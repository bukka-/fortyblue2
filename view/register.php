
<form method="post" action="" name="registerform">   
    
    <div class="form-group">
        <!-- the user name input field uses a HTML5 pattern check -->
        <label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label>
        <input id="login_input_username" class="login_input form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
    </div>
    <div class="form-group">
    <!-- the email input field uses a HTML5 email type check -->
        <label for="login_input_email">User's email</label>    
        <input id="login_input_email" class="login_input form-control" type="email" name="user_email" required />        
    </div>
    <div class="form-group">
        <label for="login_input_password_new">Password (min. 6 characters)</label>
        <input id="login_input_password_new" class="login_input form-control" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />  
    
    <div class="form-group">
        <label for="login_input_password_repeat">Repeat password</label>
        <input id="login_input_password_repeat" class="login_input form-control" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />   
    </div>
    <input type="submit" class="btn btn-success"  name="register" value="Register" />
    
</form>

<!-- backlink -->