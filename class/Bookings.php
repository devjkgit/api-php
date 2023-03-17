<?php

class Bookings{

	private $table = "bookings";
	private $conn;

	public function __construct($db){
		$this->conn = $db;
	}

	function check(){
		$checkquery = $this->conn->prepare('select * from bookings where prop_id = :prop_id AND (:start_date BETWEEN start_date AND end_date) AND (:end_date BETWEEN start_date AND end_date)');
		$checkquery->bindValue(':prop_id',$_POST['prop_id']);
		$checkquery->bindValue(':start_date',$_POST['start_date']);
		$checkquery->bindValue(':end_date',$_POST['end_date']);
		$checkquery->execute();
		$result = $checkquery->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
}

?>