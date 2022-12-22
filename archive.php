<?php

	include_once "config/core.php";

	$page_title = "Archived Books";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/book.php";
	
	$database = new Database();
	$db = $database->getConnection();

	$book = new Book($db);
	$stmt = $book->viewAllBook($from_record_num, $records_per_page);

	
	echo " <table class='table table-hover table-bordered'>
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
					<td>
						<a class='btn btn-primary text-white restore-object' restore-id='{$ISBN}'>Restore</a>
						<a class='btn btn-danger text-white delete-object' delete-id='{$ISBN}'>Delete</a>
					</td>
				</tr>";
			}
			
		echo "</tbody>
			</table>";

		$page_url = "archive.php?searchTextBox={$book->searchValue}&";
		$total_rows = $book->countAll();
		include_once 'paging.php'; 
?>

<script>
$(document).on('click', '.delete-object', function(){
    var id = $(this).attr('delete-id');
    var q = confirm("Are you sure?");
     
    if (q == true){
        $.post('bookDelete.php', {
            ISBN: id
        }, function(data){
            location.reload();
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
            alert('Unable to delete.');
        });
});
</script>