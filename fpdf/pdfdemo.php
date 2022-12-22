<?php
  session_start();
  if(!isset($_SESSION['borrowersId'])){
    header("Location: ../login.php");
  } 


  require "fpdf.php";
  include_once "../config/database.php";
  include_once "../classes/transaction.php";

  $database = new Database();
  $db = $database->getConnection();

  if($_POST){
		$trans = new Transaction($db);
		$trans->fromDate = $_POST['fyear']."-".$_POST['fmonth']."-".$_POST['fday'];
		$trans->toDate = $_POST['tyear']."-".$_POST['tmonth']."-".$_POST['tday'];
  }

  class myPDF extends FPDF{
  	function header(){
  		$this->SetFont('Arial','B',14);
  		$this->Cell(276,10,'BORROWED BOOKS REPORT',0,0,'C');
  		$this->Ln();
  		$this->SetFont('Times','',12);
  		$this->Cell(276,10,'Books Borrowed From Jan to Dec',0,0,'C');
  		$this->Ln(20);
  	}

  	function footer(){
  		$this->SetY(-15);
  		$this->SetFont('Arial','',8);
  		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
  	}

  	function headerTable(){
  		$this->SetFont('Times','B',12);
  		$this->Cell(90,10,'Book Title',1,0,'C');
  		$this->Cell(60,10,'Borrower',1,0,'C');
  		$this->Cell(43,10,'Date Borrowed',1,0,'C');
  		$this->Cell(43,10,'Due Date',1,0,'C');
  		$this->Cell(43,10,'Date Returned',1,0,'C');
  		//$this->Cell(43,10,'Total Borrowers',1,0,'C');
  		$this->Ln();
  	}

  	function viewTable($db){
  		$this->SetFont('Times','',12);
  		$trans = new Transaction($db);
  		$stmt = $trans->selectAllTrans1();
  		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  			$this->Cell(90,10,$row['title'],1,0,'L');
	  		$this->Cell(60,10,$row['firstname'].' '.$row['lastname'],1,0,'L');
	  		$this->Cell(43,10,$row['dateborrowed'],1,0,'L');
	  		$this->Cell(43,10,$row['expectedreturndate'],1,0,'L');
	  		$this->Cell(43,10,$row['datereturned'],1,0,'L');
	  		$this->Ln();
  		}
  	}
	
	function headerTable1(){
  		$this->SetFont('Times','B',12);
  		$this->Cell(90,10,'Book Title',1,0,'C');
  		$this->Cell(60,10,'Borrower',1,0,'C');
  		$this->Cell(43,10,'Date Borrowed',1,0,'C');
  		$this->Cell(43,10,'Due Date',1,0,'C');
  		$this->Cell(43,10,'Date Returned',1,0,'C');
  		//$this->Cell(43,10,'Total Borrowers',1,0,'C');
  		$this->Ln();
  	}
	
	function viewTable1($db){
  		$this->SetFont('Times','',12);
  		$trans = new Transaction($db);
  		$stmt = $trans->selectAllTrans1();
  		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  			$this->Cell(90,10,$row['title'],1,0,'L');
	  		$this->Cell(60,10,$row['firstname'].' '.$row['lastname'],1,0,'L');
	  		$this->Cell(43,10,$row['dateborrowed'],1,0,'L');
	  		$this->Cell(43,10,$row['expectedreturndate'],1,0,'L');
	  		$this->Cell(43,10,$row['datereturned'],1,0,'L');
	  		$this->Ln();
  		}
  	}
  }


  $pdf = new myPDF();
  $pdf->AliasNbPages();
  $pdf->AddPage('L','A4',0);
  $pdf->headerTable();
  $pdf->viewTable($db);
  $pdf->Output();
  
?>