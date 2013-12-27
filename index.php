<?php

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once($_SERVER['DOCUMENT_ROOT']."libraries/password_compatibility_library.php");
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
	controller_user($user);
}else if($page == "timetable"){
	if (count($URI_parts) > 2){
		$timetable = strtolower($URI_parts[2]);
	}else{
		$timetable = 1;
	}
	if($timetable == 'new'){
		controller_timetable_new();
	}else{
		controller_timetable($timetable);
	}
}else if($page == "user_setup"){
	controller_user_setup();
}else if($page == "new_subject"){
	controller_new_subject();
}else{
	$page = "home";
	controller_home();
}


function controller_home(){
	global $login;
	include('view/header.php');
	include('view/home.php');
}

function controller_register(){
	global $login;
	$registration = new Registration($login);
	
	include('view/header.php');
	include("view/register.php");
}

function controller_user($user){
	global $login;
	$registration = new Registration($login);

	include('view/header.php');
	include('view/user.php');
}

function controller_timetable_new(){
	global $login;
	include('view/header.php');
	include('model/timetable.php');
	include('view/timetable_new.php');
}

function controller_timetable($timetable){
	global $login;
	include('view/header.php');
	include('model/timetable.php');
	include('view/timetable.php');
}

function controller_user_setup(){
	global $login;
	include('view/header.php');
	include('model/Subject.php');
	$subject = new Subject($login);
	include('view/user_setup.php');
}

function controller_new_subject(){
	global $login;
	include('view/header.php');
	include('model/Subject.php');
	$subject = new Subject($login);
	include('view/new_subject.php');
}

include('view/footer.php');
?>