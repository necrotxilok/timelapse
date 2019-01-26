<?php 

Allow::LoggedIn();

$users = $db->Get("users", array('active' => true));
return_data($users);
