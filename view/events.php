

<h2>Events</h2>


<?


$events = $event->getEvents();

if($events){

	$upcoming_events = '';
	$past_events = '';



	for($i=0; $i<count($events); $i++){
		$event = $events[$i];
		if($event->public){
			$event_datetime = strtotime($event->datetime_start.$event->time_start);

			if($event_datetime > strtotime('now') && $event_datetime < strtotime("+3 day")){

				$subject_pair = false;

				$labels = '<span class="label label-success">NEW</span>
				 ';

				if($event->type == 'mock_exam'){
					$labels .= '<span class="label label-warning" title="Mock Exam">EXAM</span>';
				}


				$days_until = floor(($event_datetime - strtotime('now'))/60/60/24);
				$hours_until = floor(($event_datetime - strtotime('now'))/60/60);

				if($hours_until < 2){
					$days_until = 'Less than an hour until the event.';
				}else if($days_until < 1){
					$days_until = $hours_until. ' hours until the event.';
				}else if($days_until == 1){
					$days_until = $days_until.' day until the event.';
				}else{
					$days_until = $days_until.' days until the event.';
				}

				$full_date = date_create_from_format('Y-m-d', $event->datetime_start);
				$full_date = $full_date->format('F jS Y');

				if (isset($event->subject_id)) {
					if($event->subject_id == $events[$i+1]->subject_id && $event->datetime_start == $events[$i+1]->datetime_start){
						$upcoming_events .= '<li class="list-group-item">'.$labels.' '.$event->title.' + '.$events[$i+1]->title.' - <span rel="tooltip" title="'.$full_date.'">'.$days_until.'</span></li>';
						$i++;
						$subject_pair = true;
					}
				}
				if(!$subject_pair){
					$upcoming_events .= '<li class="list-group-item">'.$labels.' '.$event->title.' - <span rel="tooltip" title="'.$full_date.'">'.$days_until.'</span></li>';
				}
			}else if($event_datetime < strtotime('now') && $event_datetime > strtotime("-3 day")){
				$subject_pair = false;

				$labels = '<span class="label label-default">OLD</span>
				 ';

				if($event->type == 'mock_exam'){
					$labels .= '<span class="label label-warning" title="Mock Exam">EXAM</span>';
				}


				$days_until = floor((strtotime('now') - $event_datetime)/60/60/24);
				$hours_until = floor((strtotime('now') - $event_datetime)/60/60);

				if($hours_until < 2){
					$days_until = 'Less than an hour ago.';
				}else if($days_until < 1){
					$days_until = floor((strtotime('now') - $event_datetime)/60/60). ' hours ago.';
				}else if($days_until == 1){
					$days_until = $days_until.' day ago.';
				}else{
					$days_until = $days_until.' days ago.';
				}

				$full_date = date_create_from_format('Y-m-d', $event->datetime_start);
				$full_date = $full_date->format('F jS Y');

				if (isset($event->subject_id)) {
					if($event->subject_id == $events[$i+1]->subject_id && $event->datetime_start == $events[$i+1]->datetime_start){
						$past_events .= '<li class="list-group-item">'.$labels.' '.$event->title.' + '.$events[$i+1]->title.' - <span rel="tooltip" title="'.$full_date.'">'.$days_until.'</span></li>';
						$i++;
						$subject_pair = true;
					}
				}
				if(!$subject_pair){
					$past_events .= '<li class="list-group-item">'.$labels.' '.$event->title.' - <span rel="tooltip" title="'.$full_date.'">'.$days_until.'</span></li>';
				}
			}
			
		}
	}

?>

	<div class="panel panel-default">
		<div class="panel-heading">Upcoming events</div>
		<ul class="list-group">
			<? echo $upcoming_events; ?>
		</ul>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">Past events</div>
		<ul class="list-group">
			<? echo $past_events; ?>
		</ul>
	</div>

<?

}else{
	echo '<div class="alert alert-danger">No events found.</div>';
}


?>
