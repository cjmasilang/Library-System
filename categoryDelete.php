<?php
if($_POST){
    include_once "config/database.php";
    include_once "classes/category.php";

    $database = new Database();
    $db = $database->getConnection();
 
    $category = new Category($db);
    $category->categoryCode = $_POST['categoryCode'];
     
    $category->deleteCategory();
    
}
?>

