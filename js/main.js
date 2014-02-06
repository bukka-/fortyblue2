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
	if(typeof generate_edit_timetable != 'undefined' || typeof generate_timetable != 'undefined'){
		if(typeof generate_edit_timetable != 'undefined'){
			generateTimetable('timetable_construct', 'edit');
		}else if(typeof generate_timetable != 'undefined'){
			generateTimetable('timetable_container', 'display');
		}
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
		initDraggable();

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
							hl_sl = "SL-HL";
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
							if(hl_sl == "" || hl_sl == "SL-HL"){
								if (filter_subjects.indexOf(subject_id+"h") > -1 || filter_subjects.indexOf(subject_id+"s") > -1 || filter_subjects.indexOf(subject_id) > -1 || subject_id == 42){
									$('.timetable_container tr:nth-child('+i+') td:nth-child('+c+')>div').append(subject_button);
								}
							}else if(hl_sl == "SL"){
								if (filter_subjects.indexOf(subject_id+"s") > -1 || subject_id == 42){
									$('.timetable_container tr:nth-child('+i+') td:nth-child('+c+')>div').append(subject_button);
								}
							}else if(hl_sl == "HL"){
								if (filter_subjects.indexOf(subject_id+"h") > -1 || subject_id == 42){
									$('.timetable_container tr:nth-child('+i+') td:nth-child('+c+')>div').append(subject_button);
								}
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


	function getWeekNumber(d) {
	    d = new Date(+d);
	    d.setHours(0,0,0);
	    d.setDate(d.getDate() + 4 - (d.getDay()||7));
	    var yearStart = new Date(d.getFullYear(),0,1);
	    var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7)
	    return weekNo;
	}

	function printTimetableByShift(filter_check){
		if(typeof generate_edit_timetable != 'undefined'){
			fillEditTimetable(first_shift_timetable, 'first');
			fillEditTimetable(second_shift_timetable, 'second');
		}else if(typeof generate_timetable != 'undefined'){

			if(shift_start == 'first'){
				shift_check = 1;
			}else{
				shift_check = 0;
			}

			week_number = getWeekNumber(new Date());

			if( week_number % 2 == shift_check){
				fillTimetable(first_shift_timetable, 'first', filter_check);
			}else{
				fillTimetable(second_shift_timetable, 'second', filter_check);
			}
			setTimeout(function () {timetableInit();}, 50); 
			// console.log(week_number, week_number % 2, shift_start);
		}
	}

	printTimetableByShift(true);



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
	    	printTimetableByShift(true)
	    	$('.timetable_container').addClass('filtered');

			filter = "filter";
			localStorage.setItem( 'filter', JSON.stringify(filter) );
	    }else{
			printTimetableByShift(false);
			$('.timetable_container').removeClass('filtered');
			filter = "";
			localStorage.setItem( 'filter', JSON.stringify(filter) );

	    }
	});

	if(filter != "filter"){
		$('#filter_subjects:checkbox').click();
	}

	// Timetable Events

	function initTimetableEvents(){

		var events = {};


		$.ajax({ 
			url:"/feed/CalendarEvents.php",
			data: {getevents: true, from: '1970-01-02', to: '2015-02-02'},
			type: 'post',
			success: function(output) {
				events = JSON.parse(output);

				$.each(events, function(index, value) { 
					if (events[index].public == true) {
						events[index].start = new Date(events[index].datetime_start);
						events[index].end = new Date(events[index].datetime_end);
					}else{
						events[index] = '';
					}
				});


				insertTimetableEvents();
			}
		});

		function insertTimetableEvents(){


			function getLastMonday(d) {
				d = new Date(d);
				var day = d.getDay(),
					diff = d.getDate() - day + (day == 0 ? -6:1); // adjust when day is sunday
				return new Date(d.setDate(diff));
			}

			function getNextFriday(date) {

			    var resultDate = new Date(date.getTime());

			    resultDate.setDate(date.getDate() + (7 + 4 - date.getDay()) % 7);

			    return resultDate;
			}

			$.each(events, function(index, value) {
				if (events[index].type == "no_classes") {
					var el_no_classes = "<span class='no_classes'></span>";

					console.log(getNextFriday(new Date()));

					if(new Date(events[index].start) < getLastMonday(new Date().setHours(0,0,0,0))){
						console.log(new Date(events[index].start));
						event_start = 1;
					}

					console.log(getLastMonday(new Date().setHours(0,0,0,0)));

					
				}
			});
		}
	}

	initTimetableEvents();

	// Grade Table

	$('#add_grade').click(function() {
		$('#add_grade_controls').css('display', 'table-row');
		$(this).css('display', 'none');
	});

	$('.btn').button();


	function saveUserGrade(content, user_id, subject_id, button){
		$.ajax({
			url:"/feed/UserSubject.php",
			type:"post",
			data:{grade_data:content ,user_id:user_id, subject_id: subject_id},
			success:function(result){
				button.button('reset');
				grade_date = moment(content.grade_date).format('MMMM Do');
				last_row_id = parseInt($('.grade_list tr:nth-last-child(3)').attr('id')) + 1;

				element = '<tr id="'+last_row_id+'"><td>'+grade_date+'</td><td>'+content.grade_task+'</td><td>'+content.grade_value+'</td><td class="align-middle"><span class="btn btn-default glyphicon glyphicon-edit" title="Edit" data-rowid="'+last_row_id+'" id="edit_row"></span></td></tr>';

				$(element).insertAfter('.grade_list tr:nth-last-child(3)')

				$('#grade_date').val('');
				$('#grade_task').val('');
				$('#grade_value').val('');

			},
			error: function(e){
				console.log(e);
			}
		});
	}

	$('#submit_grade').click(function() {

		$(this).button('loading');

		var grade_data = {};

		grade_data.grade_date = $('#grade_date').val();
		grade_data.grade_task = $('#grade_task').val();
		grade_data.grade_value = $('#grade_value').val();

		saveUserGrade(grade_data, user_id, subject_id, $(this));
	});


	function editUserGrade(content, user_id, subject_id, button){
		$.ajax({
			url:"/feed/UserSubject.php",
			type:"post",
			data:{grade_edit_data:content ,user_id:user_id, subject_id: subject_id},
			success:function(result){
				console.log(result);
			},
			error: function(e){
				console.log(e);
			}
		});
	}



	$(document).on('click', ".edit_grade", function() {
		if($(this).hasClass('save_grade')){
			return false;
		}
		edit_row_id = $(this).attr('data-rowid');

		$(this).removeClass('edit_grade').addClass('save_grade').html('<span title="Save" class="btn btn-default glyphicon glyphicon-check"></span>');

		row_grade_date = $("#"+edit_row_id+" #row_grade_date").attr('title');
		row_grade_date = moment(row_grade_date, 'MMMM DD yyyy').format('YYYY-MM-DD');
		$("#"+edit_row_id+" #row_grade_date").html('<input id="edit_grade_date" class="form-control full-width" type="date" placeholder="mm/dd/yyyy" value="'+row_grade_date+'" />');

		row_grade_task = $("#"+edit_row_id+" #row_grade_task").text();
		$("#"+edit_row_id+" #row_grade_task").html('<input value="'+row_grade_task+'"  id="edit_grade_task" class="form-control full-width" type="text" placeholder="Task"/>');

		row_grade_value = $("#"+edit_row_id+" #row_grade_value").text();
		$("#"+edit_row_id+" #row_grade_value").html('<input name="" value="'+row_grade_value+'" id="edit_grade_value" class="form-control full-width" type="number" min="1" max="7" placeholder="Grade"/>');

		grade_edit_data = {
			grade_date : row_grade_date,
			grade_task : row_grade_task,
			grade_value : row_grade_value,
		}

	});


	$(document).on('click', ".save_grade", function() {
		var grade_data = {};

		edit_row_id = $(this).attr('data-rowid');

		grade_data.grade_id = $("#"+edit_row_id).attr('data-gradeid');
		grade_data.grade_date = $("#"+edit_row_id+" #row_grade_date input").val();
		grade_data.grade_task = $("#"+edit_row_id+" #row_grade_task input").val();
		grade_data.grade_value = $("#"+edit_row_id+" #row_grade_value input").val();

		if(grade_data.grade_date == grade_edit_data.grade_date && grade_data.grade_task == grade_edit_data.grade_task && grade_data.grade_value == grade_edit_data.grade_value){

			grade_edit_data.grade_date = moment(grade_edit_data.grade_date, 'YYYY-MM-DD').format('MMMM Do ');
			$("#"+edit_row_id+" #row_grade_date").text(grade_edit_data.grade_date);
			$("#"+edit_row_id+" #row_grade_task").text(grade_edit_data.grade_task);
			$("#"+edit_row_id+" #row_grade_value").text(grade_edit_data.grade_value);

			$(this).removeClass('save_grade').addClass('edit_grade').html('<span title="Edit" class="btn btn-default glyphicon glyphicon-edit"></span>');
		}else{
			editUserGrade(grade_data, user_id, subject_id, $(this));

			grade_data.grade_date = moment(grade_data.grade_date, 'YYYY-MM-DD').format('MMMM Do ');
			$("#"+edit_row_id+" #row_grade_date").text(grade_data.grade_date);
			$("#"+edit_row_id+" #row_grade_task").text(grade_data.grade_task);
			$("#"+edit_row_id+" #row_grade_value").text(grade_data.grade_value);

			$(this).removeClass('save_grade').addClass('edit_grade').html('<span title="Edit" class="btn btn-default glyphicon glyphicon-edit"></span>');
		}

	});


	// Calendar


	function reInitCalendar(filter) {

		var events = {};


		$.ajax({ 
			url:"/feed/CalendarEvents.php",
			data: {getevents: true, from: '1970-01-02', to: '2015-02-02'},
			type: 'post',
			success: function(output) {
				events = JSON.parse(output);

				$.each(events, function(index, value) { 
					if(events[index].calendar == false){
						events[index] = '';
					}else{
						if (events[index].public == true) {
							events[index].start = new Date(events[index].datetime_start)/ 1000 + '000';
							events[index].end = new Date(events[index].datetime_end)/ 1000 + '000';
						};
						events[index].time_start = events[index].time_start.substring(0, events[index].time_start.length - 3)
						if (filter == true){
							if (filter_subjects.indexOf(parseInt(events[index].subject_id)) > -1){
							}else{
								events[index] = '';

							}
						}
					}

				});

				initCalendar();
			}
		});

		
			function initCalendar(){

				var options = {
					events_source: function(){
					    return events;
					},
					view: 'month',
					tmpl_path: './js/vendor/tmpls/',
					tmpl_cache: false, 
					first_day: 1,
					onAfterEventsLoad: function(events) {
						if(!events) {
							return;
						}
						var list = $('#eventlist');
						list.html('');

						$.each(events, function(key, val) {
							$(document.createElement('li'))
								.html('<a href="' + val.url + '">' + val.title + '</a>')
								.appendTo(list);
						});
					},
					onAfterViewLoad: function(view) {
						$('.current_month').text(this.getTitle());
						$('.btn-group button').removeClass('active');
						$('button[data-calendar-view="' + view + '"]').addClass('active');
					},
					classes: {
						months: {
							general: 'label'
						}
					}
				};

				calendar = $('#calendar').calendar(options);

				$('.events-list').each(function(i, obj) {
					event_ammount = $(this).children('.event').length;
					more_events = event_ammount - 2;

					if (more_events > 0) {
						$("<span class='badge badge-success more-events' title='+"+more_events+" more events.'>+"+more_events+"<span class='caret'></span></span>").insertAfter($(this));
					};
				});
			}
		}

		// Filter Events

		reInitCalendar(true);
		var filter_events = JSON.parse(localStorage.getItem( 'filter_events' ));

		$('#filter_events:checkbox').click(function() {
		    if (this.checked) {
				filter = true;
				localStorage.setItem( 'filter_events', JSON.stringify(filter) );
				reInitCalendar(filter)
		    }else{
				filter = false;
				localStorage.setItem( 'filter_events', JSON.stringify(filter) );
				reInitCalendar(filter)
		    }
		});

		if(filter_events != true){
			$('#filter_events:checkbox').click();
		}


		$('.btn-group button[data-calendar-nav]').each(function() {
			var $this = $(this);
			$this.click(function() {
				calendar.navigate($this.data('calendar-nav'));
			});
		});

		$('.btn-group button[data-calendar-view]').each(function() {
			var $this = $(this);
			$this.click(function() {
				calendar.view($this.data('calendar-view'));
			});
		});

		$('#first_day').change(function(){
			var value = $(this).val();
			value = value.length ? parseInt(value) : null;
			calendar.setOptions({first_day: value});
			calendar.view();
		});

		$('#language').change(function(){
			calendar.setLanguage($(this).val());
			calendar.view();
		});

		$('#events-in-modal').change(function(){
			var val = $(this).is(':checked') ? $(this).val() : null;
			calendar.setOptions({modal: val});
		});

});