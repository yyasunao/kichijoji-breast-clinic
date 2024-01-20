<?php
require($_POST["wp_directory"].'/wp-load.php');

$result = reception_time($_POST["date"]);

header('Content-Type: application/json');
	echo json_encode( $result);
	exit;

