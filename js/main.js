$(document).ready(function(){

	$("[rel=tooltip]").tooltip({ placement: 'right'});


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
		var time = hour + ':' + minutes;

		//Timeline

		time_start_minutes = calculateTimeMinutes(timetable_settings['time_start']);
		time_end_minutes = time_start_minutes+timetable_settings['lesson_duration']*13+timetable_settings['minibreak_duration']*(13-timetable_settings['break_amount']-1)+timetable_settings['break_amount']*timetable_settings['break_duration'];

		current_time_minutes = calculateTimeMinutes(time);

		table_height = $('table.timetable tbody').height();

		timeline_fill = (((current_time_minutes-time_start_minutes)*100)/(time_end_minutes-time_start_minutes));

		timeline_fill_pixels = (table_height*timeline_fill)/100;

		if(timeline_fill_pixels > table_height){
			timeline_fill_pixels = table_height;
		}else if(timeline_fill_pixels < 0){
			timeline_fill_pixels = 0;
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


	function generateTimetable(element_class, type){


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

			printTimetable(element_class, type)
		}

		calculateTimeFrames();


		function printTimetable(){

			var tr;
			for (var i = 1; i <= 13; i++) {
				tr = $('<tr style="height:'+parseInt(timetable_settings['lesson_duration'])+'px">');
				tr_mini_break = '<tr style="height:'+parseInt(timetable_settings['minibreak_duration'])+'px"></tr>';
				tr_break = '<tr style="height:'+parseInt(timetable_settings['break_duration'])+'px"><td colspan="6" class="break">BREAK</td></tr>';

				if(type == "edit"){
					tr.append("<td>" + timetable_time[i] + "</td>");
					tr.append("<td><div class='drop'></div></td>");
					tr.append("<td><div class='drop'></div></td>");
					tr.append("<td><div class='drop'></div></td>");
					tr.append("<td><div class='drop'></div></td>");
					tr.append("<td><div class='drop'></div></td>");
					tr.append("</tr>");
				}else{
					tr.append("<td class='time'>" + timetable_time[i] + "</td>");
					tr.append("<td><div></div></td>");
					tr.append("<td><div></div></td>");
					tr.append("<td><div></div></td>");
					tr.append("<td><div></div></td>");
					tr.append("<td><div></div></td>");
					tr.append("</tr>");
				}


				$('div.'+element_class+' tbody').append(tr);

				if(i%timetable_settings['break_lessons']==0 && i<(timetable_settings['break_amount']*timetable_settings['break_lessons'])+1) {
					$('div.'+element_class+' tbody').append(tr_break);
				}else if(i<13){
					$('div.'+element_class+' tbody').append(tr_mini_break);
				}
			}

			if(type == "edit"){
				initDraggable();
			}


		};

	};

	if(generate_edit_timetable){
		generateTimetable('timetable_construct', 'edit');
	}else if(generate_timetable){
		generateTimetable('timetable_container', 'display');
	}

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



	function fillTimetable(timetable_data, shift,  filter){
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
			for (var i = 3; i <= 28; i=i+2) {
				if(timetable_data[day_of_week][index] != ""){

					var subject_ids = timetable_data[day_of_week][index].split(';');
					for(k=0; k<subject_ids.length;k++){
						var hl_sl = "";
						subject_id = subject_ids[k];
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
						lunch = "";
						if(subject_id == 42){
							lunch = "class='lunch'";
						}

						var subject_name = getSubjectName(subject_id);
						var subject_name_short = getSubjectNameShort(subject_id);


						subject_button = "<span "+lunch+" >"+subject_name_short+" "+hl_sl+"</span>";

						if(filter == true){
							if (filter_subjects.indexOf(subject_id) > -1 || subject_id == 42){
								$('.timetable_container tr:nth-child('+i+') td:nth-child('+c+')>div').append(subject_button);
							}else{

							}

						}else{
							$('.timetable_container tr:nth-child('+i+') td:nth-child('+c+')>div').append(subject_button);
						}

					}

				}
				index++;
			};
		};

		timetableInit();
	}

	function clearTable(){
		for(var c = 2; c <= 6; c++){
			for (var i = 3; i <= 28; i=i+2) {
				$('.timetable_container tr:nth-child('+i+') td:nth-child('+c+')>div').html("");
			}
		};
	}

	if(fill_edit_timetable){
		fillEditTimetable(first_shift_timetable, 'first');
		fillEditTimetable(second_shift_timetable, 'second');
	}else if(fill_timetable){
		// TO DO calc shift

		fillTimetable(first_shift_timetable, 'first', true);
	}


	$('.hide_shift').click(function(){
		if($('.first_shift_container').hasClass('hidden')){
			$('.first_shift_container').slideUp(200).removeClass('hidden');
		}else{
			$('.first_shift_container').slideDown(200).addClass('hidden');
		}
	});

	// Filter Subjects

	var filter = JSON.parse(localStorage.getItem( 'filter' ));



	$('#filter_subjects:checkbox').click(function() {
		clearTable();
	    if (this.checked) {
	    	fillTimetable(first_shift_timetable, 'first', true);
	    	$('.timetable_container').addClass('filtered');

			filter = "filter";
			localStorage.setItem( 'filter', JSON.stringify(filter) );
	    }else{
			fillTimetable(first_shift_timetable, 'first', false);
			$('.timetable_container').removeClass('filtered');
			filter = "";
			localStorage.setItem( 'filter', JSON.stringify(filter) );

	    }
	});
	console.log(filter);
	if(filter != "filter"){
		$('#filter_subjects:checkbox').click();
	}

});