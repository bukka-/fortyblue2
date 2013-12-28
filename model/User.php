<?php

class User
{

	private $db_connection = null;

	private $user_name = "";

	private $user_email = "";

	private $user_id= "";

	private $user_group= "member";


	public function __construct()
	{

		if (isset($_POST["user_edit"])) {
				$this->editUser();
		}
	}

	public function checkUser($username){
		$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$query_check_user_name = $db_connection->query("SELECT * FROM users WHERE user_name = '" . $username . "';");

		if ($query_check_user_name->num_rows == 1) {
		  return true;
		}
	}

	public function getUsers() {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_errno) {

				if (mysqli_connect_errno()) {
				  return '<div class="alert alert-danger">Database connection problem.</div>';
				  exit;
				}
				$query = "SELECT user_id, user_name, user_group FROM users";
				$result = $this->db_connection->query($query);


				if ($result->num_rows < 1) {
				  return false;
				}     

				while($row = $result->fetch_row()) {
				  $rows[]=$row;
				}
				$result->close();
				$this->db_connection->close();
				return $rows;

		}else{
				return '<div class="alert alert-danger">Database connection problem.</div>';
		}

	}	


	public function getUserInfo($user_name) {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_errno) {

				if (mysqli_connect_errno()) {
				  return '<div class="alert alert-danger">Database connection problem.</div>';
				  exit;
				}
				$query = "SELECT user_id, user_email, user_group FROM users WHERE user_name = '".$user_name."'";
				$result = $this->db_connection->query($query);


				if ($result->num_rows < 1) {
				  return false;
				}     

				while($row = $result->fetch_row()) {
				  $rows[]=$row;
				}
				$result->close();
				$this->db_connection->close();
				return $rows;

		}else{
				return '<div class="alert alert-danger">Database connection problem.</div>';
		}

	}


	private function editUser()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if (!$this->db_connection->connect_errno) {

			$this->user_id = $this->db_connection->real_escape_string($_POST['user_id']);
			$this->user_group = $this->db_connection->real_escape_string($_POST['user_group']);


			$stmt = $this->db_connection->prepare("UPDATE users SET user_group = ? WHERE user_id = ? ");
			$stmt->bind_param('si', $this->user_group, $this->user_id);
			$stmt->execute();

			if ($this->db_connection->affected_rows > 0) {
				echo "<div class='alert alert-success'>The user has been updated.</div>";
				
			} else {
				echo "<div class='alert alert-danger'>Sorry, the user has not been updated.</div>";
			}

			$stmt->close();
		}else{
			echo  "<div class='alert alert-danger'>Database connection problem.</div>";
		}
	}


	public function getUserGroup()
	{
		return $this->user_group;
	}

}