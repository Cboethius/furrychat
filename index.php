<?php
session_start();

if (!isset($_SESSION['username'])) {

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FurryChat - Login</title>
		<link rel="stylesheet" href="css/login.css">
	</head>

	<body class="body">
		<div class="form-box">
			<form class="login" method="post" action="includes/conn/auth.php">

				<!-- about modal -->

				<div class="container_box">
					<button type="button" id="modal_button">About App</button>
				</div>

				<div id="customerModal">
					<div class="inner_text">
						<h3>Welcome to FurryChat <br> the Chat for furries</h3>
						<br>
						<p>This a safe space chat room, where people can chat and make in friends that enjoy being part of the furry fandom. <br>
							You can sign up and choise your perfured furry name, and select a suitable profile pic. <br>
							you can see who is online, and search for other users that maybe only or offline. <br>
							There is a also a time stamp so u can see with your frends where online last time. <br>
							Remeber this is a safe space, if your not sure what furries are here is a linnk that discribes it in more detail
							<br><br>
						<p>
							<a class="link" href="https://en.wikipedia.org/wiki/Furry_fandom">wiki Furry fandom</a>

							<button type="button" class="btn1" data-dismiss="modal">Close</button>
					</div>
				</div>


				<div class="">
					<img src="img/logo_1.png">
					<h1>FurryChat</h1>
				</div>

				<div class="login-box">
					<label class="form-label"></label>
					<input type="text" class="form-control" name="username" placeholder="Furry Name">
				</div>

				<div class="login-box">
					<label class="form-label"></label>
					<input type="password" class="form-control" name="password" placeholder="Password">
				</div>

				<button type="submit" class="btn btn-primary">LOGIN</button>
				<a class="signup" href="signup.php">Sign Up</a>
			</form>

			<?php if (isset($_GET['error'])) { ?>
				<div class="alert alert-warning" role="alert">
					<?php echo htmlspecialchars($_GET['error']); ?>
				</div>
			<?php } ?>

			<?php if (isset($_GET['success'])) { ?>
				<div class="alert alert-success" role="alert">
					<?php echo htmlspecialchars($_GET['success']); ?>
				</div>
			<?php } ?>

		</div>
	</body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<script>
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

	</html>
<?php
} else {
	header("Location: home.php");
	exit;
}
?>