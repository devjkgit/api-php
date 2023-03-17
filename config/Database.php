<?php 
class Database{
	private $user = "root";
	private $password = "";

	public function getConnection(){
		try {
			$conn = new PDO("mysql:host=localhost;dbname=propertyportal", $this->user, $this->password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			die('Error connecting to database : '.$e->getMessage());
		}
		return $conn;
	}
}

?>