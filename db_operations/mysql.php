<?php

	require_once(dirname(dirname(__FILE__))."../api/api.php");

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
				ob_start();
				try {
					$this->link = mysqli_connect($this->server, $this->username, $this->password);
					if (ob_get_length()==0 && !$this->link) {
						$error = mysqli_error($this->link);
						if (!$error) {
							API::response("Não foi possível conectar no banco de dados!", 502);
						}
						API::response("A conexão do banco de dados falhou: ", trim(strip_tags($error)), 500);
					}
				}
				catch (Exception $e) {
					API::response($e->getMessage(), 500);
				}
				$displayError = trim(strip_tags(ob_get_clean()));
				if ($displayError) {
					API::response("erro ao conectar no banco de dados:/n".$displayError, 500);
				}
	   	}

	   	function selectDB($dbName=null, $die=true) {
				$this->dbName = $dbName ? $dbName : $this->dbName;
				$ok = mysqli_select_db($this->link, $this->dbName);
				$ok or $die 
					//&& die("Database does not exist.<br>Please run the setup_database.php script or create_dabatabse.php script or create the database manually.<br />"
					//	. mysqli_error($this->link));
					&& API::response(strip_tags("Database does not exist. Please run the setup_database.php script or create_dabatabse.php script or create the database manually."),500);
				return $ok;
	   	}

			function query($sql) {
				$this->queryResult = mysqli_query($this->link, $sql);
				return $this->queryResult;
			}

			function numRows() {
				if (!$this->queryResult) {
					return false;
				}
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
				if (!$this->queryResult) {
					return false;
				}
				return mysqli_fetch_array($this->queryResult);
			}
	}
?>