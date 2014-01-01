	
<?
	$timetable = $timetable->getTimetable($timetable_id);

	if($timetable == false){
		echo '<span class="alert alert-danger">Timetable not found</span>';
	}else{
		$timetable_array = get_object_vars($timetable);

		$subjects = $subject->getSubjects();
		
?>
	
	<script type="text/javascript">
		var timetable_settings =  <?php echo json_encode($timetable_array); ?>;
		var timetable_id = <?php echo $timetable_id;?>;

		var generate_edit_timetable = true;

		<?
		if(isset($timetable->first_shift_timetable) && isset($timetable->second_shift_timetable)){
			$first_shift_timetable = json_decode($timetable->first_shift_timetable);
			$first_shift_timetable = get_object_vars($first_shift_timetable);
			echo 'var first_shift_timetable = '.json_encode($first_shift_timetable).'; ';


			$second_shift_timetable = json_decode($timetable->second_shift_timetable);
			$second_shift_timetable = get_object_vars($second_shift_timetable);
			echo 'var second_shift_timetable = '.json_encode($second_shift_timetable).'; ';


			echo 'var fill_edit_timetable = true;';
		}
		?>

		var subject_labels = <?php echo json_encode($subjects); ?>;

	</script>


	<h1>Timetable Edit: <? echo $timetable->class.' '.$timetable->school ?></h1>
	<legend>Construct the timetable</legend>

	<div class="timetable_errors alert alert-danger"></div>
	<div class="timetable_success alert alert-success"></div>

	<div class="timetable_construct">
	
		<div class="subjects">
			<?


			if($subjects){

				for($i=0; $i<count($subjects); $i++){
					echo '<div class="btn btn-default subject" id="'.$subjects[$i]->subject_id.'" title="'.$subjects[$i]->subject_name.'">'.$subjects[$i]->subject_name_short.'</div>';
				}
			}else{
				echo '<div class="alert alert-danger">No subjects found.</div>';
			}

			?>
		</div>

			<div class="generated_timetable">

				<div class="subject_settings">
		
					<div class="btn btn-danger trash">Trash <span class="glyphicon glyphicon-trash"></span></div>
					
					<div class="btn btn-info standard_level">Add SL <span class="glyphicon glyphicon-plus"></span></div>
					<div class="btn btn-info high_level">Add HL <span class="glyphicon glyphicon-plus"></span></div>
				</div>

				<div class="btn btn-default hide_shift">Toggle First Shift</div>

				<div class="first_shift_container">
					<legend class="timetable_shift">First Shift:</legend>

					<table class="table table-bordered table-hover timetable first_shift">
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
							
						</tbody>
					</table>

					<div class="btn btn-success timetable_save_shift" data-shift="first">Save First Shift</div>
				</div>


				<legend class="timetable_shift">Second Shift:</legend>
				<table class="table table-bordered table-hover timetable second_shift">
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
						
					</tbody>
				</table>

				<div class="btn btn-success timetable_save_shift" data-shift="second">Save Second Shift</div>
			</div>
	</div>

<?
}
?>