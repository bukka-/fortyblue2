$(document).ready(function(){

	$("[rel=tooltip]").tooltip({ placement: 'right'});


	// Read and display data from JSON
	var timetable_data = {};
	var timetable_settings = {};
	var subject_ids = {};

	$.getJSON("./data/subjects.json", function(json_data) {
		subject_ids = json_data[0];
		getTimetable();

	});

	function getTimetable(){
		$.getJSON("./data/timetable.json", function(json) {

			// Save data to JS object for later use
			timetable_data = json[0];
			timetable_settings = json[0].settings;

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
		console.log(day);
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

});