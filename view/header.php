<!DOCTYPE html>
<html>
	<head>
		<title>sixtylime</title>

		<script src="/dev/main.js"></script>
		<script src="/vendor/bootstrap/js/bootstrap.js"></script>
		<link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="/css/main.css">


		<link rel="shortcut icon" href="/favicon.ico" />
	</head>

	<body>
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="nav-logo" href="/">Brand</a>
				</div>

				<div class="btn-group">
					<button type="button" class="btn btn-default dropdown-toggle dropdown-timetable" data-toggle="dropdown">
						Test <span class="caret"></span>
					</button>
					<ul class="dropdown-menu timetable-select" role="menu">

						<li>
							<a>Test</a>
						</li>
						<li class="divider"></li>
						<li><a href="#">Add Custom</a></li>
					</ul>
				</div>
			<?
			if ($login->isUserLoggedIn() == true) {
			?>
				<p class="navbar-text pull-right">Hi <a href="/user/<? echo $_SESSION['user_name'];?>"><? echo $_SESSION['user_name'];?></a>! <a class="btn btn-default" href="/new_login.php?logout">Logout</a></p>
			<?
			} else{
			?>
				<p class="navbar-text pull-right">Hello Guest! <a href="#" id="log-in" class="navbar-link">Log In</a> or <a href="/register" class="btn btn-success">Register</a></p>
			<?
			}
			?>
			</div>
		</nav>

	<div class="container">

		<? if(count($login->errors)>0) echo '<div class="alert alert-danger">'.$login->errors[0].'</div>'; ?>

		<div class="row user_log_in">
			<?
				if ($login->isUserLoggedIn() == false) {
					include('./view/login.php');
				}
			?>
		</div>

