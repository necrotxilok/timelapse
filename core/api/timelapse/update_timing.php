<?php 

Allow::LoggedIn();

if (empty($_POST['date']) || !isset($_POST['hours'])) {
	notFound();
}

if (date('Y-m-d') < $_POST['date']) {
	return_error('You cannot set hours after today!!');
}

$data = filter($_POST, array('date', 'hours'));
$data['user'] =  $_SESSION['current_user']['user'];

$primaryKey = filter($data, array('date', 'user'));

$timing = $db->GetFirst('timing', $primaryKey);

if (empty($timing['date'])) {
	if (!empty($data['hours'])) {
		$data['created'] = date('Y-m-d H:i:s');
		$db->Insert('timing', $data);
	}
} else {
	if (!empty($data['hours'])) {
		$data['updated'] = date('Y-m-d H:i:s');
		$db->Update('timing', filter($data, array('hours', 'updated')), $primaryKey);
	} else {
		$date = $primaryKey['date'];
		$user = $primaryKey['user'];
		$db->Execute("DELETE FROM timing WHERE date='$date' AND user='$user'");
	}	
}

return_ml('Timing updated!');
