<?php
	header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 404 Not Found');
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="UTF-8">
		<title>404 - Page not found</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<link rel='shortcut icon' type='image/x-icon' href='/img/favicon.png?beta1' />
		<style media="screen">
			body {
				background: #fff !important;
				color: #ccc!important;
				font-family: arial, verdana, sans-serif;
			}
			.container {
				position: fixed;
				top: 0;
				bottom: 0;
				left: 0;
				right: 0;
				text-align: center;
				font-size: 20px;
			}
			.container h1 {
				font-size: 200px;
				font-weight: normal;
				margin: 60px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<h1></h1>
			<p>Page not found</p>
			<h1>404</h1>
		</div>
	</body>
</html>
