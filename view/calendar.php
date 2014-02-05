	
	<?
		$subjects = $subject->getSubjects();

		$filter_subjects = $login->getFilterSubjects($_SESSION['user_id']);

		$filter_subjects = unserialize($filter_subjects[0]->filter_subjects);
	?>
	
	<script>
	<?
		if(isset($filter_subjects)){
			echo 'var filter_subjects = '.json_encode($filter_subjects).'; ';
		}
	?>

	</script>
		<div class="form-group well well-sm">
				<input id="filter_events" name="filter_events" type="checkbox" checked>
				<label for="filter_events">Filter events</label>
		</div>

	<h4 class="current_month"></h2>
	<div class="btn-group">
		<button class="btn btn-success" data-calendar-nav="prev">&lt;&lt; Prev</button>
		<button class="btn btn-default" data-calendar-nav="today">Today</button>
		<button class="btn btn-success" data-calendar-nav="next">Next &gt;&gt;</button>
	</div>
	<div class="btn-group pull-right">
		<button class="btn btn-default" data-calendar-view="year">Year</button>
		<button class="btn btn-default active" data-calendar-view="month">Month</button>
		<button class="btn btn-default" data-calendar-view="week">Week</button>
		<button class="btn btn-default" data-calendar-view="day">Day</button>
	</div>
	<div id="calendar" class="calendar">
		
	</div>
