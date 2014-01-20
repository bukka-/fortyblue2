<?


if($user->checkUser($user_edit_name)){
	$user_info = $user->getUserInfo($user_edit_name)[0];

	echo '<legend>Edit <a href="/user/'.$user_edit_name.'">'.$user_edit_name.'</a>\'s settings</legend>';
	

	?>

	<form method="post" action="" name="user_edit">
		<div class="form-group">
			<label for="login_input_id">ID</label>
			<input id="login_input_id" class="login_input form-control" value="<? echo $user_info->user_id; ?>" type="text" name="user_id" required readonly="readonly"/>
		</div>
		<div class="form-group">
			<label for="login_input_email">Email</label>
			<input id="login_input_email" class="login_input form-control" value="<? echo $user_info->user_email; ?>"  type="text" name="user_email" required readonly="readonly"/>
		</div>		

		<div class="form-group">
			<label for="login_input_group">Group</label>
			<input id="login_input_group" class="login_input form-control" value="<? echo $user_info->user_group; ?>"  type="group" name="user_group" required />
		</div>



		<input type="submit" class="btn btn-default"  name="user_edit" value="Edit" />
	</form>


<?
}else{
	echo '<span class="alert alert-danger">User does not excist.</span>';
}
?>