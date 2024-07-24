<?php

$root_dir = __DIR__;
require_once $root_dir . "/../service/Service.php";

$service = new Service("localhost", "admin","admin", "employees");

if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
	$service->deleteProfile($_COOKIE['email']);
	cookieDelete();
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$first_name = isset($_POST['fname']) ? $_POST['fname'] : '';
	$last_name = isset($_POST['lname']) ? $_POST['lname'] : '';
	$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';
	$phone = isset($_COOKIE['phone']) ? $_COOKIE['phone'] : '';
	$position = isset($_POST['position']) ? $_POST['position'] : '';

	if ($service->updateProfile($first_name, $last_name, $email, $phone, $position) != null) {
		echo "<script>alert('Profile updated successfully');</script>";
		header('Location: home.php');
	}
} else {
	cookieDelete();
}

function deleteCookie($cookieName) {
	setcookie($cookieName, '', time() - 3600, '/');
}

function cookieDelete() {
	if (isset($_COOKIE['email'])) {
		deleteCookie('email');
	}
	if (isset($_COOKIE['phone'])) {
		deleteCookie('phone');
	}
	header("Location: ../../index.php");
	exit;
}
?>