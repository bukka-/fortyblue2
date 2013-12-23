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
	<?
	include('./user/index.php');
	?>
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
				<p class="navbar-text pull-right">Hi <? echo $_SESSION['user_name'];?>';! <a class="navbar-link" href="/user/index.php?logout">Logout</a></p>
			<?
			} else{
			?>
				<p class="navbar-text pull-right">Hello Guest! <a href="#" id="log-in" class="navbar-link">Log In</a> or <a href="/register" class="navbar-link">Register</a></p>
			<?
			}
			?>
			</div>
		</nav>

	<div class="container">

