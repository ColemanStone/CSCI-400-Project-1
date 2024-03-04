<?php
// This page allows a logged-in user to change their password.

require_once ('config.php'); 
$page_title = 'Change Your Password';
include ('header.html');

// If no name session variable exists, redirect the user:
if (!isset($_SESSION['name'])) {
	$url = BASE_URL . 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
} else {
	$name = htmlspecialchars($_SESSION['name']);
	echo "<h1> Password Change for $name </h1>";
	echo "\n\n\n";
}
?>
<form action="change_password.php" method="post">
	<fieldset>
	<div class="myRow">
		<label class="labelCol" for="newpass">New Password</label> 
		<input type="password" name="password1" size="20" maxlength="20" /> <small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small>
	</div>
	<div class="myRow">
		<label class="labelCol" for="newpassc">Confirm New Password</label> 
		<input type="password" name="password2" size="20" maxlength="20" />
	</div>
	
	<div class="mySubmit">
		<input type="submit" name="submit" value="Change My Password" />
	</div>
	</fieldset>
</form>

<?php
include ('footer.html');
?>
