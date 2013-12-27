

<legend>Add New Subject</legend>

<form method="post" action="" name="new_subject">
    <div class="form-group">
        <label for="subject_id">Subject ID</label>
        <input id="subject_id" class="login_input form-control" type="text" name="subject_id" value="<? $subjects = $subject->getSubjects(); echo count($subjects)+1; ?>" required />
    </div>
    <div class="form-group">
        <label for="subject_name">Subject Name</label>
        <input id="subject_name" class="login_input form-control"  type="text" name="subject_name" required />
    </div>
    <div class="form-group">
        <label for="subject_short_name">Subject Short Name</label>
        <input id="subject_short_name" class="login_input form-control"  type="text" name="subject_name_short" required />
    </div>

	<div class="form-group">
	    <input type="checkbox" id="subject_default" name="subject_default" value="1" />
	    <label for="subject_default">Default</label>
	</div>

    <input type="submit" class="btn btn-default"  name="new_subject" value="Add" />
</form>
