<?php

	if (empty($selectingDatabase)) {
    	require_once "db_connection.php";
    }

	DB::$connect->query("SHOW TABLES LIKE 'item'");
	$tableExists = DB::$connect->numRows();

	if($tableExists > 0) {
		echo "'item' table already exists!<br/>";
	
	} else {
		DB::$connect->query(
			'CREATE TABLE item ( id int(11) NOT NULL auto_increment, 
				name varchar(20) NOT NULL,
				price DECIMAL(10,2) NOT NULL,
				PRIMARY KEY (id)
			)'
		);

		echo "Item table created!<br/> Inserting two sample items";

		DB::$connect->query(
			"INSERT INTO item (name, price) VALUES ( 'Item A', '5.99')"
		);

		DB::$connect->query(
			"INSERT INTO item (name, price) VALUES ( 'Item B', '12.99')"
		);

		echo "<br/>Items added successfully!";	
	}

	

?>