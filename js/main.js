$(document).ready(function() {

	function Draggable() {
		$('.object').draggable({handle: '.drag', containment: ".canvas", helper: 'none', opacity: '1', revert: false});
	};	

	function handleDraggable() {
		$('.object').draggable({handle: '.handle', containment: ".canvas", helper: "clone", opacity: '0.7', revert: true});
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
		Draggable();
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
		Draggable();
	});

	$('body').on('mouseover','.drag', function(){
		Draggable();
	});


});
