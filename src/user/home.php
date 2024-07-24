<?php
header('Content-Type: text/html');

if ($_COOKIE['email'] == null){
	header("Location: ../../index.php");
}

$rootDir = dirname(__DIR__);
include $rootDir . '/service/Service.php';
$service = new Service("localhost", "admin","admin", "employees");

$getUserData = $service->readProfile($_COOKIE['email']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles/style.css">
	<script src="app/script.js" async></script>
	<title>Document</title>
</head>

<body>
	<div class="container">
		<h1>Welcome Avinash!</h1>
		<form method="POST" action="profile.php" enctype="multipart/form-data">
			<label for="fname">
				First Name
				<input type="text" name="fname" id="fname" autocomplete="name"
					placeholder="<?php echo $getUserData['first_name']; ?>" required>
			</label>
			<label for="lname">
				Last Name
				<input type="text" name="lname" id="lname" autocomplete="family-name"
					placeholder="<?php echo $getUserData['last_name']?>" required>
			</label>
			<label for="email">
				Email
				<input type="email" name="email" id="email" autocomplete="email"
					placeholder="<?php echo $getUserData['email']?>" required disabled>
			</label>
			<label for="phone">
				Phone
				<input type="tel" name="phone" id="phone" autocomplete="tel"
					placeholder="<?php echo $getUserData['phone']?>" min=10 max=10 required disabled>
			</label>
			<label for="position">
				Position
				<input type="text" name="position" id="position" placeholder="<?php echo
					$getUserData['position']?>" required>
			</label>
			<label for="profile_picture">
				Profile Picture
				<input type="file" name="profile_picture" id="profile_picture" accept="image/*">
			</label>
		</form>
		<div class="btn-container">
			<button type="submit">Update</button>
			<button type="reset">Reset Form</button>
			<button type="button">Delete Profile</button>
			<button type="button">Log Out</button>
		</div>
	</div>
</body>

</html>