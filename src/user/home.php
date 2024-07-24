<?php

$rootDir = dirname(__DIR__);
include $rootDir . '/service/Service.php';
header('Content-Type: text/html');
$service = new Service("localhost", "admin","admin", "employees");

$getUserData = $service->readProfile($_SESSION['email']);
var_dump($_SESSION['email']);
echo $getUserData
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="styles/style.css">
	<title>Document</title>
</head>

<body>
	<div class="container">
		<h1>Hey there Avinash!</h1>
		<!-- <form>
			<label for="fname">
				First Name
				<input type="text" name="fname" id="fname" placeholder="Enter your first name" autocomplete="name"
					value=<?php echo $service->getUserData()['fname']; ?> required>
			</label>
			<label for="lname">
				Last Name
				<input type="text" name="lname" id="lname" placeholder="Enter your last Name" autocomplete="family-name"
					value="<?php echo $service->getUserData()['lname']?>" required>
			</label>
			<label for="email">
				Email
				<input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="email"
					value="<?php echo $service->getUserData()['email']?>" required>
			</label>
			<label for="phone">
				Phone
				<input type="tel" name="phone" id="phone" placeholder="Enter your phone number" autocomplete="tel"
					value="<?php echo $service->getUserData()['phone']?>" min=10 max=10 required>
			</label>
			<label for="position">
				Position
				<input type="text" name="position" id="position" placeholder="Your position" value="<?php echo
					$service->getUserData()['position']?>" required>
			</label>
		</form>
		<div class="btn-container">
			<button type="submit">Submit</button>
			<button type="reset">Reset</button>
		</div> -->
	</div>
</body>

</html>