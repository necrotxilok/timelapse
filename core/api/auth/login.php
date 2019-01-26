<?php 

if (empty($_POST['user']) || empty($_POST['pass'])) {
	notFound();
}

$data = filter($_POST, array('user', 'pass'));
$data['active'] = true;

$access = $db->GetFirst('users', $data);
if (empty($access)) {
	Log::Write("LOGIN ERROR! [".$data['user']."] User not found or incorrect password.");
	return_error('Incorrect user or password');
}

$access['lastlogon'] = date('Y-m-d H:i:s');

$db->Update(
	'users', 
	array('lastlogon' => $access['lastlogon']), 
	array('user' => $access['user'])
);

$_SESSION['current_user'] = $access;

Log::Write("User ".$access['user']." logged in.");

return_data($access);
