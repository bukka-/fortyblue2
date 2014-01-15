<?php

if(isset($_POST['grade_data'])){
	$grade_data = $_POST['grade_data'];
	$user_id = $_POST['user_id'];
	$subject_id = $_POST['subject_id'];

	userGradeInsert($grade_data, $user_id, $subject_id);

}else if(isset($_POST['grade_edit_data'])){

	echo 'testy';
	$grade_data = $_POST['grade_edit_data'];
	$user_id = $_POST['user_id'];
	$subject_id = $_POST['subject_id'];
	$grade_id = $grade_data['grade_id'];

	userGradeEdit($grade_data, $user_id, $subject_id, $grade_id);
}

function userGradeInsert($grade_data, $user_id, $subject_id) {
	$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	if (!$db_connection->connect_errno) {

		$grade_date = $db_connection->real_escape_string($grade_data['grade_date']);
		$date = date_create($grade_date)->format('Y-m-d');

		$grade_task = $db_connection->real_escape_string($grade_data['grade_task']);
		$grade_value = $db_connection->real_escape_string($grade_data['grade_value']);

		$query_new_grade_insert = $db_connection->query("INSERT INTO grades (user_id, subject_id, `date`, task, grade) 
			VALUES('".$user_id."', '".$subject_id."', '".$date."', '".$grade_task."', '".$grade_value."');");

		if ($query_new_grade_insert) {
			echo "<div class='alert alert-success'>The grade has been added.</div>";
			
		} else {
			echo "<div class='alert alert-danger'>Sorry, the grade has not been added.</div>";
		}

	}else{
		echo  "<div class='alert alert-danger'>Database connection problem.</div>";
	}
}

function userGradeEdit($grade_data, $user_id, $subject_id, $grade_id) {
	$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	if (!$db_connection->connect_errno) {

		$grade_date = $db_connection->real_escape_string($grade_data['grade_date']);
		$date = date_create($grade_date)->format('Y-m-d');

		$grade_task = $db_connection->real_escape_string($grade_data['grade_task']);
		$grade_value = $db_connection->real_escape_string($grade_data['grade_value']);

		$stmt = $db_connection->prepare("UPDATE grades SET `date` = ?, task = ?, grade = ? WHERE id = ? ");
		print_r($db_connection->error);
		$stmt->bind_param('sssi', $date, $grade_task, $grade_value, $grade_id);
		$stmt->execute();


		if ($db_connection->affected_rows > 0) {
			echo "<div class='alert alert-success'>The grade has been updated.</div>";
			
		} else {
			echo "<div class='alert alert-danger'>Sorry, the grade has not been updated.</div>";
		}

		$stmt->close();
	}else{
		echo  "<div class='alert alert-danger'>Database connection problem.</div>";
	}
}

exit;