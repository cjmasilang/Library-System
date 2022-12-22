<?php
	
	include_once "config/database.php";
	include_once "classes/author.php";

	$database = new Database();
	$db = $database->getConnection();

	if($_POST){
      $author = new Author($db);

      $author->authorId = $_POST['authorId'];
    	$author->firstname = $_POST['firstname'];
    	$author->lastname = $_POST['lastname'];
      $author->middlename = $_POST['middlename'];

    	$author->updateAuthor();
  }
?>