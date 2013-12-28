<?


if($user->checkUser($user_name)){
	echo 'This is '.$user_name.'\'s profile';
}else{
	echo '<span class="alert alert-danger">User '.$user_name.' does not excist.</span>';
}
?>