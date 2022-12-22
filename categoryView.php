
<?php
	$page_title = "Categories";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/category.php";

	$database = new Database();
	$db = $database->getConnection();

	$category = new Category($db);
	$stmt = $category->viewAllCategory();

	echo "<div class='col-lg-6'>
      		<a class='btn btn-primary text-white float-right' data-toggle='modal' data-target='#addCategoryModal'>Add New Category</a>
    	   </div>
  	</div>";

	echo " <table class='table table-hover table-bordered'>
			<thead>
				<tr class='bg-info text-center'>
					<th>Category Name</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);

		echo "<tr>	
					<td>{$category}</td>
					<td>
						<a class='btn btn-primary' href='categoryEdit.php?categoryCode={$categoryCode}'>Edit</a>
						<a class='btn btn-danger text-white delete-object' delete-id='{$categoryCode}'>Delete</a></td>
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
        $.post('categoryDelete.php', {
            categoryCode: id
        }, function(data){
            location.reload();
        }).fail(function() {
            alert('Unable to delete.');
        });
    }
});
</script>

<!--- Add Category Modal -->
<div class="modal" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
			  
			  <form method="POST"  id="addCategoryForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
			      <tr>
			        <th>Category Code</th>
			        <td><input type="text" class="form-control" name="categoryCode"></td>
			      </tr>
			      <tr>
			        <th>Category</th>
			        <td><input type="text" class="form-control" name="category"></td>
			      </tr>
			      <tr>
			        <th>Days Limit</th>
			        <td><input type="text" class="form-control" name="dayslimit"></td>
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
	$('#addCategoryForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'categoryAdd.php',
		method:"POST",
		data:$('#addCategoryForm').serialize(),
		
		success: function(data){
			$('#addCategoryModal').modal('hide');
			}
		});
		return false;
	});
</script>

