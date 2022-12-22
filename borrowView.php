
<?php
	$page_title = "Borrower";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/borrow.php";
	include_once "borrowAdd.php";

	$database = new Database();
	$db = $database->getConnection();

	$borrower = new Borrower($db);
	$stmt = $borrower->viewAllBorrower();

	echo "<div class='col-lg-6'>
      		<a class='btn btn-primary text-white float-right' data-toggle='modal' data-target='#addBorrowModal' >Add New Borrower</a>
    	   </div>
  	</div>";

	echo " <table class='table table-hover table-bordered'>
			<thead>
				<tr class='bg-info text-center'>
					<th>Borrower Name</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);

		echo "<tr>	
					<td>{$firstname} {$lastname}</td>
					<td>
						<a class='btn btn-primary' href='borrowEdit.php?borrowersId={$borrowersId}'>Edit</a>
						<a class='btn btn-danger text-white delete-object' delete-id='{$borrowersId}'>Delete</a></td>
				</tr>";
			}
			
		echo "</tbody>
			</table>";
?>

<script>
$(document).on('click', '.delete-object', function(){
    var id = $(this).attr('delete-id');
    var q = confirm("Are you sure?");
     
    if (q == true){
        $.post('borrowDelete.php', {
            borrowersId: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('Unable to delete.');
        });
    }
});
</script>

<!--- Add Borrower Modal -->
<div class="modal" id="addBorrowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Borrower</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
			  
			  <form method="POST"  id="addBorrowForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
			      <tr>
			        <th>Borrower ID</th>
			        <td><input type="text" class="form-control" name="borrowersId"></td>
			      </tr>
			      <tr>
			        <th>Firstname</th>
			        <td><input type="text" class="form-control" name="firstname"></td>
			      </tr>
			      <tr>
			        <th>Lastname</th>
			        <td><input type="text" class="form-control" name="lastname"></td>
			      </tr>
			      <tr>
			        <th>Position</th>
			        <td>
			          <select class="form-control" name="position">
			            <option selected></option>
			            <option >1</option>
			            <option >2</option>
			            <option >3</option>
			            <option >4</option>
			            <option >5</option>
			          </select>
			        </td>
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
	$('#addBorrowForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'borrowAdd.php',
		method:"POST",
		data:$('#addBorrowForm').serialize(),
		
		success: function(data){
			$('#addBorrowModal').modal('hide');
			}
		});
		return false;
	});
</script>

