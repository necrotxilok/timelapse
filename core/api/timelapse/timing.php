<?php 

Allow::LoggedIn();

$user_id = $_SESSION['current_user']['user'];

//$timing = $db->Get("timing", array('user' => $user_id));
$timing = $db->Execute("SELECT * FROM timing WHERE user = '$user_id' ORDER BY date ASC");


return_data($timing);
