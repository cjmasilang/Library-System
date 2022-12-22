<?php
if($_POST){
    include_once "config/database.php";
    include_once "classes/book.php";

    $database = new Database();
    $db = $database->getConnection();
 
    $book = new Book($db);
    $book->ISBN = $_POST['ISBN'];
     
    $book->deleteBook();
    
}
?>

