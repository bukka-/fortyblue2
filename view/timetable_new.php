
		<h1>New Timetable</h1>
		<legend>Timetable Settings</legend>

		<form method="post" action="" class="timetable_settings_form" name="timetable_settings_submit">

			<div class="form-group">
				<label for="class">Name/Class</label>
				<input  id="class" name="class" type="text" class="form-control" placeholder="IV-3" />
			</div>

			<div class="form-group">
				<label for="school">School</label>
				<input  id="school" name="school" type="text" class="form-control" placeholder="IB" />
			</div>

			<div class="form-group">
				<label for="time_start">Time at which the timetable will start</label>
				<input  id="time_start" name="time_start" type="text" class="form-control" placeholder="08:00" />
			</div>

			<div class="form-group">
				<label for="lesson_duration">Duration of each lesson in minutes</label>
				<input  id="lesson_duration" name="lesson_duration" type="text" class="form-control" placeholder="45" />
			</div>

			<div class="form-group">
				<label for="minibreak_duration">Duration of each minibreak in minutes</label>
				<input  id="minibreak_duration" name="minibreak_duration" type="text" class="form-control" placeholder="5" />
			</div>

			<div class="form-group">
				<label for="break_duration">Duration of each break in minutes</label>
				<input  id="break_duration" name="break_duration" type="text" class="form-control" placeholder="20" />
			</div>

			<div class="form-group">
				<label for="break_amount">Amount of breaks</label>
				<input  id="break_amount" name="break_amount" type="text" class="form-control" placeholder="3" />
			</div>

			<div class="form-group">
				<label for="break_lessons">Break after X lessons</label>
				<input  id="break_lessons" name="break_lessons" type="text" class="form-control" placeholder="3" />
			</div>

			<div class="form-group">
				<input  id="shift_check" name="shift_check" type="checkbox"/>
				<label for="shift_check">Shifts</label>
			</div>

			<div class="form-group">
				<label for="shift_start">Shift of first week in the year</label>
				<input  id="shift_start" name="shift_start" type="text" class="form-control" placeholder="first" />
			</div>

			<input type="submit" class="btn btn-default" name="timetable_settings_submit" value="Save" />
		</form>