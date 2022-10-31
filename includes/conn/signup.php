<?php

// checking if it submits
if (
	isset($_POST['username']) &&
	isset($_POST['firstname']) &&
	isset($_POST['rePassword']) &&
	isset($_POST['password'])
) {

	//database
	include '../dbconn.php';

	//post data
	$password = $_POST['password'];
	$username = $_POST['username'];
	$firstname = $_POST['firstname'];

	$data = [
		'firstname' => trim($_POST['firstname']),
		'password' => trim($_POST['password']),
		'rePassword' => trim($_POST['rePassword']),
		'username' => trim($_POST['fusername'])
	];

	if (
		empty($data['username'])  || empty($data['firstname']) ||
		empty($data['password']) || empty($data['rePassword'])
	) {
	}

	if (!preg_match("/^[a-zA-Z0-9]*$/", $data['username'])) {

		$em = "User is in use";

		header("Location: ../../signup.php?error=$em&$data");
	}

	if (strlen($data['password']) < 6) {
		$em = "Invalid password";
		header("Location: ../../signup.php?error=$em&$data");
	} else if ($data['password'] !== $data['rePassword']) {
		$em = "Password don't match";
		header("Location: ../../signup.php?error=$em&$data");
		exit;
	} else {
		// checking user name 
		$sql = "SELECT username 
   	          FROM users
   	          WHERE username=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$username]);

		if ($stmt->rowCount() > 0) {
			$em = "The username ($username) is taken";
			header("Location: ../../signup.php?error=$em&$data");
			exit;
		} else {
			//profile upload
			if (isset($_FILES['pp'])) {
				// store data
				$img_name  = $_FILES['pp']['name'];
				$tmp_name  = $_FILES['pp']['tmp_name'];
				$error  = $_FILES['pp']['error'];

				//error handiling
				if ($error === 0) {

					// image extension store
					$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);


					$img_ex_lc = strtolower($img_ex);


					$allowed_exs = array("jpg", "jpeg", "png");


					if (in_array($img_ex_lc, $allowed_exs)) {

						$new_img_name = $username . '.' . $img_ex_lc;

						//upload path on root directory
						$img_upload_path = '../../uploads/' . $new_img_name;

						// move uploaded image to ./upload folder
						move_uploaded_file($tmp_name, $img_upload_path);
					} else {
						$em = "You can't upload files of this type";
						header("Location: ../../signup.php?error=$em&$data");
						exit;
					}
				}
			}

			// password hashing
			$password = password_hash($password, PASSWORD_DEFAULT);

			//upload Profile Picture
			if (isset($new_img_name)) {

				# inserting data into database
				$sql = "INSERT INTO users
                    (firstname, username, password, p_p)
                    VALUES (?,?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->execute([$firstname, $username, $password, $new_img_name]);
			} else {
				// inserting data to database
				$sql = "INSERT INTO users
                    (firstname, username, password)
                    VALUES (?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->execute([$firstname, $username, $password]);
			}

			//success message
			$sm = "Great Success :)";

			// error hanldling
			header("Location: ../../index.php?success=$sm");
			exit;
		}
	}
} else {
	header("Location: ../../signup.php");
	exit;
}
