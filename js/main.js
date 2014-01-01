$(document).ready(function(){

	$("[rel=tooltip]").tooltip({ placement: 'right'});


	// Read and display data from JSON
	var timetable_data = {};
	var subject_ids = {};

	$.getJSON("./data/subjects.json", function(json_data) {
		subject_ids = json_data[0];
		getTimetable();

	});

	function getTimetable(){
		$.getJSON("./data/timetable.json", function(json) {

			// Save data to JS object for later use
			timetable_data = json[0];
			// timetable_settings = json[0].settings;

			var tr;
			for (var i = 1; i <= 13; i++) {
				tr = $('<tr style="height:'+parseInt(timetable_settings['class_duration'])+'px">');
				tr_mini_break = '<tr style="height:'+parseInt(timetable_settings['mini-break'])+'px"></tr>';
				tr_break = '<tr style="height:'+parseInt(timetable_settings['break'])+'px"><td colspan="6" class="break">BREAK</td></tr>';


				subjects = "";
				k = false;
				s = false;
				n = 0;
				function extractSubjects(subject_list){
					n++;
					if(subject_list == ""){

						subjects = "";

					}else if(subject_list.indexOf(";") != -1 ){

						subject_one = subject_list.split(';')[0];
						other_subjects = subject_list.substr(subject_list.indexOf(';') + 1);


						s = true;


						if(subject_one.indexOf("x") != -1){
							hl_sl = " HL/SL";
							subject_one = subject_one.substring(0, subject_one.length - 1);
						}else if(subject_one.indexOf("h") != -1){
							hl_sl = " HL";
							subject_one = subject_one.substring(0, subject_one.length - 1);
						}else{
							hl_sl = "";
						}

						if(n==1){
							subjects +=  subject_ids[subject_one] + hl_sl;
						}else{
							subjects += " | " + subject_ids[subject_one] + hl_sl;
						}

						extractSubjects(other_subjects);
					}else{
							if(subject_list.indexOf("x") != -1){
								hl_sl = " HL/SL";
								subject_list = subject_list.substring(0, subject_list.length - 1);
							}else if(subject_list.indexOf("h") != -1){
								hl_sl = " HL";
								subject_list = subject_list.substring(0, subject_list.length - 1);
							}else{
								hl_sl = "";
							}

							if(s == true){
								subjects += " | " + subject_ids[subject_list] + hl_sl;			
							}else{
								subjects += subject_ids[subject_list] + hl_sl;
							}

						s = false;
					}
					n=0;
					return subjects
				}


				tr.append("<td>" + json[0].time[i] + "</td>");
				tr.append("<td>" + extractSubjects(json[0].monday[i].first) + "</td>");
				subjects = "";
				tr.append("<td>" + extractSubjects(json[0].tuesday[i].first) + "</td>");
				subjects = "";
				tr.append("<td>" + extractSubjects(json[0].wednesday[i].first) + "</td>");
				subjects = "";
				tr.append("<td>" + extractSubjects(json[0].thursday[i].first) + "</td>");
				subjects = "";
				tr.append("<td>" + extractSubjects(json[0].friday[i].first) + "</td>");
				tr.append("</tr>");

				$('.timetable_container tbody').append(tr);

				if(i%3==0 && i<10){
					$('.timetable_container tbody').append(tr_break);
				}else if(i<13){
					$('.timetable_container tbody').append(tr_mini_break);
				}
			}

			// Initialize timetable after data is loaded
			timetableInit();
		});	
	}

	function timetableInit(){
		var today = new Date();

		// Determine the day
		var day = ((today.getDay() + 6) %7)+2;
		var day_indicator = '<span class="today"></span>';
		$('.timetable_container thead>tr>th:nth-child('+day+')').addClass('today-select').append(day_indicator);

		// Datermine the time
		var seconds = today.getSeconds();
		var minutes = today.getMinutes();
		var hour = today.getHours();
		var time = hour + ':' + minutes + ':' + seconds;

		//Timeline

		time_start = timetable_data.time[1].split('-')[0];
		time_end = timetable_data.time[13].split('-')[1];

		time_start_hour = parseInt(time_start.split(':')[0]);
		time_start_minutes = parseInt(time_start.split(':')[1]);

		time_end_hour = parseInt(time_end.split(':')[0]);
		time_end_minutes = parseInt(time_end.split(':')[1]);

		from_start_hour = hour - time_start_hour;
		from_start_minutes = minutes - time_start_minutes;
		
		table_height = $('table.timetable tbody').height();

		timeline_fill = (from_start_hour/((time_end_hour-time_start_hour)+0.5))*100;
		timeline_fill_pixels = (table_height*timeline_fill)/100 + minutes;
		if(timeline_fill_pixels > table_height){
			timeline_fill_pixels = table_height;
		}


		$('.timeline-fill').css({'height': timeline_fill_pixels + 'px'});
		$('.timeline').css({'top': timeline_fill_pixels + 'px'});

		// Re-init the function each minute
    	setTimeout(timetableInit, 60000);
	}

	$('#log-in').click(function(){
		if($('.user_log_in').hasClass('open')){
			$('.user_log_in').slideUp(200).removeClass('open');
		}else{
			$('.user_log_in').slideDown(200).addClass('open');
		}
	});


	function initDraggable() {
			var helper_id = "0";

			$('.timetable_construct .subject').draggable({
				revert:true,
				helper:'clone',
				start: function( e, ui ) {
					helper_id = $(this).attr("id");
					$('.timetable_errors').css('display', 'none').text("");
				}
			});

			$('.timetable div.drop').droppable({
				over:function(){
					$(this).addClass('over');
				},
				out:function(){
					$(this).removeClass('over');
				},
				drop:function(e,source){
					helper = source.helper[0];
					source = source.draggable[0];
					helper_text = $(helper).text();

					console.log(helper_id);

					if($(this).find('.btn').length > 3){
						$('.timetable_errors').css('display', 'block').text("That cell is already full.");
					}else{
						var c = $(source).clone().addClass('assigned').attr("id", helper_id).text(helper_text);
						if($(source).hasClass('assigned')){
							$(source).remove();
						}
						$(this).append(c);

						c.draggable({
							revert:true,
							helper:'clone'
						});
					}




					$(helper).remove();
				}
			});

			$('.trash').droppable({
				drop:function(e,source){
					helper = source.helper[0];
					source = source.draggable[0];
					if ($(source).hasClass('assigned')){
						$(source).remove();

					}
					 $(helper).remove();
				}
			});

			$('.standard_level').droppable({
				over:function(e,source){
					helper = source.helper[0];

					if(helper_id.slice(-1) == "s" || helper_id.slice(-1) == "x"){
					}else{

						source = source.draggable[0];


						if(helper_id.slice(-1) == "s" || helper_id.slice(-1) == "x"){

						}else if(helper_id.slice(-1) == "h"){
							helper_id = helper_id.slice(0, - 1) + "x";
						}else{
							helper_id = helper_id + "s";
						}


						helper_text = $(helper).text();
						$(helper).attr("id", helper_id);
						$(helper).text(helper_text + " SL");
						$(helper).addClass("standard");
					}	
				}
			});

			$('.high_level').droppable({
				over:function(e,source){
					helper = source.helper[0];

					if(helper_id.slice(-1) == "h" || helper_id.slice(-1) == "x"){
					}else{
						source = source.draggable[0];

						if(helper_id.slice(-1) == "h" || helper_id.slice(-1) == "x"){

						}else if(helper_id.slice(-1) == "s"){
							helper_id = helper_id.slice(0, - 1) + "x";
						}else{
							helper_id = helper_id + "h";
						}

						helper_text = $(helper).text();
						$(helper).attr("id", helper_id);
						$(helper).text(helper_text + " HL");
						$(helper).addClass("high");
					}	
				}
			});
	}



	function generateTimetable(){


		function calculateTimeMinutes(time){
			var hm = time;
			var a = hm.split(':');
			time_minutes =  (+a[0]) * 60 + (+a[1]);

			return time_minutes;
		}

		function calculateTimeHours(minutes){
			var hours = Math.floor( minutes / 60);
			if(hours < 10){
				hours = "0" + hours;
			}
			var minutes = minutes % 60;
			if(minutes < 10){
				minutes = "0" + minutes;
			}
			time = hours + ":" + minutes;
			return time;
		}

		var timetable_time = {};

		function calculateTimeFrames(){
			var time_start_minutes = parseInt(calculateTimeMinutes(timetable_settings['time_start']));
			var lesson_duration_minutes = parseInt(timetable_settings['lesson_duration']);
			var minibreak_duration_minutes = parseInt(timetable_settings['minibreak_duration']);
			var break_duration_minutes = parseInt(timetable_settings['break_duration']);
			var current_time_frame_minutes = parseInt(time_start_minutes);
			var break_lessons = parseInt(timetable_settings['break_lessons']);
			var break_amount = parseInt(timetable_settings['break_amount']);

			for (var i = 1; i <= 13; i++) {
				timetable_timeframe_start_minutes = current_time_frame_minutes;
				timetable_timeframe_start = calculateTimeHours(timetable_timeframe_start_minutes);

				timetable_timeframe_end_minutes = current_time_frame_minutes + lesson_duration_minutes;
				timetable_timeframe_end = calculateTimeHours(timetable_timeframe_end_minutes);
				current_time_frame_minutes += lesson_duration_minutes;

				if(i%break_lessons == 0 && i < (break_amount*break_lessons)+1){
					current_time_frame_minutes += break_duration_minutes;
				}else{
					current_time_frame_minutes += minibreak_duration_minutes;
				}

				timetable_time[i] = timetable_timeframe_start + " - " +  timetable_timeframe_end;
			}

			printTimetable()
		}

		calculateTimeFrames();


		function printTimetable(){

			var tr;
			for (var i = 1; i <= 13; i++) {
				tr = $('<tr style="height:'+parseInt(timetable_settings['lesson_duration'])+'px">');
				tr_mini_break = '<tr style="height:'+parseInt(timetable_settings['minibreak_duration'])+'px"></tr>';
				tr_break = '<tr style="height:'+parseInt(timetable_settings['break_duration'])+'px"><td colspan="6" class="break">BREAK</td></tr>';


				tr.append("<td>" + timetable_time[i] + "</td>");
				tr.append("<td><div class='drop'></div></td>");
				tr.append("<td><div class='drop'></div></td>");
				tr.append("<td><div class='drop'></div></td>");
				tr.append("<td><div class='drop'></div></td>");
				tr.append("<td><div class='drop'></div></td>");
				tr.append("</tr>");

				$('.timetable_construct tbody').append(tr);

				if(i%timetable_settings['break_lessons']==0 && i<(timetable_settings['break_amount']*timetable_settings['break_lessons'])+1) {
					$('.timetable_construct tbody').append(tr_break);
				}else if(i<13){
					$('.timetable_construct tbody').append(tr_mini_break);
				}
			}

			initDraggable();

		};

	};

	function getTableData(shift){
			var timetable_content = {};

			for(var c = 2; c <= 6; c++){
				if(c == 2){
					day_of_week = "1.monday";
				}else if(c == 3){
					day_of_week = "2.tuesday";
				}else if(c == 4){
					day_of_week = "3.wednesday";
				}else if(c == 5){
					day_of_week = "4.thursday";
				}else if(c == 6){
					day_of_week = "5.friday";
				}
				timetable_content[day_of_week] = {};
				var index = 1;
				for (var i = 1; i <= 26; i=i+2) {
					var ids = $('.timetable_construct .'+shift+'_shift tr:nth-child('+i+') td:nth-child('+c+')>div .btn').map(function(i) {
					    return this.id;
					});

					ids = ids.get().join(';')

					timetable_content[day_of_week][index] = ids;
					index++;
				};
			};

		return timetable_content;
	}


	function saveTimetableContent(content, shift){
		$.ajax({
			url:"/feed/TimetableContent.php",
			type:"post",
			data:{value:JSON.stringify(content),timetable_id:timetable_id, timetable_shift:shift},
			success:function(result){
			 	$('.timetable_success').css('display', 'block').text(result);
			},
			error: function(e){
				console.log(e);
			}
		});

	}

	$('.timetable_save_shift').click(function(){
		var shift = $(this).attr("data-shift");
		if(shift == "first"){
			var first_shift_timetable = getTableData('first');
			saveTimetableContent(first_shift_timetable, shift);
		}else{
			var second_shift_timetable = getTableData('second');
			saveTimetableContent(second_shift_timetable, shift);
		}
	});

	if(generate_timetable){
		generateTimetable();
	}

	function getSubjectNameShort(id){
		var matches = $.grep(subject_labels, function (elt){
			return elt.subject_id === id;
		});
		return matches[0]['subject_name_short'];
	}

	function getSubjectName(id){
		var matches = $.grep(subject_labels, function (elt){
			return elt.subject_id === id;
		});
		return matches[0]['subject_name'];
	}

	function fillEditTimetable(timetable_data, shift){
		var timetable_data = timetable_data;


		for(var c = 2; c <= 6; c++){
			if(c == 2){
				day_of_week = "1.monday";
			}else if(c == 3){
				day_of_week = "2.tuesday";
			}else if(c == 4){
				day_of_week = "3.wednesday";
			}else if(c == 5){
				day_of_week = "4.thursday";
			}else if(c == 6){
				day_of_week = "5.friday";
			}
			index = 1;
			for (var i = 1; i <= 26; i=i+2) {
				if(timetable_data[day_of_week][index] != ""){

					var subject_ids = timetable_data[day_of_week][index].split(';');
					for(k=0; k<subject_ids.length;k++){
						var hl_sl = "";
						if(subject_ids[k].indexOf("x") != -1){
							subject_id = subject_ids[k].slice(0, - 1);
							hl_sl = "SL HL";
						}else if(subject_ids[k].indexOf("h") != -1 ){
							subject_id = subject_ids[k].slice(0, - 1);
							hl_sl = "HL";
						}else if(subject_ids[k].indexOf("s") != -1) {
							subject_id = subject_ids[k].slice(0, - 1);
							hl_sl = "SL";
						}

						if(hl_sl == ""){
							var subject_name = getSubjectName(subject_ids[k]);
							var subject_name_short = getSubjectNameShort(subject_ids[k]);
						}else{
							var subject_name = getSubjectName(subject_id);
							var subject_name_short = getSubjectNameShort(subject_id);
						}

						subject_button = "<div class='btn btn-default subject ui-draggable assigned' id='"+subject_ids[k]+"' title='"+subject_name+"'>"+subject_name_short+" "+hl_sl+"</div>";

						$('.timetable_construct .'+shift+'_shift tr:nth-child('+i+') td:nth-child('+c+')>div').append(subject_button);	
					}

				}
				index++;
			};
		};
		initDraggable()
	}

	if(fill_edit_timetable){
		fillEditTimetable(first_shift_timetable, 'first');
		fillEditTimetable(second_shift_timetable, 'second');
	}
});