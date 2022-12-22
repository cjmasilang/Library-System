
<?php
	session_start();
	if(!isset($_SESSION['borrowersId'])){
		header("Location: login.php");
	} else {
		$borrower = $_SESSION['borrowersId'];
	}

	include_once "config/core.php";
	$page_title = "Book";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/book.php";
	include_once "classes/transaction.php";

	$database = new Database();
	$db = $database->getConnection();

	$book = new Book($db);
	$trans = new Transaction($db);

	// for restoring archived books
	if(isset($_POST['ISBN'])){
		$book->ISBN= $_POST['ISBN'];
	    $book->restore();
    }

	// search box and button + add book button
	echo "<div class='col-lg-6'>
			<form action='index.php'>
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
      		<a class='btn btn-primary text-white float-right' data-toggle='modal' data-target='#addBookModal'>Add New Book</a>
    	  </div>
  	</div>";

  	$book->searchValue = isset($_GET['searchTextBox']) ? $_GET['searchTextBox'] : '';
  	$stmt = $book->viewSearchResult($from_record_num, $records_per_page);
  	$total_rows = $book->countSearchResult();

  	if(!empty($book->searchValue)){
  		echo "<div class='alert alert-warning alert-dismissable' role='alert'>
  				<strong>". $total_rows . " search results </strong>
  				  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
  				    <span aria-hidden='true'>&times</span>
  				  </button>
  			  </div>";
  	} 

  	// alert message for archiving
  		if(isset($_GET['archiveId'])){
			$book->ISBN = $_GET['archiveId'];
			$state = $book->checkRow();
			$num = $state->rowCount();
		  	if($num > 0){
		    		echo "<div class='alert alert-warning alert-dismissable' role='alert'>
		  				<a class='alert-link' data-toggle='modal' href='#transaction'>".$num." Transactions found!</a>
		  				  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		  				    <span aria-hidden='true'>&times</span>
		  				  </button>
		  			  </div>";
		    } else {
		    	if($book->archive()){
		    	echo '<script>
		    			$(document).ready(function(){
		    				$("#bookTable").load(" #bookTable");
		    			});
		    		  </script>';
		    	echo "<div class='alert alert-warning alert-dismissable' role='alert'>
		  				<strong>Record Archived!</strong>
		  				  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		  				    <span aria-hidden='true'>&times</span>
		  				  </button>
		  			  </div>";
		  		}
		    }
		}

		// for edit modal
		if(isset($_GET['updateId'])){
			$book->ISBN = $_GET['updateId'];
			echo " <script>
				$(document).ready(function(){
					$('#updateModal').modal('show');
				});
			</script>";  	
		}

		// for borrow modal
		if(isset($_GET['borrowId'])){
			$book->ISBN = $_GET['borrowId'];
			$trans->ISBN = $_GET['borrowId'];

			$book->viewOneBook();
			$state = $trans->checkTrans();
			$records = $state->rowCount();
			
			if($records < $book->copies){
				echo " <script>
				$(document).ready(function(){
					$('#borrowModal').modal('show');
				});
				</script>";  
			} else {
				echo "<div class='alert alert-warning alert-dismissable' role='alert'>
		  				<strong>No more book in stock!</strong>
		  				  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		  				    <span aria-hidden='true'>&times</span>
		  				  </button>
		  			  </div>";
			}		
		}

	if($total_rows > 0){
	echo "  <div  id='bookTable'>
			<form action='index.php'>
			<table class='table table-hover table-bordered'>
			<thead>
				<tr class='bg-info text-center'>
					<th>Book Title</th>
					<th>Copies</th>
					<th>In stock</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>";

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				extract($row);

		echo "<tr>	
					<td>{$title}</td>
					<td>{$copies}</td>";

					// in stock
					$trans->ISBN = $row['ISBN'];
					$state = $trans->checkTrans();
					$records = $state->rowCount();
					if($records > 0){
						$print = $row["copies"] - $records;
						echo "<td>".$print."</td>";
					} else {
						echo "<td>{$copies}</td>";
					}
		echo "		<td>
						<button type='submit' class='btn btn-primary updateBook' value='{$ISBN}' name='updateId'>Edit</button>
						<a class='btn btn-danger text-white delete-object' delete-id='{$ISBN}' hidden>Delete</a>
                        <button type='submit' class='btn btn-primary borrowBook' value='{$ISBN}' name='borrowId'>Borrow</button>
                        <button type='submit' class='btn btn-primary returnBook' value='{$ISBN}' name='returnId'>Return</button>
						<button type='submit' class='btn btn-danger archiveBook' value='{$ISBN}' name='archiveId'>Archive</button>
					</td>
				</tr>";
			}
			
		echo "</tbody>
			</table>
			</form>
			</div>";

		$page_url = "index.php?searchTextBox={$book->searchValue}&";
		include_once 'paging.php';
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
            location.reload();
        }).fail(function() {
            alert('Unable to delete.');
        });
    }
});
</script>

<!--- Update Modal -->
<div class="modal" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
			  
			  <form method="POST"  id="updateForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
					<?php 
						$book->viewOneBook();
					?>
			      <tr>
			        <th>ISBN</th>
			        <td><input type="text" class="form-control" name="ISBN" value="<?php echo $book->ISBN; ?>"></td>
			      </tr>
			      <tr>
			        <th>Book Title</th>
			        <td><input type="text" class="form-control" name="title" value="<?php echo $book->title; ?>"></td>
			      </tr>
			      <tr>
			        <th>Edition</th>
			        <td><input type="text" class="form-control" name="edition" value="<?php echo $book->edition; ?>"></td>
			      </tr>
			      <tr>
			        <th>Author</th>
			        <td>
			          <select class="form-control" name="author">
			            <option selected></option>
			              <?php
			              include_once "classes/author.php";
			              $author = new Author($db);
			              $stmt = $author->viewAllAuthor();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                if ($book->author == $row['authorId']){
			                  echo "<option value={$authorId} selected>{$lastname}, {$firstname} {$middlename}</option>";
			            	} else {
			            		 echo "<option value={$authorId}>{$lastname}, {$firstname} {$middlename}</option>";
			            	}
			              }
			              ?>
			          </select>
			        </td>
			      </tr>
			      <tr>
			        <th>Category</th>
			        <td>
			          <select class="form-control" name="category">
			            <option selected></option>
			              <?php
			              include_once "classes/category.php";
			              $category = new Category($db);
			              $stmt = $category->viewAllCategory();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                if ($book->category == $row['categoryCode']){
			                  echo "<option value={$categoryCode} selected>{$category}</option>";
			            	} else {
			            		 echo "<option value={$categoryCode}>{$category}</option>";
			            	}
			              }
			              ?>
			          </select>
			        </td>
			      </tr>
			       <tr>
			        <th>Copies</th>
			        <td><input type="text" class="form-control" name="copies" value="<?php echo $book->copies; ?>"></td>
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
	$('#updateForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'bookEdit.php',
		method:"POST",
		data:$('#updateForm').serialize(),
		
		success: function(data){
			$('#updateModal').modal('hide');
			$("#bookTable").load(" #bookTable");
            alert("Record Updated!");
			}
		});
		return false;
	});
</script>

<!--- Borrow Modal -->
<div class="modal" id="borrowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Borrow Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

			  <form method="POST"  id="borrowForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
			      <tr>
			        <th>Borrowers ID</th>
			        <td><input type="text" class="form-control" name="borrowersid"></td>
			      </tr>
			      <tr>
			        <th>Book ID</th>
			        <td><input type="text" class="form-control" name="bookid" value="<?php echo $book->ISBN; ?>"></td>
			      </tr>
			      <tr>
			        <th>Date Borrowed</th>
			        <td><input type="text" class="form-control" name="borrowDate" value="<?php echo date("Y-m-d"); ?>"></td>
			      </tr>
			      <tr>
			        <th>Return Date</th>
			        <td><input type="text" class="form-control" name="returnDate" value="<?php echo date("Y-m-d", strtotime("+1 week")); ?>"></td>
			      </tr>
			      <tr>
			        <th>Assisting Personnel</th>
			        <td>
			        	<?php 
			        	include_once "classes/borrow.php";
			            $borrow = new Borrower($db);
			            $borrow->borrowersId = $borrower;
			            $stmt = $borrow->viewOneBorrower();
			            echo '<input type="text" class="form-control" value="'.$borrow->firstname.' '.$borrow->lastname.'">
			            	<input type="text" class="form-control" name="personnel" value="'.$borrow->borrowersId.'" hidden>'
			            ;
			        	?>
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
	$('#borrowForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'bookBorrow.php',
		method:"POST",
		data:$('#borrowForm').serialize(),
		
		success: function(data){
			$('#borrowModal').modal('hide');
			$('#bookTable').load(' #bookTable');
            alert("Book Borrowed!");
        }
		});
		return false;
	});
</script>

<?php 
	//return modal show
	if(isset($_GET['returnId'])){
			$trans->ISBN = $_GET['returnId'];
			$book->ISBN = $_GET['returnId'];
			$state = $book->viewOneBook();
			$state1 = $trans->checkTrans();
			$records = $state1->rowCount();

			if($records != 0){
				echo " <script>
					$(document).ready(function(){
						$('#returnModal').modal('show');
					});
				</script>";
			} else {
				echo " <script>
						alert('No Books Borrowed');
				</script>";
			} 
		}
?>

<!-- return book modal -->
<div class="modal" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><?php echo "Return Book - ".$book->title; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
			  
			  <form method="POST"  id="returnForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
			      <tr>
			        <th>Borrowers ID</th>
			        <td>
			        	<select class="form-control" name="borrowersid">
			            <option selected></option>
			              <?php
			              $stmt = $trans->checkTrans();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                echo "<option value={$borrowersid}>{$borrowersid}</option>";
			              }
			              ?>
			          </select>
			      </td>
			      </tr>
			      <tr>
			        <th>Book ID</th>
			        <td><input type="text" class="form-control" name="bookid" value="<?php echo $book->ISBN; ?>"></td>
			      </tr>
			      <tr>
			        <th>Date Borrowed</th>
			        <td>
			        	<select class="form-control" name="borrowDate">
			            <option selected></option>
			              <?php
			              $stmt = $trans->checkTrans();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                echo "<option value={$dateborrowed}>{$dateborrowed}</option>";
			              }
			              ?>
			          </select>
			        </td>
			      </tr>
			       <tr>
			        <th>Return Date</th>
			        <td><input type="text" class="form-control" name="returnDate" value="<?php echo date("Y-m-d"); ?>">
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

<!-- return here -->
<script>
	$('#returnForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'bookReturn.php',
		method:"POST",
		data:$('#returnForm').serialize(),
		
		success: function(data){
			$('#returnModal').modal('hide');
			$('#bookTable').load(' #bookTable');
            alert("Book Returned!");
        }
		});
		return false;
	});
</script>

<!--- Add Book Modal -->
<div class="modal" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
			  
			  <form method="POST"  id="addBookForm">
              <div class="modal-body">
				<table class='table table-hover table-bordered'>
			      <tr>
			        <th>ISBN</th>
			        <td><input type="text" class="form-control" name="ISBN"></td>
			      </tr>
			      <tr>
			        <th>Book Title</th>
			        <td><input type="text" class="form-control" name="title"></td>
			      </tr>
			      <tr>
			        <th>Edition</th>
			        <td><input type="text" class="form-control" name="edition"></td>
			      </tr>
			      <tr>
			        <th>Author</th>
			        <td>
			          <select class="form-control" name="author">
			            <option selected></option>
			              <?php
			              include_once "classes/author.php";
			              $author = new Author($db);
			              $stmt = $author->viewAllAuthor();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                echo "<option value={$authorId}>{$lastname}, {$firstname} {$middlename}</option>";
			              }
			              ?>
			          </select>
			        </td>
			      </tr>
			      <tr>
			        <th>Category</th>
			        <td>
			          <select class="form-control" name="category">
			            <option selected></option>
			              <?php
			              include_once "classes/category.php";
			              $category = new Category($db);
			              $stmt = $category->viewAllCategory();

			              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			                extract($row);
			                echo "<option value={$categoryCode}>{$category}</option>";
			              }
			              ?>
			          </select>
			        </td>
			      </tr>
			      <tr>
			        <th>Copies</th>
			        <td><input type="text" class="form-control" name="copies"></td>
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
	$('#addBookForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'bookAdd.php',
		method:"POST",
		data:$('#addBookForm').serialize(),
		
		success: function(data){
			$('#addBookModal').modal('hide');
			$('#bookTable').load(' #bookTable');
            alert("Record Saved!");
        }
		});
		return false;
	});
</script>

<!-- Transactions Modal -->
<div id="transaction" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<?php
      		$stmt = $book->checkRow();
      		$row = $stmt->fetch(PDO::FETCH_ASSOC);
        	echo "<h4 class='modal-title'>".$row['title']."</h4>";
        ?>
        <button type="button" class="close" data-dismiss="modal">&times;</button>'
      </div>
      <div class="modal-body">
        <table class='table table-hover table-bordered'>
	        	<thead>
	        		<tr class='bg-info text-center'>
					  <th>Borrower/s</th>
					</tr>
	        	</thead>
	        	<tbody>
	        		<?php 
				        $stmt = $book->checkRow();
				        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				   		  extract($row);
						echo "<tr><td>".$row['firstname']." ".$row['lastname']."</td></tr>";}
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

<!-- Reports Modal -->
<div id="reportModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class='modal-title'>Generate Report</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>'
      </div>

      <form method="POST"  id="reportForm">
      <div class="modal-body">
        <table class='table table-hover table-bordered'>
			<tr>
        	<th>Select range from:</th>
				<td>
	        	  <?php
				  	$month = ["Month","January",'February','March','April','May','June','July','August','September','October','November','December'];
					  echo "
					  <div class='form-row'>
					    <div class='form-group col-md-4'>
					      <select class='form-control' name='fmonth'>";
					      for($i = 0; $i < count($month); $i++){
						    echo "<option value=".$i.">".$month[$i]."</option>";
					      }
					  echo "
					  </select> </div>";
					
					  echo "
					  <div class='form-group col-md-4'>
					    <select class='form-control' name='fday'>
					      <option selected>Day</option>"; 
					        for ($x = 1; $x <= 31; $x++) {
					          echo '<option value= '.$x.'>'.$x.'</option>';
					        }
					  echo "
					  </select> </div>";
					  
					  
					  echo "
					  <div class='form-group col-md-4'>
					  <select class='form-control' name='fyear'>
					  <option selected>Year</option>"; 
					  for ($x = date("Y"); $x >= date("Y")-70; $x--) {
					    echo '<option value='.$x.'>'.$x.'</option>';
					  }
					  echo "
					  </select> </div>
					  
					  </div>";
				  ?>
				</td>
			</tr>
			<tr>
				<th>To:</th>
				<td>
	        	<?php
				  	$month = ["Month","January",'February','March','April','May','June','July','August','September','October','November','December'];
					  echo "
					  <div class='form-row'>
					    <div class='form-group col-md-4'>
					      <select class='form-control' name='tmonth'>";
					      for($i = 0; $i < count($month); $i++){
						    echo "<option value=".$i.">".$month[$i]."</option>";
					      }
					  echo "
					  </select> </div>";
					
					  echo "
					  <div class='form-group col-md-4'>
					    <select class='form-control' name='tday'>
					      <option selected>Day</option>"; 
					        for ($x = 1; $x <= 31; $x++) {
					          echo '<option value= '.$x.'>'.$x.'</option>';
					        }
					  echo "
					  </select> </div>";
					  
					  
					  echo "
					  <div class='form-group col-md-4'>
					  <select class='form-control' name='tyear'>
					  <option selected>Year</option>"; 
					  for ($x = date("Y"); $x >= date("Y")-70; $x--) {
					    echo '<option value='.$x.'>'.$x.'</option>';
					  }
					  echo "
					  </select> </div>
					  
					  </div>";
				  ?>
				</td>
			</tr>
			    </tbody>
			</table>
      </div>
  	  
      <div class="modal-footer">
      	<input type="submit" class="btn btn-info" name="save" value="Save">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>

  </div>
</div>

<script>
	$('#reportForm').on('submit', function(event){
		event.preventDefault();

	$.ajax({
		url:'fpdf/pdfdemo.php',
		method:"POST",
		data:$('#reportForm').serialize(),
		
		success: function(data){
			$('#reportModal').modal('hide');
			window.open("fpdf/pdfdemo.php");
        }
		});
		return false;
	});
</script>