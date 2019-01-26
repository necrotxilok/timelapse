<?php 

Allow::LoggedIn();

$auth = new DBConnector('auth');
$users = $auth->GetIndexed('users', 'user');

$totals = $db->Execute("SELECT user, SUM(hours) AS hours FROM timing GROUP BY user");

$userTotals = array();
foreach ($totals as $key => $ut) {
	if (!empty($users[$ut['user']])) {
		$user = $users[$ut['user']];
		if (!empty($user['active'])) {
			$ut['name'] = $user['name'];
			$ut['image'] = $user['image'];
			$userTotals[] = $ut;
		}
	}
}

return_data($userTotals);