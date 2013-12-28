
<?
$subjects = $subject->getSubjects();

if($subjects && $subject_edit_id >= 0){
        $subject_id = 2;
        $subject_name = 0;
        $subject_name_short = 1;
        $subject_default = 3;

?>

<legend>Edit <? echo $subjects[$subject_edit_id][$subject_name]; ?></legend>

<form method="post" action="" name="subject_edit">
    <div class="form-group">
        <label for="subject_id">Subject ID</label>
        <input id="subject_id" class="login_input form-control" type="text" name="subject_id" value="<? echo $subjects[$subject_edit_id][$subject_id]; ?>" required readonly="readonly"/>
    </div>
    <div class="form-group">
        <label for="subject_name">Subject Name</label>
        <input id="subject_name" class="login_input form-control"  type="text" name="subject_name" value="<? echo $subjects[$subject_edit_id][$subject_name]; ?>" required />
    </div>
    <div class="form-group">
        <label for="subject_short_name">Subject Short Name</label>
        <input id="subject_short_name" class="login_input form-control"  type="text" name="subject_name_short" value="<? echo $subjects[$subject_edit_id][$subject_name_short]; ?>" required />
    </div>

	<div class="form-group">
	    <input type="checkbox" id="subject_default" name="subject_default" value="1" <? if($subjects[$subject_edit_id][$subject_default]) echo 'checked="checked"' ?> />
	    <label for="subject_default">Default</label>
	</div>

    <input type="submit" class="btn btn-default"  name="subject_edit" value="Update" />
</form>

<?
}else{
    echo '<div class="alert alert-danger">No subject with such ID</div>';
}

?>
