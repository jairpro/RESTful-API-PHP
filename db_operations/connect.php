<?php
	
	require_once 'mysql.php';

	$username = "sambhav";
	$password = "70dBcEMq0oyB0wVP";
	$hostname = "localhost"; 
	$db = "sambhav";

	class DB {
		static $selectingDatabase = false;
		static $connect = null;
	}
	DB::$connect = new MySQL($hostname, $username, $password, $db);

?>
