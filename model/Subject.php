<?php

class Subject
{

	private $db_connection = null;

	private $user_name = "";

	private $user_id = "";

	public $subjects = Array();

	public function __construct()
	{


		if (isset($_GET["user_setup_submit"])) {
			$this->submitUserSetup();
		}elseif (isset($_GET["user_setup_updatr"])) {
			$this->updateUserSetup();
		}elseif (isset($_POST["new_subject"])){
			$this->newSubject();
		}elseif (isset($_POST["subject_edit"])){
			$this->subjectEdit();
		}
	}


	public function getSubjects() {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_errno) {
			$getSubjects = "";

				if (mysqli_connect_errno()) {
				  return '<div class="alert alert-danger">Database connection problem.</div>';
				  exit;
				}
				$query = "SELECT subject_name, subject_name_short, subject_id, subject_default FROM subjects";
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

	private function newSubject() {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_errno) {

			$this->subject_id = $this->db_connection->real_escape_string($_POST['subject_id']);
			$this->subject_name = $this->db_connection->real_escape_string($_POST['subject_name']);
			$this->subject_name_short = $this->db_connection->real_escape_string($_POST['subject_name_short']);

			if(isset($_POST['subject_default'])){
				$this->subject_default = true;
			}else{
				$this->subject_default = false;
			}

			$query_new_subject_insert = $this->db_connection->query("INSERT INTO subjects (subject_id, subject_name, subject_name_short, subject_default) VALUES('" . $this->subject_id . "', '" . $this->subject_name . "', '" . $this->subject_name_short . "', '" . $this->subject_default . "');");

			if ($query_new_subject_insert) {
				echo "<div class='alert alert-success'>The subject has been added.</div>";
				
			} else {
				echo "<div class='alert alert-danger'>Sorry, the subject has not been added.</div>";
			}

		}else{
			echo  "<div class='alert alert-danger'>Database connection problem.</div>";
		}
	}	

	private function subjectEdit() {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_errno) {

			$this->subject_id = $this->db_connection->real_escape_string($_POST['subject_id']);
			$this->subject_name = $this->db_connection->real_escape_string($_POST['subject_name']);
			$this->subject_name_short = $this->db_connection->real_escape_string($_POST['subject_name_short']);

			if(isset($_POST['subject_default'])){
				$this->subject_default = true;
			}else{
				$this->subject_default = false;
			}

			$stmt = $this->db_connection->prepare("UPDATE subjects SET subject_name = ?, subject_name_short = ?, subject_default = ? WHERE subject_id = ? ");
			$stmt->bind_param('sssi', $this->subject_name, $this->subject_name_short, $this->subject_default, $this->subject_id);
			$stmt->execute();

			if ($this->db_connection->affected_rows > 0) {
				echo "<div class='alert alert-success'>The subject has been updated.</div>";
				
			} else {
				echo "<div class='alert alert-danger'>Sorry, the subject has not been updated.</div>";
			}

			$stmt->close();
		}else{
			echo  "<div class='alert alert-danger'>Database connection problem.</div>";
		}
	}


}