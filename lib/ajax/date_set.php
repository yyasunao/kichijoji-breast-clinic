<?php
require($_POST["wp_directory"].'wp-load.php');

$result = reception_situation( $_POST["post_name"] );

header('Content-Type: application/json');
	echo json_encode( $result);
	exit;

