	
<?
	$timetable = $timetable->getTimetable($timetable_id);
	if($timetable == false){
		echo '<span class="alert alert-danger">Timetable not found</span>';
	}else{
		$timetable_array = get_object_vars($timetable);

		$subjects = $subject->getSubjects();

		$filter_subjects = $login->getFilterSubjects($_SESSION['user_id']);

		$filter_subjects = explode(';', $filter_subjects[0]->filter_subjects);
?>
	
	<script type="text/javascript">
		var timetable_settings =  <?php echo json_encode($timetable_array); ?>;
		var timetable_id = <?php echo $timetable_id;?>;

		var generate_timetable = true;


		<? echo 'var shift_start = "'.(string)$timetable->shift_start.'"; '; ?>

		<?
		if(isset($timetable->first_shift_timetable) && isset($timetable->second_shift_timetable)){
			$first_shift_timetable = json_decode($timetable->first_shift_timetable);
			$first_shift_timetable = get_object_vars($first_shift_timetable);
			echo 'var first_shift_timetable = '.json_encode($first_shift_timetable).'; ';


			$second_shift_timetable = json_decode($timetable->second_shift_timetable);
			$second_shift_timetable = get_object_vars($second_shift_timetable);
			echo 'var second_shift_timetable = '.json_encode($second_shift_timetable).'; ';
		}

		if(isset($filter_subjects)){
			echo 'var filter_subjects = '.json_encode($filter_subjects).'; ';
		}

		?>

		var subject_labels = <?php echo json_encode($subjects); ?>;

	</script>


		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">	
					<div class="panel-heading">Time until next lesson</div>
					<div class="panel-body">
						<!-- <span class="time-until-text"></span> -->
						<span class="time-until">02:42</span>
						<span class="glyphicon glyphicon-time remind-me" rel="tooltip" title="Set Reminder"></span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">Notifications</div>
					<!-- <div class="panel-body"></div> -->
					<ul class="list-group">
						<li class="list-group-item"><span class="label label-success">NEW</span> No school on January 19th </li>
						<li class="list-group-item"><span class="label label-danger">Warning</span> Math test on Thursday (January 22nd), in 2 days</li>
						<li class="list-group-item"><span class="label label-default">OLD</span> Shorter Lessons on January 11th</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-3">
					<input id="filter_subjects" name="filter_subjects" type="checkbox" checked>
					<label for="filter_subjects">Filter subjects</label>
			</div>
		</div>

		<div class="timetable_container filtered">
			<table class="table table-bordered table-hover timetable">
				<thead>
					<tr>
						<th>Time</th>
						<th>Monday</th>
						<th>Tuesday</th>
						<th>Wednesday</th>
						<th>Thrusday</th>
						<th>Friday</th>
					</tr>
				</thead>
				<tbody>
					<tr class="timeline-fill"></tr>
					<tr class="timeline"></tr>
					<!-- Subjects will be inserted here -->
				</tbody>
			</table>
			<div class="timetable_events">
				
			</div>
		</div>

<?
}
?>