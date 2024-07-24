<?php

include_once "src/helper/helper.php";

$helper = new Helper();

$hostname = "localhost";
$username = "admin";
$password = "admin";
$database = "employees";

$conn = new mysqli($hostname, $username, $password);

if ($conn->connect_error) {
	die("Error Connecting: ". $conn->connect_error);
} else {
	$sql = "CREATE DATABASE IF NOT EXISTS $database";
	
	$result = $conn->query($sql);
	if ($result === TRUE) {
		$helper->log_this("Database Created Successfully");
		$sql = "CREATE TABLE IF NOT EXISTS $database (
				`id` INT NOT NULL AUTO_INCREMENT,
				`first_name` VARCHAR(50),
				`last_name` VARCHAR(50),
				`email` VARCHAR(200) NOT NULL,
				`phone` BIGINT(15) NOT NULL,
				`position` VARCHAR(100),
				`profile_picture` VARCHAR(255),
				`is_admin` BOOLEAN,
				PRIMARY KEY (`id`)
			)";
		$conn = new mysqli($hostname, $username, $password, $database);
		$result = $conn->query($sql);
		if ($result === TRUE) {
			$helper->log_this("Table Created Successfully");
		} else {
			$helper->log_this("Table creation failed, could be because already exists");
		}
	} else {
		$helper->log_this("Database creation failed, could be because already exists");
	}
}
?>