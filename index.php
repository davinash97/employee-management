<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=UTF-8');

?> 
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style/style.css">
	<script type="module" src="src/app/script.js"></script>
	<title>Employees Management</title>
</head>

<body>
	<div class="auth-container">
		<label for="signUp">
			<h2>Sign Up</h2>
			<input type="radio" name="auth" id="signUp" value="signup" required checked>
		</label>
		<label for="logIn">
			<h2>Log In</h2>
			<input type="radio" name="auth" id="logIn" value="login" required>
		</label>
	</div>

	<div class="container">
		<form method="GET" action="src/authorization/auth.php">
			<label for="fname">
				First Name
				<input type="text" name="fname" id="fname" placeholder="Enter your first name" autocomplete="name"
					required>
			</label>
			<label for="lname">
				Last Name
				<input type="text" name="lname" id="lname" placeholder="Enter your last Name" autocomplete="family-name"
					required>
			</label>
			<label for="email">
				Email
				<input type="email" name="email" id="email" placeholder="Enter your email" autocomplete="email"
					required>
			</label>
			<label for="phone">
				Phone
				<input type="tel" name="phone" id="phone" placeholder="Enter your phone number" autocomplete="tel"
					min=10 max=10 required>
			</label>
			<label for="position">
				Position
				<input type="text" name="position" id="position" placeholder="Your position" required>
			</label>
			<div class="btn-container">
				<button type="submit">Submit</button>
				<button type="reset">Reset</button>
			</div>
		</form>
	</div>
</body>

</html>