<?php

class Event
{

	private $db_connection = null;

	private $user_name = "";

	private $user_id = "";

	public $subjects = Array();

	public function __construct()
	{

		if (isset($_POST["event_new"])){
			$this->newEvent();
		}elseif (isset($_POST["event_edit"])){
			$this->eventEdit();
		}
	}


	public function getEvents() {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_errno) {
			$getSubjects = "";

				if (mysqli_connect_errno()) {
				  return '<div class="alert alert-danger">Database connection problem.</div>';
				  exit;
				}
				$query = "SELECT * FROM events";
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

	private function newEvent() {
		$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$db_connection->connect_errno) {

			$event_id = $db_connection->real_escape_string($_POST['event_id']);
			$event_date_start = $db_connection->real_escape_string($_POST['event_date_start']);
			$event_date_start = date("Y-m-d", strtotime($event_date_start));
			$event_date_end = $db_connection->real_escape_string($_POST['event_date_end']);
			$event_date_end = date("Y-m-d", strtotime($event_date_end));
			$event_time_start = $db_connection->real_escape_string($_POST['event_time_start']);
			$event_time_duration = $db_connection->real_escape_string($_POST['event_time_duration']);
			$event_title = $db_connection->real_escape_string($_POST['event_title']);
			$event_description = $db_connection->real_escape_string($_POST['event_description']);
			$event_type = $db_connection->real_escape_string($_POST['event_type']);
			$event_url = $db_connection->real_escape_string($_POST['event_url']);

			if(isset($_POST['subject_id'])) {
				$subject_id = (int)$_POST['subject_id'];
			}

			if(isset($_POST['event_calendar'])){
				$event_calendar = true;
			}else{
				$event_calendar = false;
			}

			if(isset($_POST['event_public'])){
				$event_public = true;
			}else{
				$event_public = false;
			}


			$query_new_event_insert = $db_connection->query("INSERT INTO events (id, datetime_start, datetime_end, time_start, time_duration, title, description, type, subject_id, url, public, calendar) VALUES('".$event_id."', '".$event_date_start."', '".$event_date_end."', '".$event_time_start."', '".$event_time_duration."', '".$event_title."', '".$event_description."', '".$event_type."', '".$subject_id."', '".$event_url."', '".$event_public."', '".$event_calendar."');");

			if ($query_new_event_insert) {
				echo "<div class='alert alert-success'>The event has been added.</div>";
				
			} else {
				echo "<div class='alert alert-danger'>Sorry, the event has not been added.</div>";
			}

		}else{
			echo  "<div class='alert alert-danger'>Database connection problem.</div>";
		}
	}	

	private function eventEdit() {
		$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$db_connection->connect_errno) {

			$event_id = $db_connection->real_escape_string($_POST['event_id']);
			$event_date_start = $db_connection->real_escape_string($_POST['event_date_start']);
			$event_date_start = date("Y-m-d", strtotime($event_date_start));
			$event_date_end = $db_connection->real_escape_string($_POST['event_date_end']);
			$event_date_end = date("Y-m-d", strtotime($event_date_end));
			$event_time_start = $db_connection->real_escape_string($_POST['event_time_start']);
			$event_time_duration = $db_connection->real_escape_string($_POST['event_time_duration']);
			$event_title = $db_connection->real_escape_string($_POST['event_title']);
			$event_description = $db_connection->real_escape_string($_POST['event_description']);
			$event_type = $db_connection->real_escape_string($_POST['event_type']);
			$event_url = $db_connection->real_escape_string($_POST['event_url']);

			if(isset($_POST['subject_id'])) {
				$subject_id = (int)$_POST['subject_id'];
			}

			if(isset($_POST['event_calendar'])){
				$event_calendar = true;
			}else{
				$event_calendar = false;
			}

			if(isset($_POST['event_public'])){
				$event_public = true;
			}else{
				$event_public = false;
			}

			$stmt = $db_connection->prepare("UPDATE events SET datetime_start = ?, datetime_end = ?, time_start = ?, time_duration = ?, title = ?, description = ?, type = ?, subject_id = ?, url = ?, public = ?, calendar = ? WHERE id = ? ");
			$stmt->bind_param('sssssssssssi', $event_date_start, $event_date_end, $event_time_start, $event_time_duration, $event_title, $event_description, $event_type, $subject_id, $event_url, $event_public, $event_calendar, $event_id);
			$stmt->execute();

			if ($db_connection->affected_rows > 0) {
				echo "<div class='alert alert-success'>The event has been updated.</div>";
				
			} else {
				echo "<div class='alert alert-danger'>Sorry, the event has not been updated.</div>";
			}

			$stmt->close();
		}else{
			echo  "<div class='alert alert-danger'>Database connection problem.</div>";
		}
	}


}