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

				<div class="btn-group nav-dropdown">
					<button type="button" class="btn btn-default dropdown-toggle dropdown-timetable" data-toggle="dropdown">
						<?
						if(isset($timetable_id)){
							$timetable2 = $timetable->getTimetable($timetable_id);
							echo $timetable2->class.' '.$timetable2->school;
						}else{
							echo 'Select Timetable';
						}

						?>
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu timetable-select" role="menu">
						<?
						$timetables = $login->getTimetables();
							for($i=0; $i<count($timetables); $i++){
								$highlight = "";
								if(isset($timetable_id) && $timetable_id == $timetables[$i]->timetable_id) $highlight = 'class="highlight"';

								echo '<li '.$highlight.'>
										<a href="/timetable/'.$timetables[$i]->timetable_id.'">'.$timetables[$i]->class.' '.$timetables[$i]->school.'</a>
									  </li>';
							}
						?>
						

						<?
						if(isset($_SESSION['user_group']) == "admin"){
						?>
						<li class="divider"></li>
						<li><a href="/timetable_new">Add New</a></li>
						<?
						}	
						?>
					</ul>
				</div>
			<p class="navbar-text pull-right user_nav">
				<?
				if ($login->isUserLoggedIn() == true) {
				?>
					Hi <a href="/user/<? echo $_SESSION['user_name'];?>"><? echo $_SESSION['user_name'];?></a>! <? if(isset($_SESSION['user_group']) == "admin") echo'<a class="btn btn-success" href="/panel">Panel</a>'; ?> <a class="btn btn-default" href="/new_login.php?logout">Logout</a>
				<?
				} else{
				?>
					Hello Guest! <a href="#" id="log-in" class="navbar-link">Log In</a> or <a href="/register" class="btn btn-success">Register</a>
				<?
				}
				?>
			</p>
			</div>
		</nav>

	<div class="container">

		<? 
			if(count($login->errors)>0){
				for($i=0;$i<count($login->errors);$i++){
					echo '<div class="alert alert-danger">'.$login->errors[$i].'</div>'; 
				}
			} 
		?>

		<? if(count($login->messages)>0){
			for($i=0;$i<count($login->messages);$i++){
				echo '<div class="alert alert-success">'.$login->messages[$i].'</div>'; 
			}
		} 
		
		?>

		<div class="row user_log_in">
			<?
				if ($login->isUserLoggedIn() == false) {
					include('./view/login.php');
				}
			?>
		</div>

