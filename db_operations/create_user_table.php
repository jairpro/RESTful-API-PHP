<?php
	
	if (empty($selectingDatabase)) {
    	require_once "db_connection.php";
	}

	DB::$connect->query("SHOW TABLES LIKE 'user'");
	$tableExists = DB::$connect->numRows();

	if($tableExists > 0) {
		echo "'user' table already exists!<br/>";
	
	} else {

		DB::$connect->query(
			'CREATE TABLE user ( id int(11) NOT NULL auto_increment, 
				username varchar(20) NOT NULL,
				password varchar(40) NOT NULL,
				PRIMARY KEY (id),
				UNIQUE KEY username (username)
			)'
		);

		echo "<br/>User table created!<br/>Inserting admin user now!";

		$username = "admin";
		$password = "password";

		$password = sha1($password);

		DB::$connect->query(
			"INSERT INTO user (username, password) VALUES ( '" . $username . "', '" . $password . "')"
		);

		echo "<br/>Admin user created successfully!";
	}

?>