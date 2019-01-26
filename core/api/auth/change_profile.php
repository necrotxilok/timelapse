<?php 

Allow::LoggedIn();

$data = filter($_POST, array('name', 'email'));

$user_id = $_SESSION['current_user']['user'];

$db->Update(
	'users', 
	$data, 
	array('user' => $user_id)
);

Log::Write('Profile changed!', $db->lastSQL);

$user = $db->GetFirst('users', array('user' => $user_id));
$_SESSION['current_user'] = $user;

return_data($user, 'Profile changed!');
