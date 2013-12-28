<form method="post" class="form-inline" action="" name="loginform">
	<div class="form-group">
		<label class="sr-only" for="login_input_username">Username</label>
		<input id="login_input_username" placeholder="Username" class="login_input form-control" type="text" name="user_name" required />
	</div>
	<div class="form-group">
		<label class="sr-only" for="login_input_password">Password</label>
		<input id="login_input_password" placeholder="Password" class="login_input form-control"  type="password" name="user_password" autocomplete="off" required />
	</div>

	<div class="form-group">
		<input type="checkbox" id="user_rememberme" name="user_rememberme" value="1" />
		<label for="user_rememberme">Remember me</label>
	</div>

	<input type="submit" class="btn btn-default"  name="login" value="Log in" />
</form>
