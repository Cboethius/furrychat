<?php

session_start();

// checking if user logged in
if (isset($_SESSION['username'])) {
	//check if the key is submitted
	if (isset($_POST['key'])) {

		//conntected to database
		include '../dbconn.php';

		//creating simple search algorithm with username and firstname 
		$key = "%{$_POST['key']}%";

		$sql = "SELECT * FROM users
	           WHERE username
	           LIKE ? OR firstname LIKE ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$key, $key]);

		if ($stmt->rowCount() > 0) {
			$users = $stmt->fetchAll();

			foreach ($users as $user) {
				if ($user['user_id'] == $_SESSION['user_id']) continue;
?>
				<li>
					<a href="chat.php?user=<?= $user['username'] ?>">
						<div>

							<img src="uploads/<?= $user['p_p'] ?>">

							<h3>
								<?= $user['firstname'] ?>
							</h3>
						</div>
					</a>
				</li>
			<?php }
		} else { ?>
			<div>
				<i></i>
				The user "<?= htmlspecialchars($_POST['key']) ?>"
				is not found.
			</div>
<?php }
	}
} else {
	header("Location: ../../index.php");
	exit;
}
