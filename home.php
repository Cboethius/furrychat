<?php
session_start();

if (isset($_SESSION['username'])) {
	//connected database
	include 'includes/dbconn.php';
	include 'includes/classes/user.php';
	include 'includes/classes/conversations.php';
	include 'includes/classes/timeAgo.php';
	include 'includes/classes/last_chat.php';

	// getting user
	$user = getUser($_SESSION['username'], $conn);

	//getting conversation
	$conversations = getConversation($user['user_id'], $conn);

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FurryChat - Home</title>

	</head>

	<body>
		<div class="container">
			<div>
				<div class="section_1">
					<div class="upload">
						<h1><?= $user['firstname'] ?></h1>
						<img class="propic" src="uploads/<?= $user['p_p'] ?>">
					</div>
					<a href=" logout.php" class="logout">Logout</a>
				</div>

				<div class="search_bar">
					<input type="text" placeholder=" Search..." id="searchText">
					<button class="search" id="serachBtn">
						<i><img class="mag" src="./img/magnifying-glass-solid.svg" alt=""></i>
					</button>
				</div>

				<ul id="chatList" class="chatlist">
					<?php if (!empty($conversations)) { ?>
						<?php

						foreach ($conversations as $conversation) { ?>
							<li class="list">
								<a href="chat.php?user=<?= $conversation['username'] ?>">
									<div class="under_list">
										<img src="uploads/<?= $conversation['p_p'] ?>">
										<h3>
											<?= $conversation['firstname'] ?><br>
											<small>
												<?php
												echo lastChat($_SESSION['user_id'], $conversation['user_id'], $conn);
												?>
											</small>
										</h3>
									</div>
									<?php if (last_seen($conversation['last_seen']) == "Active") { ?>
										<div title="online">
											<div class="online"></div>
										</div>
									<?php } ?>
								</a>
							</li>
						<?php } ?>
					<?php } else { ?>
						<div class="no_message">
							<i></i>
							No messages, Start the conversation
						</div>
					<?php } ?>
				</ul>
			</div>


			<div class="container_box">
				<button type="button" id="modal_button">Add Furry Friend</button>
				<a href="create.php">Furry friends list</a>

			</div>

			<!-- This is Customer Modal. It will be use for Create new Records and Update Existing Records!-->
			<div id="customerModal">
				<h4 class="modal-title">Add Furry Friend</h4>
				<br>
				<div class="login-box">
					<label>Enter Name</label>
					<input type="text" name="user_names" id="user_names" />
				</div>
				<br>
				<div class="login-box">
					<label>Enter Furry Name</label>
					<input type="text" name="furry_name" id="furry_name" />

					<br>
					<input type="hidden" name="customer_id" id="customer_id" />
					<input type="submit" name="action" id="action" class="btn btn-success" />
					<br>
					<button type="button" class="btn" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</body>

	<link rel="stylesheet" href="css/home.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<script>
		$(document).ready(function() {

			// Search
			$("#searchText").on("input", function() {
				var searchText = $(this).val();
				if (searchText == "") return;
				$.post('includes/ajax/search.php', {
						key: searchText
					},
					function(data, status) {
						$("#chatList").html(data);
					});
			});

			// Search using the button
			$("#serachBtn").on("click", function() {
				var searchText = $("#searchText").val();
				if (searchText == "") return;
				$.post('includes/ajax/search.php', {
						key: searchText
					},
					function(data, status) {
						$("#chatList").html(data);
					});
			});


			//auto update for looged users

			let lastSeenUpdate = function() {
				$.get("includes/ajax/update_last_seen.php");
			}
			lastSeenUpdate();

			//update every 10sec last seen

			setInterval(lastSeenUpdate, 10000);

		});





		$(document).ready(function() {
			fetchUser(); //This function will load all data on web page when page load
			function fetchUser() // This function will fetch data from table and display under <div id="result">
			{
				var action = "Load";
				$.ajax({
					url: "action.php", //Request send to "action.php page"
					method: "POST", //Using of Post method for send data
					data: {
						action: action
					}, //action variable data has been send to server
					success: function(data) {
						$('#result').html(data); //It will display data under div tag with id result
					}
				});
			}

			//This JQuery code will Reset value of Modal item when modal will load for create new records
			$('#modal_button').click(function() {
				$('#customerModal').modal('show'); //It will load modal on web page
				$('#user_names').val(''); //This will clear Modal first name textbox
				$('#furry_name').val(''); //This will clear Modal last name textbox
				$('.modal-title').text("Add Furry friend"); //It will change Modal title to Create new Records
				$('#action ').val('Add'); //This will reset Button value ot Create
			});

			//This JQuery code is for Click on Modal action button for Create new records or Update existing records. This code will use for both Create and Update of data through modal
			$('#action').click(function() {
				var first_Name = $('#user_names').val(); //Get the value of first name textbox.
				var lastName = $('#furry_name').val(); //Get the value of last name textbox
				var id = $('#customer_id').val(); //Get the value of hidden field customer id
				var action = $('#action ').val(); //Get the value of Modal Action button and stored into action variable
				if (first_Name != '' && lastName != '') //This condition will check both variable has some value
				{
					$.ajax({
						url: "action.php", //Request send to "action.php page"
						method: "POST", //Using of Post method for send data
						data: {
							first_Name: first_Name,
							lastName: lastName,
							id: id,
							action: action
						}, //Send data to server
						success: function(data) {
							alert(data); //It will pop up which data it was received from server side
							$('#customerModal').modal('hide'); //It will hide Customer Modal from webpage.
							fetchUser(); // Fetch User function has been called and it will load data under divison tag with id result
						}
					});
				} else {
					alert("Both Fields are Required"); //If both or any one of the variable has no value them it will display this message
				}
			});

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