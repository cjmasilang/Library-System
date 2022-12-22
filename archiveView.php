<?php

	include_once "config/core.php";

	$page_title = "Archived Books";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/archive.php";
	
	$database = new Database();
	$db = $database->getConnection();

	$arcBook = new Archive($db);

	// Search Function
	echo "<div class='col-lg-6'>
			<form action='archiveView.php'>
			  <div class='form-row'>
			  	<div class='col-lg-10'>
			  	  <input type='text' class='form-control' name='searchTextBox'>
			  	</div>
			  	<div class='col-lg-2'>
			  	  <button type='submit' class='btn btn-primary float-right'>Search</button>
			  	</div>
			  </div>
			</form>
		  </div>
  	</div>";

  	$arcBook->searchValue = isset($_GET['searchTextBox']) ? $_GET['searchTextBox'] : '';
  	$stmt = $arcBook->viewSearchResult($from_record_num, $records_per_page);
  	$total_rows = $arcBook->countSearchResult();
	
  	if(!empty($arcBook->searchValue)){
  		echo "<div class='alert alert-warning alert-dismissable' role='alert'>
  				<strong>". $total_rows . " search results </strong>
  				  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
  				    <span aria-hidden='true'>&times</span>
  				  </button>
  			  </div>";
  	} 

	if($total_rows > 0){
	echo " 	<div id='archiveTable'>
			<table class='table table-hover table-bordered'>
			<thead>
				<tr class='bg-info text-center'>
					<th>Book Title</th>
					<th>Copies</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);

		echo "<tr>	
					<td>{$title}</td>
					<td>{$copies}</td>
					<td>
						<a class='btn btn-primary text-white restore-object' restore-id='{$ISBN}'>Restore</a>
						<a class='btn btn-danger text-white delete-object' delete-id='{$ISBN}'>Delete</a>
					</td>
				</tr>";
			}
			
		echo "</tbody>
			</table></div>";

		$page_url = "archiveView.php?searchTextBox={$arcBook->searchValue}&";
		include_once 'paging.php'; 
	} else {
		echo "<div class='alert alert-warning alert-dismissable' role='alert'>
  				<strong>No Books Archived!</strong>
  				  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
  				    <span aria-hidden='true'>&times</span>
  				  </button>
  			  </div>";
	}
?>

<script>
$(document).on('click', '.delete-object', function(){
    var id = $(this).attr('delete-id');
    var q = confirm("Are you sure?");
     
    if (q == true){
        $.post('bookDelete.php', {
            ISBN: id
        }, function(data){
            //location.reload();
            $("#archiveTable").load(" #archiveTable");
            alert("Record Deleted!");
        }).fail(function() {
            alert('Unable to delete.');
        });
    }
});
</script>

<script>
$(document).on('click', '.restore-object', function(){
    var id = $(this).attr('restore-id');
        $.post('index.php', {
            ISBN: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('Unable to restore.');
        });
});
</script>


