<?php 

Allow::LoggedIn();

if (empty($_POST['pass'])) {
	notFound();
}

$data = filter($_POST, array('pass'));

if (strlen($data['pass']) < 4) {
	return_error("Password must have at least 4 characters!");
}

$db->Update(
	'users', 
	$data, 
	array('user' => $_SESSION['current_user']['user'])
);

return_ml('Pasword changed!');
