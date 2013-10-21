$(document).ready(function() {

	function createObject(e) {
		topPos = e.pageY - 10;
		leftPos = e.pageX - 5;
		console.log(topPos +',' + leftPos);

		element = '<span class="object" id="'+object_id+'" style="top:'+topPos+'px; left:'+ leftPos+'px" contenteditable="true"></span>'

		$('.objects').append(element);
		$('#'+object_id).focus();
		object_id++;
	}

	object_id = 1;

	var timeoutId = 0;

	var pressTimer

	$(".canvas").mouseup(function(){
	  clearTimeout(pressTimer)
	  return true;

	}).mousedown(function(e){
		pressTimer = window.setTimeout(function() {
			createObject(e);
		}, 500)
		return true; 
	});



});
