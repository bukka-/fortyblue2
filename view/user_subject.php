<?

	$filter_subjects = $login->getFilterSubjects($_SESSION['user_id']);

	if(isset($filter_subjects[0]->filter_subjects)){
		$filter_subjects = array_map('intval', explode(';', $filter_subjects[0]->filter_subjects));
	}

	$subjects = $subject->getSubjects();

	$user_info = $user->getUserInfo($user_name);

	$user_id = $user_info[0]->user_id;

	$subject_grades = $subject->getSubjectGrades($subject_id, $user_id);

	if(isset($subjects[$subject_id-1])){
		echo '<h2><a href="/user/'.$user_name.'">'.$user_name.'</a></h2>';
		echo '<h3>'.$subjects[$subject_id-1]->subject_name.'</h3>';
	?>

	<script>

	var user_id = <? echo $user_id; ?>;
	var subject_id = <? echo $subject_id; ?>;

	</script>

	<table class="table table-hover table-bordered">
		<colgroup>
		      <col span="1" style="width: 30%;">
		      <col span="1" style="width: 50%;">
		      <col span="1" style="width: 15%;">
		      <col span="1" style="width: 5%;">
		</colgroup>
		<thead>
			<th>Date</th>
			<th>Task</th>
			<th>Grade</th>
			<th>Edit</th>
		</thead>
		<tbody class="grade_list">
			<?

			if(isset($subject_grades[0]->task)){			
				for ($i=0; $i < count($subject_grades); $i++) { 
					$date = date_create_from_format('Y-m-d', $subject_grades[$i]->date);

					$date_full = $date->format('F jS Y');
					$date = $date->format('F jS');
					echo '
					<tr id="'.$i.'" data-gradeid="'.$subject_grades[$i]->id.'">
						<td id="row_grade_date"  title="'.$date_full.'">'.$date.'</td>
						<td id="row_grade_task" >'.$subject_grades[$i]->task.'</td>
						<td id="row_grade_value" >'.$subject_grades[$i]->grade.'</td>
					';
					if(isset($_SESSION['user_group']) == $user_name){
						echo '<td class="align-middle edit_grade" data-rowid="'.$i.'" data-loading-text="..."><span class="btn btn-default glyphicon glyphicon-edit" title="Edit" ></span></td>';
					}
					
					echo '
					</tr>
					';
				}
			}else{
				echo '<tr class="danger"><td colspan="4">No Grades Found</td></tr>';
			}

			?>
			<tr style="display:none;" id="add_grade_controls">
				<td><input name="" id="grade_date" class="form-control full-width" type="date" placeholder="mm/dd/yyyy"/></td>
				<td><input name="" id="grade_task" class="form-control full-width" type="text" placeholder="Task"/></td>
				<td><input name="" id="grade_value" class="form-control full-width" type="number" min="1" max="7" placeholder="Grade"/></td>
				<td class="align-middle" id="submit_grade" data-loading-text="..." ><span title="Add" class="btn btn-default glyphicon glyphicon-plus"></span></td>
			</tr>
			<tr>
				<td colspan="4" class="btn btn-default table-button" id="add_grade"><i class="glyphicon glyphicon-plus-sign"></i> Add Grade</td>
			</tr>
		</tbody>
	</table>

	<?

	}else{
		echo "<span class='alert alert-danger'>No such subject</span>";
	}

	?>


