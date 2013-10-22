$(document).ready(function() {

	function createObject(e) {
		topPos = e.pageY - 10;
		leftPos = e.pageX + 1;
		console.log(topPos +',' + leftPos);

		element = '<span class="object" id="'+object_id+'" style="top:'+topPos+'px; left:'+ leftPos+'px" contenteditable="true"></span>'

		$('.objects').append(element);
		$('#'+object_id).focus();
		object_id++;
	}

	function preObject(e){
		topPos = e.pageY - 10;
		leftPos = e.pageX + 1;
		console.log(topPos +',' + leftPos);

		element = '<span class="pre-object-container" id="pre'+object_id+'" style="top:'+topPos+'px; left:'+ leftPos+'px"><span class="pre-object"></span></span>'

		$('.objects').append(element);
		$('#pre'+object_id+' .pre-object').animate({'width': '80px'}, 400);
	}

	object_id = 1;

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





});
