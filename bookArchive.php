<?php
if($_POST){
    include_once "config/database.php";
    include_once "classes/book.php";

    $database = new Database();
    $db = $database->getConnection();
 
    $book = new Book($db);
    $book->ISBN= $_POST['ISBN'];
     
    if($book->checkRow() > 0){
    	return false;
    } else {
    	$book->archive();
    }
    
}
?>