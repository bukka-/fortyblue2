<?php

class Timetable
{

	public function __construct(){

		if (isset($_POST['timetable_settings_submit'])){
			$this->createTimetable();
		}
	}

	public function getTimetable($timetable_id){
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		// $query_check_timetable = $db_connection->query();

		$query = "SELECT * FROM timetables WHERE timetable_id = '" .$timetable_id. "';";
		$result = $this->db_connection->query($query);

		if ($result->num_rows < 1) {
				  return false;
		}

		$this->db_connection->close();
		return $result->fetch_object();
	}

	public function getTimetables() {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_errno) {
			$getTimetables = "";

				if (mysqli_connect_errno()) {
				  return '<div class="alert alert-danger">Database connection problem.</div>';
				  exit;
				}
				$query = "SELECT timetable_id, class, school, creator FROM timetables";
				$result = $this->db_connection->query($query);


				if ($result->num_rows < 1) {
				  return false;
				}     

				$rows = array();
				while($row = $result->fetch_object())
				{
				    $rows[] = $row;
				}

				return $rows;

		}else{
				return '<div class="alert alert-danger">Database connection problem.</div>';
		}

	}


	private function createTimetable(){

			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			
			if (!$this->db_connection->connect_errno) {

				$this->class = $this->db_connection->real_escape_string($_POST['class']);
				$this->school = $this->db_connection->real_escape_string($_POST['school']);
				$this->time_start = $this->db_connection->real_escape_string($_POST['time_start']);
				$this->lesson_duration = $this->db_connection->real_escape_string($_POST['lesson_duration']);
				$this->minibreak_duration = $this->db_connection->real_escape_string($_POST['minibreak_duration']);
				$this->break_duration = $this->db_connection->real_escape_string($_POST['break_duration']);
				$this->break_amount = $this->db_connection->real_escape_string($_POST['break_amount']);
				$this->break_lessons = $this->db_connection->real_escape_string($_POST['break_lessons']);
				if (isset($_POST['shift_check'])){
					$this->shift_check = 1;
					$this->shift_start = $this->db_connection->real_escape_string($_POST['shift_start']);
				}else{
					$this->shift_check = 0;
					$this->shift_start = "0";
				}
				$this->creator = $_SESSION['user_name'];

				$this->default_timetable = '{"1.monday":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"","10":"","11":"","12":"","13":""},"2.tuesday":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"","10":"","11":"","12":"","13":""},"3.wednesday":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"","10":"","11":"","12":"","13":""},"4.thursday":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"","10":"","11":"","12":"","13":""},"5.friday":{"1":"","2":"","3":"","4":"","5":"","6":"","7":"","8":"","9":"","10":"","11":"","12":"","13":""}}';
				

$query_new_timetable_insert = $this->db_connection->query("INSERT INTO timetables (class, school, time_start, lesson_duration, minibreak_duration, break_duration, break_amount, break_lessons, shift_check, shift_start, creator, time_created, first_shift_timetable, second_shift_timetable) VALUES('".$this->class."', '".$this->school."', '".$this->time_start."', '".$this->lesson_duration."', '".$this->minibreak_duration."', '".$this->break_duration."', '".$this->break_amount."', '".$this->break_lessons."', '".$this->shift_check."', '".$this->shift_start."', '".$this->creator."', now(), '".$this->default_timetable."', '".$this->default_timetable."')");

				if ($query_new_timetable_insert) {
					echo "<span class='alert alert-success'>The timetable has been created.</span>";
				} else {
					echo "<span class='alert alert-danger'>Sorry, the data has not been added.</span>";
				}

			}else{
				echo  "<span class='alert alert-danger'>Database connection problem.</span>";
			}
	}

}