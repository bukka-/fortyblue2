<?


if($registration->checkUser($user)){
	echo 'This is '.$user.'\'s profile';
}else{
	echo '<span class="alert alert-danger">User '.$user.' does not excist.</span>';
}
?>