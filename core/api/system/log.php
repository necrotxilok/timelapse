<?php 

Allow::Roles(array('admin'));

return_data(
	$db->Execute("SELECT * FROM log ORDER BY datetime DESC")
);
