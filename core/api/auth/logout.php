<?php 

Log::Write("User ".$_SESSION['current_user']['user']." logged out.");

unset($_SESSION['current_user']);

return_msg('Logged out');
