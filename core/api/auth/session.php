<?php 

if (!empty($_SESSION['current_user'])) {
	return_data($_SESSION['current_user']);
}

return_error('Not logged in');