
<?
$events = $event->getEvents();

if(isset($event_new)){

	$prev_event_id = count($events)-1;
?>



<h2>New Event</h2>

<form method="post" action="" name="event_new">
	<div class="form-group">
		<label for="event_id">Event ID</label>
		<input id="event_id" class="login_input form-control" type="text" name="event_id" value="<? echo $prev_event_id+2; ?>" required />
	</div>
	<div class="form-group">
		<label for="datepicker">Event Date</label>
		<div class="input-daterange input-group" id="datepicker">
			<input type="text" class="input-sm form-control" value="<? echo date("m/d/Y", strtotime($events[$prev_event_id]->datetime_start)); ?>" name="event_date_start" />
			<span class="input-group-addon">to</span>
			<input type="text" class="input-sm form-control" value="<? echo date("m/d/Y", strtotime($events[$prev_event_id]->datetime_end)); ?>" name="event_date_end" />
		</div>
	</div>
	<div class="form-group">
		<label for="timepicker">Event Time</label>
		<div class="input-group bootstrap-timepicker">
			<span class="input-group-addon">Start</span>
			<input id="timepicker1" type="text" value="<? echo $events[$prev_event_id]->time_start; ?>" name="event_time_start" class="input-small form-control" />
			<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group bootstrap-timepicker">
			<span class="input-group-addon">Duration</span>
			<input id="timepicker2" type="text" value="<? echo $events[$prev_event_id]->time_duration; ?>" name="event_time_duration" class="input-small form-control" />
			<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
		</div>
	</div>
	<div class="form-group">
		<label for="event_title">Event Title</label>
		<input id="event_title" class="login_input form-control" value="<? echo $events[$prev_event_id]->title; ?>" type="text" name="event_title" required />
	</div>
	<div class="form-group">
		<label for="event_description">Event Description</label>
		<input id="event_description" class="login_input form-control" value="<? echo $events[$prev_event_id]->description; ?>" type="text" name="event_description" />
	</div>
	<div class="form-group">
		<label for="event_type">Event Type</label>
		<input id="event_type" class="login_input form-control" value="<? echo $events[$prev_event_id]->type; ?>" type="text" name="event_type" required />
	</div>
	<label for="event_type">Event for Subject</label>
	<?
		$subjects = $subject->getSubjects();
		if($subjects){

			for($i=0; $i<count($subjects); $i++){
				if($i%4 == 0) echo '<div class="row">';
				if($subjects[$i]->subject_id == $events[$prev_event_id]->subject_id){
					$check_subject = 'checked="checked"';
				}else{
					$check_subject = '';
				}
				echo '
				<div class="form-group col-md-3">
					<div class="input-group">
						<span class="input-group-addon">
						<input id="'.$subjects[$i]->subject_id.'" name="subject_id" value="'.$subjects[$i]->subject_id.'" type="checkbox" '.$check_subject.'>
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
	<div class="form-group">
		<label for="event_url">Event URL</label>
		<input id="event_url" class="login_input form-control" value="<? echo $events[$prev_event_id]->url; ?>" type="text" name="event_url" />
	</div>
	<div class="form-group">
		<input type="checkbox" id="event_public" name="event_public" value="1" <? if($events[$prev_event_id]->public) echo 'checked="checked"' ?>/>
		<label for="event_public">Public</label>
	</div>

	<input type="submit" class="btn btn-default"  name="event_new" value="Add" />
</form>

<script>
	$('.input-daterange').datepicker({
	    weekStart: 1,
	    todayBtn: "linked",
	    keyboardNavigation: false,
	    autoclose: true,
	    todayHighlight: true
	});

	$('#timepicker1').timepicker({showMeridian: false, defaultTime: '00:00'});
	$('#timepicker2').timepicker({showMeridian: false, defaultTime: '00:00'});
</script>



<?
}else{


if($events && $event_id >= 0){

?>
	<h2>Edit <? echo $events[$event_id]->title; ?></h2>

	<form method="post" action="" name="event_edit">
		<div class="form-group">
			<label for="event_id">Event ID</label>
			<input id="event_id" class="login_input form-control" type="text" name="event_id" value="<? echo $events[$event_id]->id; ?>" required readonly="readonly"/>
		</div>
		<div class="form-group">
			<label for="datepicker">Event Date</label>
			<div class="input-daterange input-group" id="datepicker">
				<input type="text" class="input-sm form-control" value="<? echo date("m/d/Y", strtotime($events[$event_id]->datetime_start)); ?>" name="event_date_start" />
				<span class="input-group-addon">to</span>
				<input type="text" class="input-sm form-control" value="<? echo date("m/d/Y", strtotime($events[$event_id]->datetime_end)); ?>" name="event_date_end" />
			</div>
		</div>
		<div class="form-group">
			<label for="timepicker">Event Time</label>
			<div class="input-group bootstrap-timepicker">
				<span class="input-group-addon">Start</span>
				<input id="timepicker1" type="text" value="<? echo $events[$event_id]->time_start; ?>" name="event_time_start"  class="input-small form-control">
				<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
			</div>
		</div>
		<div class="form-group">
			<div class="input-group bootstrap-timepicker">
				<span class="input-group-addon">Duration</span>
				<input id="timepicker2" type="text" value="<? echo $events[$event_id]->time_duration; ?>" name="event_time_duration" class="input-small form-control">
				<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
			</div>
		</div>
		<div class="form-group">
			<label for="event_title">Event Title</label>
			<input id="event_title" class="login_input form-control" value="<? echo $events[$event_id]->title; ?>" type="text" name="event_title" required />
		</div>
		<div class="form-group">
			<label for="event_description">Event Description</label>
			<input id="event_description" class="login_input form-control" value="<? echo $events[$event_id]->description; ?>" type="text" name="event_description" />
		</div>
		<div class="form-group">
			<label for="event_type">Event Type</label>
			<input id="event_type" class="login_input form-control" value="<? echo $events[$event_id]->type; ?>" type="text" name="event_type" required />
		</div>
		<label for="event_type">Event for Subject</label>
		<?
			$subjects = $subject->getSubjects();
			if($subjects){

				for($i=0; $i<count($subjects); $i++){
					if($i%4 == 0) echo '<div class="row">';
					if($subjects[$i]->subject_id == $events[$event_id]->subject_id){
						$check_subject = 'checked="checked"';
					}else{
						$check_subject = '';
					}
					echo '
					<div class="form-group col-md-3">
						<div class="input-group">
							<span class="input-group-addon">
							<input id="'.$subjects[$i]->subject_id.'" name="subject_id" value="'.$subjects[$i]->subject_id.'" type="checkbox" '.$check_subject.'>
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
		<div class="form-group">
			<label for="event_url">Event URL</label>
			<input id="event_url" class="login_input form-control" value="<? echo $events[$event_id]->url; ?>" type="text" name="event_url" />
		</div>
		<div class="form-group">
			<input type="checkbox" id="event_public" name="event_public" value="1" <? if($events[$event_id]->public) echo 'checked="checked"' ?> />
			<label for="event_public">Public</label>
		</div>

		<input type="submit" class="btn btn-default"  name="event_edit" value="Update" />
	</form>

	<script>
		$('.input-daterange').datepicker({
		    weekStart: 1,
		    todayBtn: "linked",
		    keyboardNavigation: false,
		    autoclose: true,
		    todayHighlight: true
		});

		$('#timepicker1').timepicker({showMeridian: false, defaultTime: '<? echo $events[$event_id]->time_start; ?>'});
		$('#timepicker2').timepicker({showMeridian: false, defaultTime: '<? echo $events[$event_id]->time_duration; ?>'});
	</script>

<?
}else{
	echo '<div class="alert alert-danger">No event with such ID</div>';
}

}

?>
