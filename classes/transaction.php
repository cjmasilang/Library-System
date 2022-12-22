<?php 
	class Transaction{
		private $conn;
		public $ISBN;
		public $borrowersid;
		public $fromDate;
		public $toDate;
		public $dateborrowed;
		public $expectedreturndate;
		public $assistingpersonnel;

		function __construct($db){
			$this->conn = $db;
		}

		function borrowBook(){
			$query = "INSERT INTO transaction SET borrowersid=?, bookid=?, dateborrowed=?, expectedreturndate=?, assistingpersonnel=?";
			
			$stmt = $this->conn->prepare($query);

			$stmt->bindparam(1,$this->borrowersid);
			$stmt->bindparam(2,$this->ISBN);
			$stmt->bindparam(3,$this->dateborrowed);
			$stmt->bindparam(4,$this->expectedreturndate);
			$stmt->bindparam(5,$this->assistingpersonnel);
			
			if($stmt->execute())
				return true;
			else
				return false;
	
		}

		function returnBook(){
			$query = "UPDATE transaction SET datereturned=now() WHERE dateborrowed=? AND borrowersid=? AND bookid=?"; 
			
			$stmt = $this->conn->prepare($query);

			//$stmt->bindparam(1,$this->datereturned);
			$stmt->bindparam(1,$this->dateborrowed);
			$stmt->bindparam(2,$this->borrowersid);
			$stmt->bindparam(3,$this->ISBN);
			
			if($stmt->execute())
				return true;
			else
				return false;
	
		}

		function checkTrans(){
			$query = "SELECT * FROM transaction WHERE bookid=? AND datereturned IS NULL";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(1, $this->ISBN);
			$stmt->execute();
			return $stmt;

			//$num = $stmt->rowCount();
			//return $num;
		}

		function selectAllTrans(){
			$from = "%".$this->fromDate."%";
			$to = "%".$this->toDate."%";
			$query = "SELECT * FROM book INNER JOIN transaction ON book.ISBN = transaction.bookId INNER JOIN borrower ON transaction.borrowersId = borrower.borrowersId 
			WHERE dateborrowed BETWEEN ".$this->from." AND ".$this->to;
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;

			//$num = $stmt->rowCount();
			//return $num;
		}
		
		function selectAllTrans1(){
			$query = "SELECT * FROM book INNER JOIN transaction ON book.ISBN = transaction.bookId INNER JOIN borrower ON transaction.borrowersId = borrower.borrowersId";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			return $stmt;

			//$num = $stmt->rowCount();
			//return $num;
		}

	}
?>