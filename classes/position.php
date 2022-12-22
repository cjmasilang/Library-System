<?php 
	class Position{
		private $conn;
		private $tablename = "position";

		public $positionId;
		public $position;
		public $access;

		public $borrowersId;

	function __construct($db){
		$this->conn = $db;
	}

	function viewPosition(){
		$query = "SELECT * FROM borrower INNER JOIN position ON borrower.position = position.positionId WHERE borrowersId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->borrowersId);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->position = $row['position'];
		$this->access = $row['accesslevel'];
	}

	}
?>