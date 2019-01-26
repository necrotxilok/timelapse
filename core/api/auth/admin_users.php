<?php 

Allow::Roles(array('admin'));

$users = $db->Get("users", "", "admin");
return_data($users);
