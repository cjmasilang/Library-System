<?php
	$page_title = "Update Book";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/book.php";
	include_once "classes/author.php";

/*
	if(isset($_GET['ISBN']))
		$ISBN = $_GET['ISBN'];
	else
		echo "ERROR: missing ID";

	if(isset($_POST['update'])){
    	$book->title = $_POST['title'];
    	$book->edition = $_POST['edition'];

    	if($book->updateBook()){
    		header("Location: index.php");
    	}
	}
	else if(isset($_POST['cancel'])){
		header("Location: index.php");
	}*/

  if(isset($_POST['ISBN'])){
    $book->ISBN= $_POST['ISBN'];
    $database = new Database();
    $db = $database->getConnection();

    $book = new Book($db);
    $book->ISBN = $ISBN;
    $stmt = $book->viewOneBook();

    $author = new Author($db);
    $author->ISBN = $ISBN;
    $stmt = $author->viewBookAuthor();

    echo "<script>
          $(document).ready(function(){
            $('#updateModal').modal('show');
          })
          </script>"

      //$book->restore();
    }

?>

<!--- Update Modal -->
<div class="modal" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Book</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
        
        <form method="POST"  id="updateForm">
              <div class="modal-body">
        <table class='table table-hover table-bordered'>
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
<!---
</div>
<form method="POST" action="bookEdit.php?ISBN=<?php echo $ISBN ?>">
  <h5><b>Book Basic Information (ISBN: <?php echo $ISBN ?>)</b></h5>
  <div class="form-row">
    <div class="form-group col-md-8">
      <label>Book Title</label>
      <input type="text" class="form-control" name="title" value="<?php echo $book->title ?>">
    </div>
    <div class="form-group col-md-4">
      <label>Edition</label>
      <input type="text" class="form-control" name="edition" value="<?php echo $book->edition ?>">
    </div>
  </div>

  <h5><b>Book Author</b></h5>
  <div class="form-row">
    <div class="form-group col-md-4">
      <label>Lastname</label>
      <input type="text" class="form-control" name="lastname" value="<?php echo $author->lastname ?>" disabled>
    </div>
    <div class="form-group col-md-4">
      <label>FirstName</label>
      <input type="text" class="form-control" name="firstname" value="<?php echo $author->firstname ?>" disabled>
    </div>
    <div class="form-group col-md-4">
      <label>MiddleName</label>
      <input type="text" class="form-control" name="middlename" value="<?php echo $author->middlename ?>" disabled>
    </div>
  </div>

  <div class="form-row float-right">
    <div class="col-lg-12 mb-3">	
      <button type="submit" class="btn btn-primary ml-2" name="update">Save Changes</button>
      <button type="submit" class="btn btn-primary ml-2" name="cancel">Cancel</button>
    </div>
  </div>  
</form>-->
