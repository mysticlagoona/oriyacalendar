<?php


// Two options for connecting to the database:
class Holiday {
	private $_connection;
	private static $_instance; //The single instance
	private $_host = "serverip";
	private $_username = "usr";
	private $_password = "pwd";
	private $_database = "db";

	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}

	public function fetchHoliday ($month, $year) {
		$days = "";

		// Select holidays
		$sql = sprintf("SELECT * FROM holiday where year=%d AND month='%s' AND holiday=1",
            $year,  $month);

		$result = $this->_connection->query($sql);

		if ($result->num_rows > 0) {
    		// output data of each row
    		while($row = $result->fetch_assoc()) {
        		$days = $days.$row["date"]." ".$row["holidayname"]."|";
    		}
		} else {
			//$days = 'no holiday *** sql query ='.$sql;
			//$days = "";
		}

		return $days;
	}

	// Get mysqli connection
	public function closeConnection() {
		mysqli_close($this->_connection);
	}

    /********************* PRIVATE **********************/
    // Constructor
	private function __construct() {
		$this->_connection = new mysqli($this->_host, $this->_username,
			$this->_password, $this->_database);

		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
				 E_USER_ERROR);
		}
	}

	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }


}

?>
