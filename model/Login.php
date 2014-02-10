<?php

class Login
{

	private $db_connection = null;

	private $user_name = "";

	private $user_email = "";

	private $user_id= "";

	private $user_group= "member";

	private $user_password_hash = "";

	private $user_is_logged_in = false;

	public $errors = array();

	public $messages = array();

	// public $user_groups = array(
	// 	array("admin",0),
	// 	array("member",1),
	// 	array("moderator",2)
	// );

	public function __construct()
	{

		session_start();


		if (isset($_GET["logout"])) {
			$this->doLogout();
		}elseif (!empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)) {
			$this->loginWithSessionData();
		}elseif (isset($_COOKIE['rememberme'])) {
			$this->loginWithCookieData();
		}elseif (isset($_POST["login"])) {
			if (isset($_POST['user_rememberme'])) {
				$this->loginWithPostData($_POST['user_rememberme']);
			}else{
				$this->loginWithPostData(0);
			}
		}
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


	public function getFilterSubjects($user_id) {
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if (!$this->db_connection->connect_errno) {

				if (mysqli_connect_errno()) {
				  return '<div class="alert alert-danger">Database connection problem.</div>';
				  exit;
				}
				$query = "SELECT filter_subjects FROM users WHERE user_id = '" .$user_id. "';";
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

	private function loginWithCookieData()
	{
		if (isset($_COOKIE['rememberme'])) {
			// extract data from the cookie
			list ($user_id, $token, $hash) = explode(':', $_COOKIE['rememberme']);

			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			// check cookie hash validity
			if ($hash == hash('sha256', $user_id . ':' . $token . COOKIE_SECRET_KEY) && !empty($token)) {
				// cookie looks good, try to select corresponding user
				if ($this->db_connection) {
					// get real token from database (and all other data)
					
					$checkcookie = $this->db_connection->query("SELECT user_name, user_email, user_id, user_group FROM users WHERE user_id = '" . $user_id . "';");

					// $sth = $this->db_connection->prepare("SELECT user_id, user_name, user_email FROM users WHERE user_id = :user_id
					//                                   AND user_rememberme_token = :user_rememberme_token AND user_rememberme_token IS NOT NULL");
					// $sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
					// $sth->bindValue(':user_rememberme_token', $token, PDO::PARAM_STR);
					// $sth->execute();

					if ($checkcookie->num_rows == 1) {

						$result_row = $checkcookie->fetch_object();
						// write user data into PHP SESSION [a file on your server]
						$_SESSION['user_id'] = $result_row->user_id;
						$_SESSION['user_name'] = $result_row->user_name;
						$_SESSION['user_email'] = $result_row->user_email;
						$_SESSION['user_group'] = $result_row->user_group;
						$_SESSION['user_logged_in'] = 1;

						// declare user id, set the login status to true
						$this->user_id = $result_row->user_id;
						$this->user_name = $result_row->user_name;
						$this->user_email = $result_row->user_email;
						$this->user_group = $result_row->user_group;
						$this->user_is_logged_in = true;

						// Cookie token usable only once
						$this->newRememberMeCookie();
						return true;
					}
				}
			}
			// A cookie has been used but is not valid... we delete it
			$this->deleteRememberMeCookie();
			$this->errors[] = 'Invalid cookie';
		}
		return false;
	}

	/**
	 * log in with session data
	 */
	private function loginWithSessionData()
	{
		// set logged in status to true, because we just checked for this:
		// !empty($_SESSION['user_name']) && ($_SESSION['user_logged_in'] == 1)
		// when we called this method (in the constructor)
		$this->user_is_logged_in = true;
	}

	/**
	 * log in with post data
	 */
	private function loginWithPostData($user_rememberme)
	{
		// if POST data (from login form) contains non-empty user_name and non-empty user_password
		if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

			// create a database connection, using the constants from config/db.php (which we loaded in index.php)
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


			// if no connection errors (= working database connection)
			if (!$this->db_connection->connect_errno) {

				// escape the POST stuff
				$this->user_name = $this->db_connection->real_escape_string($_POST['user_name']);
				// database query, getting all the info of the selected user
				$checklogin = $this->db_connection->query("SELECT user_name, user_email, user_password_hash, user_id, user_group FROM users WHERE user_name = '" . $this->user_name . "';");

				// if this user exists
				if ($checklogin->num_rows == 1) {

					// get result row (as an object)
					$result_row = $checklogin->fetch_object();

					// using PHP 5.5's password_verify() function to check if the provided passwords fits to the hash of that user's password
					if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {


						// write user data into PHP SESSION [a file on your server]
						$_SESSION['user_id'] = $result_row->user_id;
						$_SESSION['user_name'] = $result_row->user_name;
						$_SESSION['user_email'] = $result_row->user_email;
						$_SESSION['user_group'] = $result_row->user_group;
						$_SESSION['user_logged_in'] = 1;

						$this->user_id = $result_row->user_id;
						$this->user_name = $result_row->user_name;
						$this->user_email = $result_row->user_email;
						$this->user_group = $result_row->user_group;

						// set the login status to true
						$this->user_is_logged_in = true;

						if (isset($user_rememberme)) {
							$this->newRememberMeCookie();
						} else {
							// Reset rememberme token
							$this->deleteRememberMeCookie();
						}
						

					} else {
						 $this->errors[] = "Wrong password. Try again.";
					}
				} else {
					$this->errors[] =  "This user does not exist.";
				}
			} else {
				$this->errors[] =  "Database connection problem.";
			}
		} elseif (empty($_POST['user_name'])) {
			$this->errors[] =  "Username field was empty.";
		} elseif (empty($_POST['user_password'])) {
			$this->errors[] =  "Password field was empty.";
		}
	}

	private function newRememberMeCookie()
	{
		// if database connection opened
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($this->db_connection) {

			// generate 64 char random string and store it in current user data
			$random_token_string = hash('sha256', mt_rand());
			$user_id = $_SESSION['user_id'];

			$this->db_connection->query("UPDATE users SET user_rememberme_token = '" . $random_token_string. "' WHERE name = '" . $user_id . "'");
			// $sth = $this->db_connection->prepare("UPDATE users SET user_rememberme_token = :user_rememberme_token WHERE user_id = :user_id");
			// $sth->execute(array(':user_rememberme_token' => $random_token_string, ':user_id' => $_SESSION['user_id']));

			// generate cookie string that consists of userid, randomstring and combined hash of both

			$cookie_string_first_part = $_SESSION['user_id'] . ':' . $random_token_string;
			$cookie_string_hash = hash('sha256', $cookie_string_first_part . COOKIE_SECRET_KEY);
			$cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;

			// set cookie
			setcookie('rememberme', $cookie_string, time() + COOKIE_RUNTIME, "/", NULL);
		}
	}

	/**
	 * Delete all data needed for remember me cookie connection on client and server side
	 */
	private function deleteRememberMeCookie()
	{
		$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		// if database connection opened
		if ($this->db_connection) {
			// Reset rememberme token
			$user_id = $this->user_id;
			$this->db_connection->query("UPDATE users SET user_rememberme_token = NULL WHERE name = '" . $user_id . "'");
		}

		// set the rememberme-cookie to ten years ago (3600sec * 365 days * 10).
		// that's obivously the best practice to kill a cookie via php
		// @see http://stackoverflow.com/a/686166/1114320
		setcookie('rememberme', false, time() - (3600 * 3650), '/', NULL);
	}

	/**
	 * perform the logout
	 */
	public function doLogout()
	{
		$this->deleteRememberMeCookie();
		$_SESSION = array();
		session_destroy();
		$this->user_is_logged_in = false;
		$this->messages[] = "You have been logged out.";
		

	}


	public function isUserLoggedIn()
	{
		return $this->user_is_logged_in;
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


	public function getNotifications() {
		$events = $this->getEvents();

		if($events){

			$upcoming_events = '';

			for($i=0; $i<count($events); $i++){
				$event = $events[$i];
				if($event->public){
					$filter_subjects = $this->getFilterSubjects($_SESSION['user_id']);

					if(isset($filter_subjects[0]->filter_subjects)){
						$filter_subjects = array_map('intval', explode(';', $filter_subjects[0]->filter_subjects));
					}

					if(in_array($event->subject_id, $filter_subjects)){
						$event_datetime = strtotime($event->datetime_start.$event->time_start);

						if($event_datetime > strtotime('now') && $event_datetime < strtotime("+3 day")){

							$subject_pair = false;

							$labels = '<span class="label label-success">NEW</span>
							 ';

							if($event->type == 'mock_exam'){
								$labels .= '<span class="label label-warning" title="Mock Exam">EXAM</span>';
							}


							$days_until = floor(($event_datetime - strtotime('now'))/60/60/24);

							if($days_until < 1){
								$days_until = floor(($event_datetime - strtotime('now'))/60/60). ' hours until the event.';
							}else if($days_until == 1){
								$days_until = $days_until.' day until the event.';
							}else{
								$days_until = $days_until.' days until the event.';
							}

							$full_date = date_create_from_format('Y-m-d', $event->datetime_start);
							$full_date = $full_date->format('F jS Y');

							if (isset($event->subject_id)) {
								if($event->subject_id == $events[$i+1]->subject_id && $event->datetime_start == $events[$i+1]->datetime_start){
									$upcoming_events .= '<li class="list-group-item">'.$labels.' '.$event->title.' + '.$events[$i+1]->title.' - <span rel="tooltip" title="'.$full_date.'">'.$days_until.'</span></li>';
									$i++;
									$subject_pair = true;
								}
							}
							if(!$subject_pair){
								$upcoming_events .= '<li class="list-group-item">'.$labels.' '.$event->title.' - <span rel="tooltip" title="'.$full_date.'">'.$days_until.'</span></li>';
							}
						}
					}
				}
			}

			return $upcoming_events;
		}
	}

}
