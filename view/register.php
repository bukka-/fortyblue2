		<div class="row user_register">
			<?
				if ($login->isUserLoggedIn() == false) {
					include($_SERVER['DOCUMENT_ROOT']."/user/register.php");
				}else{
					echo 'Plase log out to create a new account.';
				}
			?>
		</div>