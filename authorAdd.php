<?php

	include_once "config/database.php";
	include_once "classes/author.php";

	$database = new Database();
	$db = $database->getConnection();

	if($_POST){
		$author = new Author($db);

		//$author->authorId = $_POST['authorId'];
		$author->lastname = $_POST['lastname'];
		$author->firstname = $_POST['firstname'];
		$author->middlename = $_POST['middlename'];
		
		$author->addAuthor();
	}
?>


