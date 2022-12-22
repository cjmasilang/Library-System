<?php 
	include_once "config/database.php";
	include_once "classes/borrow.php";

	session_start();
	if(isset($_SESSION['borrowersId'])){
		header('location: index.php');
	}
	
	if($_POST){
		$database = new Database();
		$db = $database->getConnection();

		$borrow = new Borrower($db);

		//values passed from the form of login
		$borrow->borrowersId = $_POST['borrowersId'];
		$borrow->password = $_POST['password'];

		if($borrow->login()){
			//if successful login then goes to index.php
				header("Location: index.php");
			/*echo "<script>
					window.open('index.php','_self');
				  </script>";*/
		} else {
			//if not it displays this message
			echo '
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
				  <strong>ERROR!</strong> Invalid Input!
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				    <span aria-hidden="true">&times;</span>
				  </button>
				</div>';
		}
	}

	echo '
	<html>
		<head>
			<title>Login Form</title>
			<!-- CSS -->
			<link rel="stylesheet" href="assets\bootstrap\4.0.0\css\bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
			
			<!-- JQuery -->
			<script src="assets\jQuery\3.2.1\jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

			<!-- POPPER-->
			<script src="assets\popper.js\1.12.9\popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			
			<!-- BOOTSTRAP -->
			<script src="assets\bootstrap\4.0.0\js\bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		</head>
		<body style = "padding: 5% 38% 5%;">
			<div class="card" style="width: 20rem;">
				<form method="POST" action="login.php">
					<div class="card-body">
						<div class="form-group col-md-12">
							<label for="exampleInputEmail1">ID</label>
							<input type="text" class="form-control" id="doctorId" placeholder="Enter ID" name="borrowersId">
						</div>
						<div class="form-group col-md-12">
							<label for="exampleInputPassword1">Password</label>
							<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
						</div>
						<div class="form-group col-md-12">
							<button type="submit" class="btn btn-primary" name="submit">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</body>
	</html>
	';
?>