<?php
session_start();

if (!isset($_SESSION['username'])) {
?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FurryChat Sign Up</title>
		<link rel="stylesheet" href="css/signup.css">
		<link rel="icon" href="img/logo.png">
	</head>

	<body class="body">
		<div class="form-box">
			<form method="post" action="includes/conn/signup.php" enctype="multipart/form-data">
				<div>
					<img src="img/logo_1.png" class="w-25">
					<h1>Sign Up</h1>
				</div>

				<?php if (isset($_GET['error'])) { ?>
					<div class="alert alert-warning" role="alert">
						<?php echo htmlspecialchars($_GET['error']); ?>
					</div>
				<?php }

				// get username and firstname

				if (isset($_GET['firstname'])) {
					$firstname = $_GET['firstname'];
				} else $firstname = '';

				if (isset($_GET['username'])) {
					$username = $_GET['username'];
				} else $username = '';
				?>

				<div class="login-box">
					<input type="text" name="firstname" id="firstname" value="<?= $firstname ?>" placeholder="First Name...">
				</div>

				<div class="login-box">
					<input type="text" value="<?= $username ?>" name="username" placeholder="Furry Name">
				</div>


				<div class="login-box">
					<input type="password" name="password" placeholder="password">
				</div>

				<div class="login-box">
					<input type="password" name="rePassword" placeholder="Repeat password">
				</div>


				<div class="choose">
					<input type="file" name="pp" id="pp">
				</div>

				<button type="submit" id="submit" class="btn">Sign Up</button>
				<a href="index.php">Login</a>
			</form>

		</div>
	</body>

	</html>
<?php
} else {
	header("Location: home.php");
	exit;
}
?>