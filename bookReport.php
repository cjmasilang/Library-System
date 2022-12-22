<?php 
	$page_title = "Reports";
	include_once "header.php";
	include_once "classes/transaction.php";
	include_once "config/database.php";

	$database = new Database();
	$db = $database -> getConnection();
	
	$trans = new Transaction($db);

	if($_POST){
		$trans->fromDate = $_POST['fyear']."-".$_POST['fmonth']."-".$_POST['fday'];
		$trans->toDate = $_POST['tyear']."-".$_POST['tmonth']."-".$_POST['tday'];
		echo "<script>
				window.open('fpdf/pdfdemo.php');
		      </script>"
	} else {
		"<script>
			alert('No Date');
		</script>"
	}
		
?>
