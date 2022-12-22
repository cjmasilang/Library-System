<?php

	include_once "config/database.php";
	include_once "classes/borrow.php";

  $database = new Database();
  $db = $database->getConnection();

  
	if($_POST){
		$borrower = new Borrower($db);
  
		$borrower->firstname = $_POST['firstname'];
		$borrower->lastname = $_POST['lastname'];
		$borrower->position = $_POST['position'];

		$borrower->AddBorrower();
  }
?>