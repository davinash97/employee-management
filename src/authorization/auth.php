<?php

// header('Content-Type: application/json');

$rootDir = dirname(__DIR__);
include $rootDir . '/service/Service.php';

$service = new Service("localhost", "admin","admin", "employees");

session_start();
$result = array();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	if (isset($_POST["email"]) || isset($_POST["phone"])) {
		$first_name = isset($_POST['fname']) ? $_POST['fname'] : '';
		$last_name = isset($_POST['lname']) ? $_POST['lname'] : '';
		$email = isset($_POST['email']) ? $_POST['email'] : '';
		$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
		$position = isset($_POST['position']) ? $_POST['position'] : '';
		
		$existingUser = ($email == '') ? $service->isExistingUser($phone) : $service->isExistingUser($email);

		if ($existingUser === false) {
			$service->createProfile($first_name, $last_name, $email, $phone, $position);
			$result = $service->readProfile($email);
			$_SESSION['email'] = $result['email'];
			$_SESSION['phone'] = $result['phone'];
			// $_SESSION['phone'] = $service->readProfile($phone);
			// echo "<script>alert('Successfully created');</script>";
			header('Location: ../user/home.php');
		} else {
			echo "<script>alert('User already exists');</script>";
		}
	} else {
		echo "<script>alert('Email or Phone is mandatory');</script>";
	}
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
		if (isset($_GET["email"]) || isset($_GET["phone"])) {
		$email = isset($_GET['email']) ? $_GET['email'] : '';
		$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
		
		$existingUser = ($email == '') ? $service->isExistingUser($phone) : $service->isExistingUser($email);

		if ($existingUser === false) {
			echo "<script>alert('User not found');</script>";
		} else {
			$result = $service->readProfile($_COOKIE['email']);
			setcookie('email', $result['email'], time() + (86400), "/");
			setcookie('phone', $result['phone'], time() + (86400), "/");
			// header('Location: ../user/home.php');
			header('Content-Type: application/json');
			// var_dump($result);
			echo json_encode($result);
			// echo json_encode($_SESSION['phone']);
		}
	} else {
		$result['status'] = 'failed';
		$result['result'] = 'Email or Phone is mandatory';
	}
}
session_abort();