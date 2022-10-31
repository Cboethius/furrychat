<?php

session_start();

// user is logged in ?
if (isset($_SESSION['username'])) {

	//data base connection
	include '../dbconn.php';

	# get the logged in user's username from SESSION
	$id = $_SESSION['user_id'];

	$sql = "UPDATE users
	        SET last_seen = NOW() 
	        WHERE user_id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);
} else {
	header("Location: ../../index.php");
	exit;
}
