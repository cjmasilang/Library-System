<?php 
	class Archive{
	private $conn;
	private $tablename = "book";

	public $ISBN;
	public $title;
	public $edition;
	public $author;

	public $searchValue;

	function __construct($db){
		$this->conn = $db;
	}

	function viewSearchResult($from_record_num, $records_per_page){
		$searchLike = "%" . $this->searchValue . "%";
		$query = "SELECT * FROM " . $this->tablename . " WHERE title LIKE ? AND archive=1
					ORDER BY title ASC
					LIMIT {$from_record_num},{$records_per_page}";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$searchLike);
		$stmt->execute();
		return $stmt;
	}

	function countSearchResult(){
		$searchLike = "%" . $this->searchValue . "%";
		$query = "SELECT * FROM " . $this->tablename . " WHERE title LIKE ? AND archive=1
					ORDER BY title";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(1,$searchLike);
		$stmt->execute();

		$num = $stmt->rowCount();
		return $num;

	}

	}
?>