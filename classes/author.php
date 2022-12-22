<?php
class Author{
	private $conn;
	private $tablename = "author";

	public $authorId;
	public $lastname;
	public $firstname;
	public $middlename;

	public $ISBN;
	public $searchValue;
	public $title;


	function __construct($db){
		$this->conn = $db;
	}

	function viewAllAuthor(){
		$query = "SELECT * FROM " . $this->tablename; 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function viewBookAuthor(){
		$query = "SELECT * FROM book INNER JOIN author ON book.author = author.authorId WHERE ISBN = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->ISBN);
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->lastname = $row['lastname'];
		$this->firstname = $row['firstname'];
		$this->middlename = $row['middlename'];
		$this->title = $row['title'];
	}
	
	function addAuthor(){
		$query = "INSERT INTO " . $this->tablename . " SET lastname=?, firstname=?, middlename=?";
		
		$stmt = $this->conn->prepare($query);

		//$stmt->bindparam(1,$this->authorId);
		$stmt->bindparam(1,$this->lastname);
		$stmt->bindparam(2,$this->firstname);
		$stmt->bindparam(3,$this->middlename);
		
		if($stmt->execute())
			return true;
		else
			return false;
	
	}

	function viewOneAuthor(){
		$query = "SELECT * FROM " . $this->tablename . " WHERE authorId=?"; 
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->authorId);
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->firstname = $row['firstname'];
		$this->lastname = $row['lastname'];
		$this->middlename = $row['middlename'];
	}

	function deleteAuthor(){
		$query = "DELETE FROM " . $this->tablename . " WHERE authorId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->authorId);
		$stmt->execute();
		return $stmt;
	}

	function updateAuthor(){
		$query = "UPDATE " . $this->tablename . " SET firstname=?, lastname=?, middlename=? WHERE authorId=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->firstname);
		$stmt->bindparam(2,$this->lastname);
		$stmt->bindparam(3,$this->middlename);
		$stmt->bindparam(4,$this->authorId);
		//$stmt->execute();
		//return $stmt;

		if($stmt->execute()){
			echo "good";
			return true;
		} else {
			echo "bad";
			return false;
		}
	}

	function viewSearchResult($from_record_num, $records_per_page){
		$searchLike = "%" . $this->searchValue . "%";
		$query = "SELECT * FROM " . $this->tablename . " WHERE firstname LIKE ?
					OR lastname LIKE ? 
					ORDER BY firstname ASC
					LIMIT {$from_record_num},{$records_per_page}";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$searchLike);
		$stmt->bindparam(2,$searchLike);
		$stmt->execute();

		return $stmt;

	}

	function countSearchResult(){
		$searchLike = "%" . $this->searchValue . "%";
		$query = "SELECT * FROM " . $this->tablename . " WHERE firstname LIKE ?
					OR lastname LIKE ?
					ORDER BY firstname";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$searchLike);
		$stmt->bindparam(2,$searchLike);
		$stmt->execute();

		$num = $stmt->rowCount();
		return $num;

	}

	function checkBooks(){
		$query = "SELECT * FROM book INNER JOIN author ON book.author = author.authorId WHERE author=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->authorId);
		$stmt->execute();
		return $stmt;
	}
}
?>