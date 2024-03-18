<title>Change Password</title>

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

if (isset($_POST['submit'])) {
	$valid = false;

	require_once (MYSQL_CONNECT);
	$trimmed = array_map('trim', $_POST);

	/*$id = $_SESSION['id'];
	print($id);

	$query_user = "SELECT pass FROM owners WHERE user_id='$id'";
	print($query_user);
	$result = $pdo->query($query_user);

	print($result);*/

	$newPassword = $trimmed['newpass'];
	$confirmNewPassword = $trimmed['confirmnewpass'];

	if(($newPassword == $confirmNewPassword)){
		$valid = true;
	} else {
		echo "<p class='error'>Passwords don't match</p>";
	}

	if($valid){
		$query = "UPDATE owners SET pass=$newPassword WHERE first_name=$name";
		print($query);

		try {
			$pdo = new PDO($dsn, $dbUser, $dbPassword);
		}
		catch (PDOException $e){
		  die("Fatal Error - Could not connect to the database" . "</body></html>" );
		}

		$result = $pdo->query($query);

		if (!$result->rowCount()) {
			print("Password successfully changed to ". $newPassword);
		}
		else{
			print("fail");
		}
	}
}

?>
<form action="change_password.php" method="post">
	<fieldset>
	<div class="myRow">
		<label class="labelCol" for="newpass">New Password</label> 
		<input type="password" name="newpass" size="20" maxlength="20" /> <small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small>
	</div>
	<div class="myRow">
		<label class="labelCol" for="newpassc">Confirm New Password</label> 
		<input type="password" name="confirmnewpass" size="20" maxlength="20" />
	</div>
	
	<div class="mySubmit">
		<input type="submit" name="submit" value="Change My Password" />
	</div>
	</fieldset>
</form>

<?php
include ('footer.html');
?>
