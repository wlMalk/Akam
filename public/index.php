<html dir="rtl">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<?php

	require_once '../flatttr.php';
	require_once '../router.php';
	require_once '../flatttrtemps.php';

	$config = include '../config.php';
	$flatttr = new Flatttr($config, new Router($_SERVER['REQUEST_URI'], $config), new FlatttrTemps($config));
?>
	</body>
</html>