<?php 

Allow::Roles(array('admin'));

if (empty($_POST['user']) || empty($_POST['pass']) || empty($_POST['role'])) {
	notFound();
}

$id = !empty($_POST['__id__']) ? $_POST['__id__'] : null;

$data = filter($_POST, array('user', 'name', 'pass', 'email', 'role', 'active'));

if (strlen($data['user']) < 4) {
	return_error('User ID must have at least 4 characters');
}

if (strlen($data['pass']) < 4) {
	return_error('User pasword must have at least 4 characters');
}

if (empty($data['active']) || $data['active'] == 'false') {
	$data['active'] = false;
} else {
	$data['active'] = 1;
}

if ($id == 'admin') {
	if ($id != $data['user']) {
		return_error('Cannot change the ID for the user admin');
	}
	if ($data['role'] != 'admin') {
		return_error('Cannot change the role for the user admin');
	}
	if (empty($data['active'])) {
		return_error('Cannot deactivate the user admin');
	}
}

$exists = $db->Count('users', array('user' => $data['user']));

if (!empty($id)) {
	if ($exists && $id != $data['user']) {
		return_error('User ID assigned to other user');
	}
	$db->Update('users', $data, array('user' => $id));
	$msg = 'User ' . $id . ' updated!';
	if ($id != $data['user']) {
		$tldb = new DBConnector('timelapse');
		$tldb->Update('timing', array('user' => $data['user']), array('user' => $id));
		Log::Write('User ID changed from "'.$id.'" to "'.$data['user'].'".', $tldb->lastSQL);
	}
} else {
	if ($exists) {
		return_error('User ID assigned to other user');
	}
	$db->Insert('users', $data);
	$msg = 'User ' .  $data['user'] . ' created!';
}
Log::Write($msg, $db->lastSQL);

$user = $db->GetFirst('users', array('user' => $data['user']));

if ($_SESSION['current_user']['user'] == $id) {
	$_SESSION['current_user'] = $user;
}

return_data($user, $msg);
