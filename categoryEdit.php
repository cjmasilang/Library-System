<?php
	$page_title = "Update Category";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/category.php";
	//include_once "classes/author.php";

	if(isset($_GET['categoryCode']))
		$categoryCode = $_GET['categoryCode'];
	else
		echo "ERROR: missing ID";

	$database = new Database();
	$db = $database->getConnection();

	$category = new Category($db);
	$category->categoryCode = $categoryCode;
	$stmt = $category->viewOneCategory();

	/*$author = new Author($db);
	$author->ISBN = $ISBN;
	$stmt = $author->viewBookAuthor();*/

	if(isset($_POST['update'])){
    	$category->category = $_POST['category'];
    	$category->days = $_POST['dayslimit'];

    	if($category->updateCategory()){
    		header("Location: categoryView.php");
    	}
	}
	else if(isset($_POST['cancel'])){
		header("Location: categoryView.php");
	}

?>

</div>
<form method="POST" action="categoryEdit.php?categoryCode=<?php echo $categoryCode ?>">
  <h5><b>Category Basic Information (Category Code: <?php echo $categoryCode ?>)</b></h5>
  <div class="form-row">
    <div class="form-group col-md-8">
      <label>Category Title</label>
      <input type="text" class="form-control" name="category" value="<?php echo $category->category ?>">
    </div>
    <div class="form-group col-md-4">
      <label>Days Limit</label>
      <input type="text" class="form-control" name="dayslimit" value="<?php echo $category->days ?>">
    </div>
  </div>

  <div class="form-row float-right">
    <div class="col-lg-12 mb-3">	
      <button type="submit" class="btn btn-primary ml-2" name="update">Save Changes</button>
      <button type="submit" class="btn btn-primary ml-2" name="cancel">Cancel</button>
    </div>
  </div>  
</form>
