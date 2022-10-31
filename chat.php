<?php
session_start();

if (isset($_SESSION['username'])) {
	# database connection file
	include 'includes/dbconn.php';
	include 'includes/classes/user.php';
	include 'includes/classes/chat.php';
	include 'includes/classes/opened.php';
	include 'includes/classes/timeAgo.php';

	if (!isset($_GET['user'])) {
		header("Location: home.php");
		exit;
	}

	// Getting User data data
	$chatWith = getUser($_GET['user'], $conn);

	if (empty($chatWith)) {
		header("Location: home.php");
		exit;
	}

	$chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

	opened($chatWith['user_id'], $conn, $chats);
?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FurryChat App</title>
	</head>

	<body class="body">
		<div class="container">
			<a href="home.php">&#8592;</a>

			<div class="container_box">
				<img src="uploads/<?= $chatWith['p_p'] ?>" class="propic">

				<h3>
					<?= $chatWith['firstname'] ?> <br>
					<div class="namechat" title="online">
						<?php
						if (last_seen($chatWith['last_seen']) == "Active") {
						?>
							<div class="online"></div>
							<small class="">Online</small>
						<?php } else { ?>
							<small class="">
								Last seen:
								<?= last_seen($chatWith['last_seen']) ?>
							</small>
						<?php } ?>
					</div>
				</h3>
			</div>

			<div class="chatbox" id="chatBox">
				<?php
				if (!empty($chats)) {
					foreach ($chats as $chat) {
						if ($chat['from_id'] == $_SESSION['user_id']) { ?>
							<p class="pra">
								<?= $chat['message'] ?>
								<small>
									<?= $chat['created_at'] ?>
								</small>
							</p>
						<?php } else { ?>
							<p class="pra">
								<?= $chat['message'] ?>
								<small>
									<?= $chat['created_at'] ?>
								</small>
							</p>
					<?php }
					}
				} else { ?>
					<div>
						<i></i>
						No messages, <br> Start the conversation
					</div>
				<?php } ?>
			</div>
			<div class="boxtext">
				<textarea cols="3" id="message" class="message"></textarea>
				<button class="btn1" id="sendBtn">
					<img src="./img/send.svg" alt="">
				</button>
			</div>

		</div>

		<link rel="stylesheet" href="./css/chat.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

		<script>
			var scrollDown = function() {
				let chatBox = document.getElementById('chatBox');
				chatBox.scrollTop = chatBox.scrollHeight;
			}

			scrollDown();

			$(document).ready(function() {

				$("#sendBtn").on('click', function() {
					message = $("#message").val();
					if (message == "") return;

					$.post("includes/ajax/insert.php", {
							message: message,
							to_id: <?= $chatWith['user_id'] ?>
						},
						function(data, status) {
							$("#message").val("");
							$("#chatBox").append(data);
							scrollDown();
						});
				});

				// auto load for the user

				let lastSeenUpdate = function() {
					$.get("includes/ajax/update_last_seen.php");
				}
				lastSeenUpdate();

				//update every 10sec

				setInterval(lastSeenUpdate, 10000);



				// auto refresh / reload
				let fechData = function() {
					$.post("includes/ajax/getMessage.php", {
							id_2: <?= $chatWith['user_id'] ?>
						},
						function(data, status) {
							$("#chatBox").append(data);
							if (data != "") scrollDown();
						});
				}

				fechData();

				//auto up date every 0.5 sec

				setInterval(fechData, 500);

			});
		</script>
	</body>

	</html>
<?php
} else {
	header("Location: index.php");
	exit;
}
?>