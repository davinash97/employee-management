<?php

// header('Content-Type: application/json');

$rootDir = dirname(__DIR__);
include $rootDir . '/service/Service.php';

$service = new Service("localhost", "admin","admin", "employees");

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
			allowAccess($result);
		} else {
			restrictAccess("User already exists, try logging in. Redirect to login page?");
		}
	} else {
		echo "<script>alert('Email or Phone is mandatory');</script>";
	}
} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
		if (isset($_GET["email"]) || isset($_GET["phone"])) {
		$email = isset($_GET['email']) ? $_GET['email'] : '';
		$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
		
		$existingUser = ($email == '') ? $service->isExistingUser($phone) : $service->isExistingUser($email);
		$result = $service->readProfile($email);

		if ($existingUser === true) {
			allowAccess($result);
			$result = $service->readProfile($email);
		} else {
			restrictAccess("User not Found!! Redirect to login page?");
		}
	} else {
		$result['status'] = 'failed';
		$result['result'] = 'Email or Phone is mandatory';
	}
}

function saveCookie($result){
	setcookie('email', $result['email'], time() + (86400), "/");
	setcookie('phone', $result['phone'], time() + (86400), "/");
}

function allowAccess($result){
	saveCookie($result);
	sleep(1); // For One Second
	header('Location: ../user/home.php');
}

function restrictAccess($message) {
	echo "<script>
			alert('$message');
			setTimeout(function() {
				window.location.href = '../../index.php';
			}, 1000); // 1000 milliseconds = 1 seconds
		  </script>";
}
