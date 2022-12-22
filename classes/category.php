<?php 
	class Category{
		private $conn;
		private $tablename = "category";

		public $categoryCode;
		public $category;
		public $days;

	function __construct($db){
		$this->conn = $db;
	}

	function viewAllCategory(){
		$query = "SELECT * FROM " . $this->tablename;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function viewOneCategory(){
		$query = "SELECT * FROM " . $this->tablename . " WHERE categoryCode=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1, $this->categoryCode);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->category = $row['category'];
		$this->days = $row['dayslimit'];
	}

	function AddCategory(){
		$query = "INSERT INTO " . $this->tablename . " SET categoryCode=?, category=?, dayslimit=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1, $this->categoryCode);
		$stmt->bindparam(2, $this->category);
		$stmt->bindparam(3, $this->days);
		$stmt->execute();
		return $stmt;
	}

	function updateCategory(){
		$query = "UPDATE " . $this->tablename . " SET category=?, dayslimit=? WHERE categoryCode=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1, $this->category);
		$stmt->bindparam(2, $this->days);
		$stmt->bindparam(3, $this->categoryCode);
		$stmt->execute();
		return $stmt;
	}

	function deleteCategory(){
		$query = "DELETE FROM " . $this->tablename . " WHERE categoryCode=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1, $this->categoryCode);
		$stmt->execute();
		return $stmt;
	}

	}
?>