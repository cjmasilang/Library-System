<?php
	
	include_once "config/database.php";
	include_once "classes/book.php";

	$database = new Database();
	$db = $database->getConnection();

	if($_POST){
      $book = new Book($db);
      	$book->ISBN = $_POST['ISBN'];
    	$book->title = $_POST['title'];
    	$book->edition = $_POST['edition'];
      	$book->author = $_POST['author'];
      	$book->category = $_POST['category'];
      	$book->copies = $_POST['copies'];

    	$book->updateBook();
	}

?>