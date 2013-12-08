$(document).ready(function(){


	// Read and display data from JSON
	var timetable_data = {};
	var timetable_settings = {};

	$.getJSON("./timetable.json", function(json) {

		// Save data to JS object for later use
		timetable_data = json[0];
		timetable_settings = json[0].settings;

		var tr;
		for (var i = 1; i <= 13; i++) {
			tr = $('<tr style="height:'+parseInt(timetable_settings['class_duration'])+'px">');
			tr_mini_break = '<tr style="height:'+parseInt(timetable_settings['mini-break'])+'px"></tr>';
			tr_break = '<tr style="height:'+parseInt(timetable_settings['break'])+'px"><td colspan="6" class="break">BREAK</td></tr>';

			tr.append("<td>" + json[0].time[i] + "</td>");
			tr.append("<td>" + json[0].monday[i] + "</td>");
			tr.append("<td>" + json[0].tuesday[i] + "</td>");
			tr.append("<td>" + json[0].wednesday[i] + "</td>");
			tr.append("<td>" + json[0].thursday[i] + "</td>");
			tr.append("<td>" + json[0].friday[i] + "</td>");
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

	function timetableInit(){
		var today = new Date();

		// Determine the day
		var day = ((today.getDay() + 6) %7) + 1;
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
		
		timeline_fill = (from_start_hour/((time_end_hour-time_start_hour)+0.5))*100;
		timeline_fill_pixels = (691*timeline_fill)/100 + minutes;
		if(timeline_fill_pixels > 691){
			timeline_fill_pixels = 691;
		}


		$('.timeline-fill').css({'height': timeline_fill_pixels + 'px'});
		$('.timeline').css({'top': timeline_fill_pixels + 'px'});
	}

	


});