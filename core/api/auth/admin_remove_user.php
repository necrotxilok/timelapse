<?php 

Allow::Roles(array('admin'));

if (empty($_POST['user'])) {
	notFound();
}

$id = $_POST['user'];

if ($id == 'admin') {
	return_error('User admin cannot be removed');
}

$tldb = new DBConnector('timelapse');
$tldb->Delete('timing', 'user', $id);
Log::Write('All timing deleted for user "' . $id . '".', $tldb->lastSQL);


$db->Delete('users', 'user', $id);

return_ml('User ' . $id . ' removed!');