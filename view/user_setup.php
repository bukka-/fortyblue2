

<form method="post" action="" name="user_setup_submit">
	
<legend>Chose Your Class</legend>



<legend>Chose Your Subjects</legend>
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
					<input id="'.$subjects[$i][$subject_id].'" type="checkbox">
					</span>
					<label for="'.$subjects[$i][$subject_id].'" class="form-control">'.$subjects[$i][$subject_name].'</label>
				</div>
			</div>';

			if($i%4 == 3) echo '</div>';
		}
	}else{
		echo '<div class="alert alert-danger">No subjects found.</div>';
	}


?>



	<div class="btn-group">
		<input type="submit" class="btn btn-default"  name="user_setup_submit" value="Save" />
	</div>
</form>
