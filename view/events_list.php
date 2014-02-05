

<h2>Events</h2>

<table class="table table-hover">
	<thead>
		<th>ID</th>
		<th>Event Date</th>
		<th>Event Title</th>
		<th>Event Type</th>
		<th>Edit</th>
	</thead>
<?


$events = $event->getEvents();

if($events){
	for($i=0; $i<count($events); $i++){

		echo '
		<tr>
			<td>'.$events[$i]->id.'</td>
			<td>'.$events[$i]->datetime_start.'</td>
			<td>'.$events[$i]->title.'</td>
			<td>'.$events[$i]->type.'</td>
			<td><a href="/event_edit/'.$events[$i]->id.'"><span class="glyphicon glyphicon-edit"></span></a></td>
		</tr>';
	}
}else{
	echo '<div class="alert alert-danger">No events found.</div>';
}


?>
</table>
