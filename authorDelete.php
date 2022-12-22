<?php
if($_POST){
    include_once "config/database.php";
    include_once "classes/author.php";

    $database = new Database();
    $db = $database->getConnection();
 
    $author = new Author($db);
    $author->authorId= $_POST['authorId'];
     
    $author->deleteAuthor();
    
}
?>