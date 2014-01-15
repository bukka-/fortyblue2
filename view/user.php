<?

if($user->checkUser($user_name)){
	echo '<h1>This is '.$user_name.'\'s profile </h1>';

	$filter_subjects = $login->getFilterSubjects($_SESSION['user_id']);

	$filter_subjects = unserialize($filter_subjects[0]->filter_subjects);

	$subjects = $subject->getSubjects();

	$user_info = $user->getUserInfo($user_name);

	$user_id = $user_info[0]->user_id;

	


	echo "<h2>Subjects</h2>";

	echo '<div class="list-group">';

		for($i=0; $i<count($filter_subjects); $i++){



			echo '<a href="/user/'.$user_name.'/'.$subjects[$filter_subjects[$i]-1]->subject_id.'" class="list-group-item">'.$subjects[$filter_subjects[$i]-1]->subject_name.' ('.$subjects[$filter_subjects[$i]-1]->subject_name_short.')';


			$subject_grades = $subject->getSubjectGrades($subjects[$filter_subjects[$i]-1]->subject_id, $user_id);

			if(isset($subject_grades[0]->grade)){
				$grades_total = 0;
				$grades_number = count($subject_grades);

				for ($k=0; $k < $grades_number; $k++) { 
					$grades_total += (int)$subject_grades[$k]->grade;
				}
				$grade_avg = $grades_total/$grades_number;
				echo '<span class="badge badge-success" title="Grade Avarage: '.round($grade_avg, 2).'">'.round($grade_avg).'</span>';
			}else{
				echo '<span class="badge" title="No Grades Found">0</span>';
			}

			echo '</a>';

		}
	echo '</div>';

}else{
	echo '<span class="alert alert-danger">User '.$user_name.' does not excist.</span>';
}
?>