$(document).ready(function() {
	function CanvasDroppable() {
		$( ".canvas" ).droppable({
			scope: 'handles',
			accept: ".object",
			tolerance: "touch",
			drop: function( event, ui ) {
				
				task = $(ui.helper[0].children[1]).text();
				
				values = task.split('+');
				result = 0;
				for(i=0; i < values.length; i++){
					result += parseInt(values[i], 10);
					console.log('reslut: ' + result);
				}
				
				$(ui.helper[0].children[1]).text(result);
				$('.objects').append($(ui.helper).clone().addClass('ui-droppable result').css('opacity', '1'));
				$(ui.helper).remove();

				$('.result').draggable({
					handle: '.drag', 
					containment: ".canvas", 
					helper: 'none', 
					opacity: '1', 
					revert: false,
					scope: 'drag'
				}).draggable({
					handle: '.handle', 
					containment: ".canvas", 
					helper: "clone", 
					opacity: '0.7', 
					revert: true, 
					scope: 'handles'
				}).removeClass('result');

				objectDroppable();
				operatorDroppable();
				removeCanvasDroppable();
				removeObjectDroppable();
				// console.log(clone);
			}
		});
	}

	function removeCanvasDroppable() {
		$( ".canvas" ).droppable({accept: ".none"});
	}

	var holdTimerObject;
	function objectDroppable() {
		$( ".object" ).droppable({
			scope: 'handles',
			accept: ".object",
			tolerance: "touch",
			over: function( event, ui ) {
				if (operator == true){

					progress = '<span class="progress"></span>';
					$(this).append(progress);
					$('.progress').animate({'width': '100%'}, 500);

					value_2 = $(this.children[1]).text();

					holdTimerObject = setTimeout($.proxy( function(){
						operator = false;

						$('.progress').remove();						
						value = $(ui.helper[0].children[1]).text();
						value = value + value_2;
						$(ui.helper[0].children[1]).text(value);

						CanvasDroppable();
					}), 500);	
				}
			},
			out: function() {
				$('.progress').remove();
				clearTimeout(holdTimerObject);
			}
		});
	}

	function removeObjectDroppable() {
		$( ".object" ).droppable({accept: ".none"});		
	}
	
	var holdTimer;
	function operatorDroppable() {
		$( ".object-operator" ).droppable({
			scope: 'handles',
			accept: ".object",
			activeClass: "ui-state-active",
			hoverClass: "ui-state-hover",
			tolerance: "touch",
			over: function( event, ui) {
				if (operator == false) {	
					progress = '<span class="progress"></span>';
					$(this).append(progress);
					$('.progress').animate({'width': '90%'}, 500);
					holdTimer = window.setTimeout(function() {
						$('.progress').remove();
						operator = true;
						objectDroppable();
						value = $(ui.helper[0].children[1]).text();
						value = value + ' + ';
						$(ui.helper[0].children[1]).text(value);
					}, 500);			
				};
			},
			drop: function( event, ui ) {

			},
			out: function(){
				$('.progress').remove();
				clearTimeout(holdTimer);
			}
		});
	}

	function objectDraggable() {
		$('.object').draggable({
			handle: '.drag', 
			containment: ".canvas", 
			helper: 'none', 
			opacity: '1', 
			revert: false,
			scope: 'drag'
		});

		operatorDroppable();
	};	

	function handleDraggable() {
		objectDroppable();
		$('.object').draggable({
			handle: '.handle', 
			containment: ".canvas", 
			helper: "clone", 
			opacity: '0.7', 
			revert: true, 
			scope: 'handles',
			start: function() {
				operator = false;
			}
		});
	};


	function createObject(e) {
		topPos = e.pageY - 30;
		leftPos = e.pageX ;

		var source   = $("#te_object").html();
		var element = Handlebars.compile(source);
		var data = {
			object_id: object_id,
			topPos: topPos,
			leftPos: leftPos
		};

		$('.objects').append(element(data));
		$('#'+object_id+' .input-field').focus();
		objectDraggable();
		object_id++;
	}

	function preObject(e){
		topPos = e.pageY - 15;
		leftPos = e.pageX + 1;

		var source   = $("#te_pre-object").html();
		var element = Handlebars.compile(source);
		var data = {
			object_id: object_id,
			topPos: topPos,
			leftPos: leftPos
		};
		$('.objects').append(element(data));
		$('#pre'+object_id+' .pre-object').animate({'width': '94px'}, 400);
	}

	var object_id = 1;
	var pressTimer;
	var press2Timer;

	$(".canvas").mouseup(function(){ 
		clearTimeout(press2Timer);
		clearTimeout(pressTimer);
		$('#pre'+object_id).remove();
		return false;
	}).mousedown(function(e){
		press2Timer = window.setTimeout(function() {
			preObject(e);

		}, 100)
		pressTimer = window.setTimeout(function() {
			$('#pre'+object_id).stop().remove();
			createObject(e);
		}, 500)
		return true; 
	});

	$('body').on('mousedown','.drag', function(){
		$(this).addClass('grabbing');
	}).on('mouseup','.drag', function(){
		$(this).removeClass('grabbing');
	});

	$('body').on('mouseover','.handle', function(){
		handleDraggable();
	}).on('mouseout','.handle', function(){
		objectDraggable();
	});


});
