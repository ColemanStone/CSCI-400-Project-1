<?php
// This page allows a user to reset their password, if forgotten.
//Usually, password reset would have the system send an email message
//to the user requireing him/her to change the password or a temporary 
//password would be generated and sent via email. However, since Turing 
//doesn't allow email messages, we can simply display the new password to
//the user. For simplicity, assume that the system always resets the user's 
//password to the default password, which can be something such as "pass"

require_once ('config.php'); 
$page_title = 'Forgot Your Password';
include ('header.html');


?>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<h1>Reset Your Password</h1>
<p>Enter your email address below and your password will be reset.</p> 
<form action="forgot_password.php" method="post">
	<fieldset>
		<div class="myRow">
			<label class="labelCol" for="email">Email Address</label>  
			<input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
		</div>

		<div class="mySubmit"><input type="submit" name="submit" value="Reset My Password" /></div>
	</fieldset>
</form>
<?php
include ('footer.html');
?>
