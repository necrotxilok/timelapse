<?php 

$parser = array(
	'auth' => array(
		'users' => array(
			'name' => function($data) {
				return empty($data['name']) ? $data['user'] : $data['name'];
			},
			'image' => function($data) {
				$gravatar = md5(!empty($data['email']) ? $data['email'] : $data['user']);
				return "https://www.gravatar.com/avatar/" . $gravatar . "?s=200&d=robohash";
			}
		)
	)
);