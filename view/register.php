
<form method="post" action="" name="registerform">   
	

	<legend>User Credentials</legend>
	<div class="form-group">
		<!-- the user name input field uses a HTML5 pattern check -->
		<label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label>
		<input id="login_input_username" class="login_input form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
	</div>
	<div class="form-group">
	<!-- the email input field uses a HTML5 email type check -->
		<label for="login_input_email">User's email</label>    
		<input id="login_input_email" class="login_input form-control" type="email" name="user_email" required />        
	</div>
	<div class="form-group">
		<label for="login_input_password_new">Password (min. 6 characters)</label>
		<input id="login_input_password_new" class="login_input form-control" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />  
	
	<div class="form-group">
		<label for="login_input_password_repeat">Repeat password</label>
		<input id="login_input_password_repeat" class="login_input form-control" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />   
	</div>
		
	<legend>Select Class</legend>
	<select class="form-control" name="class_id">
		<?
		$timetables = $login->getTimetables();
			for($i=0; $i<count($timetables); $i++){

				echo '<option value="'.$timetables[$i]->timetable_id.'">'.$timetables[$i]->class.' '.$timetables[$i]->school.'</option>';
			}
		?>
	</select>

	<legend>Select Subjects</legend>
	<span class="help-block">These subjects will be used in filtering out your timetable.</span>
	<?

		$subjects = $subject->getSubjects();
		if($subjects){

			for($i=0; $i<count($subjects); $i++){
				if($i%4 == 0) echo '<div class="row">';
				echo '
				<div class="form-group col-md-3">
					<div class="input-group">
						<span class="input-group-addon">
						<input id="'.$subjects[$i]->subject_id.'" name="check_list[]" value="'.$subjects[$i]->subject_id.'" type="checkbox">
						</span>
						<label for="'.$subjects[$i]->subject_id.'" class="form-control">'.$subjects[$i]->subject_name.'</label>
					</div>
				</div>';

				if($i%4 == 3 || $i == count($subjects)-1 ) echo '</div>';
			}
		}else{
			echo '<div class="alert alert-danger">No subjects found.</div>';
		}


	?>


	<input type="submit" class="btn btn-success"  name="register" value="Register" />
	
</form>

<!-- backlink -->