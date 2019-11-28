<?php
/* A class to establish a database connection 
 * $host : the remote server containing a database
 * $username and $password : credentials of a user having access to the server
 * $dbName : a database's name
 * $conn : an instance of a database connection with the previous parameters
 */
Class DatabaseConnection {
	
	private $host;
	private $username;
	private $password;
	private $dbName;
	private $conn;
	
	// The class's constructor
	public function __construct($host = 'localhost', $username = 'eisUser3110', $password = 'eI9x?yqoedslzDIv!j8dPF', $dbName = 'usr_p469648_3'){
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->dbName = $dbName;
		$this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbName);

		if ($this->conn->connect_error) {
		    die("Die Verbindung zur Datenbank ist fehlgeschlagen: " . $this->conn->connect_error);
		} else {
		    $this->conn->set_charset("utf8");
		}
	} 
	  
	// A method to get the connection attribute of a DB_Connection instance
	public function getConn() {
		if ($this->conn) {
			return $this->conn;
		}
	}
	// A method to close the database connection
	public function closeConnection() {
		if ($this->conn) {
		    $this->conn->close();
		}
	}
}
?>