<?php
	
	require_once 'mysql.php';

	$thisDir = dirname(__FILE__);
	require_once( dirname($thisDir) . '../.env.php');

	class DB {
		static $selectingDatabase = false;
		static $connect = null;
	}
	DB::$connect = new MySQL($hostname, $username, $password, $db);

?>
