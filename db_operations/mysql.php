<?php

	class MySQL {

	   	private $server;
	   	private $username;
	   	private $password;
	   	private $dbName;
			private $queryResult;
			private $link; 
		
	   	function __construct($server, $username, $password, $db) {
	      $this->server = $server;
	      $this->username = $username;
	      $this->password = $password;
	      $this->dbName = $db;
	      //Connect to the database
	      $this->connect();
			}
			
			function getLink() {
				return $this->link;
			}

			function getInsertId() {
				return $this->link->insert_id;
			}

			function getAffectedRows() {
				return $this->link->affected_rows;
			}

			function getDbName() {
				return $this->dbName;
			}

			function getQueryResult() {
				return $this->queryResult;
			}

	   	/* 
	      Connects the system to the given database.
	   	*/
	   	function connect() {
				$this->link = mysqli_connect($this->server, $this->username, $this->password);
				$this->link or die(mysqli_error($this->link));
	   	}

	   	function selectDB($dbName=null, $die=true) {
				$this->dbName = $dbName ? $dbName : $this->dbName;
				$ok = mysqli_select_db($this->link, $this->dbName);
				$ok or $die 
					//&& die("Database does not exist.<br>Please run the setup_database.php script or create_dabatabse.php script or create the database manually.<br />"
					//	. mysqli_error($this->link));
					&& API::response("Database does not exist.<br>Please run the setup_database.php script or create_dabatabse.php script or create the database manually.<br />",500);
				return $ok;
	   	}

			function query($sql) {
				$this->queryResult = mysqli_query($this->link, $sql);
				return $this->queryResult;
			}

			function numRows() {
				return mysqli_num_rows($this->queryResult);
			}

			function result($index) {
				$row = mysqli_fetch_row($this->queryResult);
				if (!is_array($row) || !isset($row[$index])) {
					return null;
				}
				return $row[$index];
			}

			function fetchArray() {
				return mysqli_fetch_array($this->queryResult);
			}
	}
?>