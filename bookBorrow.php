<?php

	include_once "config/database.php";
	include_once "classes/transaction.php";

	$database = new Database();
	$db = $database->getConnection();

	if($_POST){
		$trans = new Transaction($db);
		
		$trans->borrowersid = $_POST['borrowersid'];
		$trans->ISBN = $_POST['bookid'];
		$trans->dateborrowed = $_POST['borrowDate'];
		$trans->expectedreturndate = $_POST['returnDate'];
		$trans->assistingpersonnel = $_POST['personnel'];
		
		$trans->borrowBook();
	}
?>