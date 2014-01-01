

<legend>Timetables</legend>

<table class="table table-hover">
	<thead>
		<th>ID</th>
		<th>Timetable Name</th>
		<th>Creator</th>
		<th>Edit</th>
	</thead>
<?

$timetables = $timetable->getTimetables();
if($timetables){

	for($i=0; $i<count($timetables); $i++){

		echo '
		<tr>
			<td>'.$timetables[$i]->timetable_id.'</td>
			<td>'.$timetables[$i]->class.' '.$timetables[$i]->school.'</td>
			<td>'.$timetables[$i]->creator.'</td>
			<td><a href="/timetable_edit/'.$timetables[$i]->timetable_id.'"><span class="glyphicon glyphicon-edit"></span></a></td>
		</tr>';
	}
}else{
	echo '<div class="alert alert-danger">No timetables found.</div>';
}

?>
</table>
