<?php

	$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$start = $_POST['from'];
	$end   = $_POST['to'];

	$query = "SELECT * FROM events";
	$result = $db_connection->query($query);


	if ($result->num_rows < 1) {
	  return false;
	}     

	$rows = array();
	while($row = $result->fetch_object())
	{
	    $rows[] = $row;
	}

	echo json_encode($rows);

exit;