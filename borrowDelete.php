<?php
if($_POST){
    include_once "config/database.php";
    include_once "classes/borrow.php";

    $database = new Database();
    $db = $database->getConnection();
 
    $borrower = new Borrower($db);
    $borrower->borrowersId = $_POST['borrowersId'];
     
    $borrower->deleteBorrower();
    
}
?>

