<?php
require($_POST["wp_directory"].'wp-load.php');

$result = reception_situation();

header('Content-Type: application/json');
	echo json_encode( $result);
	exit;

