
<?php
	include_once "config/core.php";
	$page_title = "Author";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/author.php";

	$database = new Database();
	$db = $database->getConnection();

	$author = new Author($db);

	echo "<div class='col-lg-6'>
			<form action='authorView.php'>
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
		  <div class='col-lg-3'>
      		<a class='btn btn-primary float-right text-white' data-toggle='modal' data-target='#addAuthorModal' >Add New Author</a>
    	  </div>
  	</div>";

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

  	if(isset($_GET['writtenId'])){
		$author->authorId = $_GET['writtenId'];
		echo " <script>
			$(document).ready(function(){
				$('#writtenBooks').modal('show')
			})
		</script>";		  	
	}

	// for edit modal
		if(isset($_GET['updateId'])){
			$author->authorId = $_GET['updateId'];
			echo " <script>
				$(document).ready(function(){
					$('#editAuthorModal').modal('show');
				});
			</script>";  	
		}

  	if($total_rows > 0){
	echo " 	<div id='authorTable'>
			<form action='authorView.php'>
			<table class='table table-hover table-bordered'>
			<thead>
				<tr class='bg-info text-center'>
					<th>Author</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);

		echo "<tr>	
					<td>{$firstname} {$middlename} {$lastname}</td>
					<td>
						<button type='submit' class='btn btn-primary updateAuthor' value='{$authorId}' name='updateId'>Edit</button>
						<button type='submit' class='btn btn-primary viewBook' value='{$authorId}' name='writtenId'>Written Books</button>
						<a class='btn btn-danger text-white delete-object' delete-id='{$authorId}'>Delete</a>
					</td>
				</tr>";
			}
			
		echo "</tbody>
			</table></form></div>";

		$page_url = "authorView.php?searchTextBox={$author->searchValue}&";
		include_once 'paging.php';
	}
?>

<script>
$(document).on('click', '.delete-object', function(){
    var id = $(this).attr('delete-id');
    var q = confirm("Are you sure?");
     
    if (q == true){
        $.post('authorDelete.php', {
            authorId: id
        }, function(data){
            //location.reload();
            $("#authorTable").load(" #authorTable");
            alert("Record Deleted!");
        }).fail(function() {
            alert('Unable to delete.');
        });
    }
});
</script>

<!--- Update Modal -->
<div class="modal" id="editAuthorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
			  
			  <form method="POST"  id="editAuthorForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
					<?php 
						$stmt = $author->viewOneAuthor();
					?>
			      <tr>
			        <th>Author ID</th>
			        <td><input type="text" class="form-control" name="authorId" value="<?php echo $author->authorId; ?>"></td>
			      </tr>
			      <tr>
			        <th>First Name</th>
			        <td><input type="text" class="form-control" name="firstname" value="<?php echo $author->firstname; ?>"></td>
			      </tr>
			      <tr>
			        <th>Middle Name</th>
			        <td><input type="text" class="form-control" name="middlename" value="<?php echo $author->middlename; ?>"></td>
			      </tr>
			      <tr>
			        <th>Last Name</th>
			        <td><input type="text" class="form-control" name="lastname" value="<?php echo $author->lastname; ?>"></td>
			      </tr>
			    </table>
			  </div>
			  <div class="modal-footer">
				<input type="submit" class="btn btn-primary" name="save" value="Save">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel">Cancel</button>
			  </div>
			  </form>
			  
            </div>
          </div>
</div>

<script>
	$('#editAuthorForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'authorEdit.php',
		method:"POST",
		data:$('#editAuthorForm').serialize(),
		
		success: function(data){
			$('#editAuthorModal').modal('hide');
			$("#authorTable").load(" #authorTable");
            bootbox.alert("<h5>Record Updated!</h5>");
			}
		});
		return false;
	});
</script>

<!--- Add Author Modal -->
<div class="modal" id="addAuthorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Author</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
			  
			  <form method="POST"  id="addAuthorForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
				  <tr>
					<th>First Name</th>
					<td><input type="text" class="form-control" name="firstname"></td>
				  </tr>
				  <tr>
					<th>Last Name</th>
					<td><input type="text" class="form-control" name="lastname"></td>
				  </tr>
				  <tr>
					<th>Middle Name</th>
					<td><input type="text" class="form-control" name="middlename"></td>
				  </tr>
			    </table>
			  </div>
			  <div class="modal-footer">
				<input type="submit" class="btn btn-primary" name="save" value="Save">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel">Cancel</button>
			  </div>
			  </form>
			  
            </div>
          </div>
</div>

<script>
	$('#addAuthorForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'authorAdd.php',
		method:"POST",
		data:$('#addAuthorForm').serialize(),
		
		success: function(data){
			$('#addAuthorModal').modal('hide');
			$("#authorTable").load(" #authorTable");
			alert("Record Saved!");
		}
		});
		return false;
	});
</script>

<!-- Written Books Modal -->
<div id="writtenBooks" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<?php 
        	$state = $author->checkBooks();
        	$row = $state->fetch(PDO::FETCH_ASSOC);
        	echo "<h4 class='modal-title'>".$row['firstname']." ".$row['middlename']." ".$row['lastname']."</h4>";
    	?>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>'
      </div>
      <div class='modal-body'>
	    <table class='table table-hover table-bordered'>
	       	<thead>
	       		<tr class='bg-info text-center'>
				  <th>Book Title</th>
				</tr>
        	</thead>
        	<tbody>
	       		<?php 
			        $state = $author->checkBooks();
			        while($row = $state->fetch(PDO::FETCH_ASSOC)){
			   		  extract($row);
					echo "<tr><td>".$row['title']."</td></tr>";}
				?>
		    </tbody>
	    </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>