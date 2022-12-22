<?php
	$page_title = "Update Borrower";
	include_once "header.php";
	include_once "config/database.php";
	include_once "classes/borrow.php";
	include_once "classes/position.php";

	if(isset($_GET['borrowersId']))
		$borrowersId = $_GET['borrowersId'];
	else
		echo "ERROR: missing ID";

	$database = new Database();
	$db = $database->getConnection();

	$borrower = new Borrower($db);
	$borrower->borrowersId = $borrowersId;
	$stmt = $borrower->viewOneBorrower();

	$pos = new Position($db);
	$pos->borrowersId = $borrowersId;
	$stmt = $pos->viewPosition();

	if(isset($_POST['update'])){
    	$borrower->firstname = $_POST['firstname'];
    	$borrower->lastname = $_POST['lastname'];
      $borrower->position = $_POST['position'];

    	if($borrower->updateBorrower()){
    		header("Location: borrowView.php");
    	}
	}
	else if(isset($_POST['cancel'])){
		header("Location: borrowView.php");
	}

?>

</div>
<form method="POST" action="borrowEdit.php?borrowersId=<?php echo $borrowersId ?>">
  <h5><b>Borrower Basic Information (Borrower ID: <?php echo $borrowersId ?>)</b></h5>
  <div class="form-row">
    <div class="form-group col-md-4">
      <label>Lastname</label>
      <input type="text" class="form-control" name="lastname" value="<?php echo $borrower->lastname ?>">
    </div>
    <div class="form-group col-md-4">
      <label>FirstName</label>
      <input type="text" class="form-control" name="firstname" value="<?php echo $borrower->firstname ?>">
    </div>
    <div class="form-group col-md-4">
      <label>Position</label>
      <select class="form-control" name="position">
        <option selected></option>
        <?php 
          for($i=1;$i<6;$i++){
            if($i == $borrower->position){
              echo "<option selected>". $i . "</option>";
            } else {
              echo "<option>" . $i . "</option>";
            }
          }
        ?>
      </select>
    </div>
  </div>

  <h5><b>Position Details</b></h5>
  <div class="form-row">
    <div class="form-group col-md-8">
      <label>Position</label>
      <input type="text" class="form-control" name="title" value="<?php echo $pos->position ?>" disabled>
    </div>
    <div class="form-group col-md-4">
      <label>Access Level</label>
      <input type="text" class="form-control" name="edition" value="<?php echo $pos->access ?>" disabled>
    </div>
  </div>

 

  <div class="form-row float-right">
    <div class="col-lg-12 mb-3">	
      <button type="submit" class="btn btn-primary ml-2" name="update">Save Changes</button>
      <button type="submit" class="btn btn-primary ml-2" name="cancel">Cancel</button>
    </div>
  </div>  
</form>
