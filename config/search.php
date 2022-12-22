<?php 

	include_once "config/database.php";
	include_once "classes/author.php";

	$database = new Database();
	$db = $database->getConnection();

	$author = new Author($db);

	$author->searchValue = isset($_GET['searchTextBox']) ? $_GET['searchTextBox'] : '';
  	$stmt = $author->viewSearchResult($from_record_num, $records_per_page);
  	$total_rows = $author->countSearchResult();

  	if(!empty($author->searchValue)){
  		echo "<div class='alert alert-warning alert-dismissable' role='alert'>
  				<strong>". $total_rows . " search results </strong>
  				  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
  				    <span aria-hidden='true'>&times</span>
  				  </button>
  			  </div>";
  	} 

?>