<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		include_once "config/database.php";
		include_once "classes/borrow.php";

		$database = new Database();
		$db = $database->getConnection();

		$barrow = new Borrower($db);
	?>
	<h1>Hello World!</h1>
	<a href="logout.php">Log Out</a>
</body>
</html>