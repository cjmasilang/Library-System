<?php 
	class Borrower{
	private $conn;
	private $tablename = "borrower";

	public $borrowersId;
	public $lastname;
	public $firstname;
	public $position;
	//public password;

	function __construct($db){
		$this->conn = $db;
	}

	function viewAllBorrower(){
		$query = "SELECT * FROM " . $this->tablename;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;	
	}

	function viewOneBorrower(){
		$query = "SELECT * FROM " . $this->tablename . " WHERE borrowersId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->borrowersId);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->firstname = $row['firstname'];
		$this->lastname = $row['lastname'];
		$this->position = $row['position'];
	}

	function login(){
			$query = "SELECT * FROM borrower WHERE borrowersId=? AND password=?";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(1, $this->borrowersId);
			$stmt->bindparam(2, $this->password);
			$stmt->execute();

			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			//counts how many records is extracted by the query
			$num = $stmt->rowCount();
			/*if it has more than 0 records it will start the session and assign the doctorId in a session variable*/
			if($num > 0){
				//session_start();
				$_SESSION['borrowersId'] = $row['borrowersId'];
				//multi login chamba dito mo pala iseset yung variable name ng user
				$_SESSION['position'] = $row['position'];
				//$_SESSION[''] = $row['position'];
				return true;
			} else {
				return false;
			}
		}

		function logout(){
			session_start();
			unset($_SESSION['borrowersId']);
			header("Location: index.php");
			return true;
		}
	}
?>