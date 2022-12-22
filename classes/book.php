<?php
class Book{
	private $conn;
	private $tablename = "book";

	public $ISBN;
	public $title;
	public $edition;
	public $author;
	public $category;
	public $copies;

	public $searchValue;


	function __construct($db){
		$this->conn = $db;
	}

	function viewAllBook($from_record_num, $records_per_page){
		$query = "SELECT * FROM " . $this->tablename . " WHERE archive=1 ORDER BY title ASC LIMIT {$from_record_num},{$records_per_page}"; 
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function viewOneBook(){
		$query = "SELECT * FROM " . $this->tablename . " WHERE ISBN = ?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->ISBN);
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->title = $row['title'];
		$this->edition = $row['edition'];
		$this->author = $row['author'];
		$this->category = $row['category'];
		$this->copies = $row['copies'];
	}

	function addBook(){
		$query = "INSERT INTO book SET ISBN=?, title=?, edition=?, author=?, category=?, copies=?";
		
		$stmt = $this->conn->prepare($query);

		$stmt->bindparam(1,$this->ISBN);
		$stmt->bindparam(2,$this->title);
		$stmt->bindparam(3,$this->edition);
		$stmt->bindparam(4,$this->author);
		$stmt->bindparam(5,$this->category);
		$stmt->bindparam(6,$this->copies);
		
		if($stmt->execute())
			return true;
		else
			return false;
	
	}

	function updateBook(){
		$query = "UPDATE " . $this->tablename . " SET title=?, edition=?, category=?, copies=? WHERE ISBN=?"; 
		$stmt = $this->conn->prepare($query);
		
		$stmt->bindparam(1,$this->title);
		$stmt->bindparam(2,$this->edition);
		$stmt->bindparam(3,$this->category);
		$stmt->bindparam(4,$this->copies);
		$stmt->bindparam(5,$this->ISBN);

		if($stmt->execute()){
			echo "good";
			return true;
		}else{
			echo "bad";
			return false; 	
		}
	}

	function deleteBook(){
		$query = "DELETE FROM book WHERE ISBN=?";

    	$stmt = $this->conn->prepare($query);
   		$stmt->bindParam(1, $this->ISBN);
 
    	$stmt->execute();
	}

	function viewSearchResult($from_record_num, $records_per_page){
		$searchLike = "%" . $this->searchValue . "%";
		$query = "SELECT * FROM " . $this->tablename . " WHERE title LIKE ? AND archive=0
					ORDER BY title ASC
					LIMIT {$from_record_num},{$records_per_page}";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$searchLike);
		$stmt->execute();

		return $stmt;

	}

	function countSearchResult(){
		$searchLike = "%" . $this->searchValue . "%";
		$query = "SELECT * FROM " . $this->tablename . " WHERE title LIKE ? AND archive=0
					ORDER BY title";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$searchLike);
		$stmt->execute();

		$num = $stmt->rowCount();
		return $num;

	}

	function archive(){
		$query = "UPDATE " . $this->tablename . " SET archive=1 WHERE ISBN=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->ISBN);
		$stmt->execute();
		return $stmt;
	}

	function restore(){
		$query = "UPDATE " . $this->tablename . " SET archive=0 WHERE ISBN=?";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->ISBN);
		$stmt->execute();
		return $stmt;
	}

	function checkRow(){
		$query = "SELECT * FROM " . $this->tablename . " INNER JOIN transaction ON book.ISBN = transaction.bookId INNER JOIN borrower ON transaction.borrowersId = borrower.borrowersId WHERE ISBN=? AND transaction.datereturned IS NULL";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$this->ISBN);
		$stmt->execute();
		return $stmt;
		//$num = $stmt->rowCount();
		//return $num;
	}
}
?>
