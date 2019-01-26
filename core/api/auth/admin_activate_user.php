<?php 

Allow::Roles(array('admin'));

if (empty($_POST['user'])) {
	notFound();
}

$user_id = $_POST['user'];

if ($user_id == 'admin') {
	return_error('Cannot deactivate the user admin');
}

$user = $db->GetFirst('users', array('user' => $user_id));

$db->Update('users', array('active' => $user['active'] ? false : 1), array('user' => $user_id));

if ($user['active']) {
	return_ml('User ' . $user_id . ' deactivated');
} else {
	return_ml('User ' . $user_id . ' activated');
}