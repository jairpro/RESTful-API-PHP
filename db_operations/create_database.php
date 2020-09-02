<?php
	
	if (empty($connect)) {
    	require_once "connect.php";
	}
	//echo json_encode($connect->getLink())."<br>";

	$db = DB::$connect->getDbName();

	if (!DB::$connect->selectDB(null, false)) {
	
	    echo("creating database '$db'!<br/>");
	    DB::$connect->query('CREATE DATABASE '. $db);
	
	} else {
		echo("'$db' database already exists!<br/>");
	}

	DB::$selectingDatabase = true;
	DB::$connect->selectDB();