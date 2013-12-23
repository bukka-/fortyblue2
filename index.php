<?php


$URI_parsed = parse_url($_SERVER['REQUEST_URI']);
$URI_parts  = explode('/', $URI_parsed['path']);
$page = strtolower($URI_parts[1]);


if($page == "home"){
	controller_home();
}else if($page == "register"){
	controller_register();
}else{
	$page = "home";
	controller_home();
}






function controller_home(){
	include('view/header.php');
	include('view/home.php');
}

function controller_register(){
	include('view/header.php');
	include('view/register.php');
}

include('view/footer.php');
?>