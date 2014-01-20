<?php



if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("./libraries/password_compatibility_library.php");
}

include('./db.php');

require_once("./model/Login.php");


$login = new Login();

require_once("./model/Registration.php");



$URI_parsed = parse_url($_SERVER['REQUEST_URI']);
$URI_parts  = explode('/', $URI_parsed['path']);
$page = strtolower($URI_parts[1]);

if($page == "home"){
	controller_home();
}else if($page == "register"){
	controller_register();
}else if($page == "user" && strtolower($URI_parts[2])!=""){
	$user = strtolower($URI_parts[2]);
	if(isset($URI_parts[3])){
		$subject_id = strtolower($URI_parts[3]);
		controller_user_subject($user, $subject_id);
	}else{	
		controller_user($user);
	}
}else if($page == "user_edit" && strtolower($URI_parts[2])!=""){
	$user = strtolower($URI_parts[2]);
	controller_user_edit($user);
}else if($page == "user_list"){
	controller_user_list();
}else if($page == "timetable"){
	if (count($URI_parts) > 2){
		$timetable_id = strtolower($URI_parts[2]);
	}else{
		$timetable_id = 1;
	}
	controller_timetable($timetable_id);
}else if($page == "timetable_new"){
	controller_timetable_new();
}else if($page == "timetable_list"){
	controller_timetable_list();
}else if($page == "timetable_edit" && strtolower($URI_parts[2])!=""){
	$timetable_id = intval(strtolower($URI_parts[2]));
	controller_timetable_edit($timetable_id);
}else if($page == "user_setup"){
	controller_user_setup();
}else if($page == "new_subject"){
	controller_new_subject();
}else if($page == "subject_list"){
	controller_subject_list();
}else if($page == "subject_edit" && strtolower($URI_parts[2])!=""){
	$subject = intval(strtolower($URI_parts[2]))-1;
	controller_subject_edit($subject);
}else if($page == "panel"){
	controller_panel();
}else if($page == "feed"){
	controller_feed();
}else{
	if(isset($_SESSION['user_id'])){
		$page = "timetable";
		controller_timetable(3);
	}else{
		$page = "home";
		controller_home();
	}
}


function controller_home(){
	global $login;
	include('./view/header.php');
	include('./view/home.php');
}

function controller_register(){
	global $login;
	$registration = new Registration($login);
	
	include('./model/Subject.php');
	$subject = new Subject($login);
	
	include('./view/header.php');
	include("view/register.php");
}

function controller_user($user_name){
	global $login;
	$registration = new Registration($login);

	include('./view/header.php');
	include('./model/User.php');
	$user = new User();

	include('./model/Subject.php');
	$subject = new Subject();

	include('./view/user.php');
}

function controller_user_subject($user_name, $subject_id){
	global $login;
	$registration = new Registration($login);

	include('./view/header.php');
	include('./model/User.php');
	$user = new User();

	include('./model/Subject.php');
	$subject = new Subject();

	include('./view/user_subject.php');
}

function controller_user_edit($user_edit_name){
	global $login;

	include('./view/header.php');
	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/User.php');
		$user = new User();
		include('./view/user_edit.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_user_list(){
	global $login;

	include('./view/header.php');
	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/User.php');
		$user = new User();
		include('./view/user_list.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_timetable_new(){
	global $login;
	include('./view/header.php');

	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/Timetable.php');
		$timetable = new Timetable();
		include('./model/Subject.php');
		$subject = new Subject();

		include('./view/timetable_new.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_timetable($timetable_id){
	global $login;

	include('./model/Timetable.php');
	$timetable = new Timetable();
	
	include('./view/header.php');

	include('./model/Subject.php');
	$subject = new Subject();

	include('./view/timetable.php');
}

function controller_timetable_list(){
	global $login;
	include('./view/header.php');

	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/Timetable.php');
		$timetable = new Timetable();
		include('./view/timetable_list.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_timetable_edit($timetable_id){
	global $login;

	include('./model/Timetable.php');
	$timetable = new Timetable();
	include('./view/header.php');
	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/Subject.php');
		$subject = new Subject();
		include('./view/timetable_edit.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_user_setup(){
	global $login;
	include('./view/header.php');

	include('./view/user_setup.php');
}

function controller_new_subject(){
	global $login;
	include('./view/header.php');
	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/Subject.php');
		$subject = new Subject($login);
		include('./view/new_subject.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}

}

function controller_subject_list(){
	global $login;
	include('./view/header.php');
	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/Subject.php');
		$subject = new Subject($login);
		include('./view/subject_list.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_subject_edit($subject_edit_id){
	global $login;
	include('./view/header.php');
	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./model/Subject.php');
		$subject = new Subject($login);
		include('./view/subject_edit.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_panel(){
	global $login;
	include('./view/header.php');
	if(isset($_SESSION['user_group']) && $_SESSION['user_group'] == "admin"){
		include('./view/panel.php');
	}else{
		echo '<span class="alert alert-danger">Sorry, you do not have permission to access this area.</span>';
	}
}

function controller_feed(){
	global $login;
	if(isset($_POST['value'])){
		include('./model/TimetableContent.php');
	}else if(isset($_POST['grade_data']) || isset($_POST['grade_edit_data'])){
		include('./model/UserSubject.php');
	}
}

include('./view/footer.php');
?>