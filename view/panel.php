<?
echo '<legend>Welcome '.$_SESSION['user_name'].'</legend>';
?>

<div class="row">
	<div class="col-sm-6 col-md-3">
		<div class="thumbnail">
			<div class="caption">
				<h3>Subjects</h3>
				<p>All subjects are listed here and can be edited.</p>
				<p><a href="/subject_list" class="btn btn-success" role="button">Subject List</a> <a href="/new_subject" class="btn btn-default" role="button">New Subjects</a></p>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-3">
		<div class="thumbnail">
			<div class="caption">
				<h3>Users</h3>
				<p>All users are listed here and can be edited.</p>
				<p><a href="/user_list" class="btn btn-success" role="button">User List</a></p>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-3">
		<div class="thumbnail">
			<div class="caption">
				<h3>Timetables</h3>
				<p>Use this to create and manage timetables.</p>
				<p><a href="/timetable_list" class="btn btn-success" role="button">Timetable List</a> <a href="/timetable_new" class="btn btn-default" role="button">New Timetable</a></p>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-3">
		<div class="thumbnail">
			<div class="caption">
				<h3>Events</h3>
				<p>Use this to create and manage events.</p>
				<p><a href="/events_list" class="btn btn-success" role="button">Events List</a> <a href="/event_new" class="btn btn-default" role="button">New Event</a></p>
			</div>
		</div>
	</div>
</div>
