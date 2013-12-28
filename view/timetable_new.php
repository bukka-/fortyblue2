		<script>
			$(function(){
				$('.subjects .subject').draggable({
					revert:true,
					helper:'clone'
				});
				$('.timetable td.drop').droppable({
					over:function(){
						$(this).addClass('over');
					},
					out:function(){
						$(this).removeClass('over');
					},
					drop:function(e,source){
						source.helper[0].remove();
						source = source.draggable[0];
						$(this).removeClass('over');
						if ($(source).hasClass('assigned')){
							$(this).append(source);
						} else {
							var c = $(source).clone().addClass('assigned');
							$(this).append(c);
							c.draggable({
								revert:true
							});
						}
					}
				});

				$('.trash').droppable({
					drop:function(e,source){
						source = source.draggable[0];
						if ($(source).hasClass('assigned')){
							$(source).remove();
						}
					}
				});

				$('.standard_level').droppable({
					over:function(e,source){
						helper = source.helper[0];

						helper_text = $(helper).text();
						$(helper).text(helper_text + " SL");
						// $(source).attr("id");
					}
				});


			});
		</script>
		<h1>New Timetable</h1>
		<legend>Timetable Settings</legend>

		<form action="">
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
		</form>

		<legend>Construct the timetable</legend>

		<div class="subjects">
			<?

			$subjects = $subject->getSubjects();
			if($subjects){
				$subject_id = 2;
				$subject_name = 0;
				$subject_name_short = 1;

				for($i=0; $i<count($subjects); $i++){
					echo '<div class="btn btn-default subject" id="'.$subjects[$i][$subject_id].'" title="'.$subjects[$i][$subject_name].'">'.$subjects[$i][$subject_name_short].'</div>';
				}
			}else{
				echo '<div class="alert alert-danger">No subjects found.</div>';
			}

			?>
		</div>

		<div>
			<div class="subject_settings">
	
				<div class="btn btn-danger trash">Trash <span class="glyphicon glyphicon-trash"></span></div>
				
				<div class="btn btn-info standard_level">Add SL <span class="glyphicon glyphicon-plus"></span></div>
				<div class="btn btn-info high_level">Add HL <span class="glyphicon glyphicon-plus"></span></div>
			</div>


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
					<tr>
						<td class="time">08:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
					<tr>
						<td class="time">09:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
					<tr>
						<td class="time">10:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
					<tr>
						<td class="time">11:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
					<tr>
						<td class="time">12:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
					<tr>
						<td class="time">13:00</td>
						<td class="lunch" colspan="5">Lunch</td>
					</tr>
					<tr>
						<td class="time">14:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
					<tr>
						<td class="time">15:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
					<tr>
						<td class="time">16:00</td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
						<td class="drop"></td>
					</tr>
				</tbody>
			</table>
		</div>