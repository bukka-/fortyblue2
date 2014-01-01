<?php

if(isset($_POST['value'])){
	$timetable_content = $_POST['value'];

	$timetable_id = $_POST['timetable_id'];
	$timetable_shift = $_POST['timetable_shift'];
	timetableInsert($timetable_content, $timetable_id, $timetable_shift);
}

function timetableInsert($timetable_content, $timetable_id, $timetable_shift) {
	$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	if (!$db_connection->connect_errno) {
		if($timetable_shift == "first"){
			$stmt = $db_connection->prepare("UPDATE timetables SET first_shift_timetable = ? WHERE timetable_id = ? ");
			$stmt->bind_param('si', $timetable_content, $timetable_id);
			$stmt->execute();
		}else{
			$stmt = $db_connection->prepare("UPDATE timetables SET second_shift_timetable = ? WHERE timetable_id = ? ");
			$stmt->bind_param('si', $timetable_content, $timetable_id);
			$stmt->execute();
		}

		if ($db_connection->affected_rows > 0) {
			echo "The timetable has been updated.";
			
		} else {
			echo "Sorry, the timetable has not been updated.";
		}

		$stmt->close();
	}else{
		echo  "Database connection problem.";
	}
}

exit;