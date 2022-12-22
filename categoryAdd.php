<?php
  
	include_once "config/database.php";
	include_once "classes/category.php";

  $database = new Database();
  $db = $database->getConnection();
  
	if($_POST){
		$category = new Category($db);
    $category->categoryCode = $_POST['categoryCode'];
		$category->category = $_POST['category'];
		$category->days = $_POST['dayslimit'];
	
		$category->AddCategory();
  }
?>
			