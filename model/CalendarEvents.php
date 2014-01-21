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


	// $sql   = sprintf('SELECT * FROM events WHERE `datetime_start` BETWEEN %s and %s',
	// 	$db->quote($start), $db->quote($end));


	// $out = array();
	// foreach($db->query($sql) as $row) {
	// 	$out[] = array(
	// 		'id' => $row->id,
	// 		'title' => $row->title,
	// 		'url' => $row->url,
	// 		'type' => $row->type,
	// 		'start' => strtotime($row->datetime_start),
	// 		'end' => strtotime($row->datetime_end)
	// 	);
	// }

	// print_r(json_encode($db));

	// echo json_encode($out);

exit;