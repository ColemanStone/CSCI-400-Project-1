<?php
// This is the registration page for the site, which uses a sticky form

require_once ('config.php');
$page_title = 'Registeration Main';
include ('header.html');

if (isset($_POST['submit'])) { // Handle the form.

	require_once (MYSQL_CONNECT);
	
	// Trim all the incoming data:
	//array_map() returns an array containing all the elements of an 
	//array, $_POST, after applying the callback function (trim) to each one
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$first_name = $last_name = $email = $password = FALSE;
	
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
		$first_name = $trimmed['first_name'];
	} else {
		echo '<p class="error">Please enter your first name!</p>';
	}
	
	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$last_name = $trimmed['last_name'];
	} else {
		echo '<p class="error">Please enter your last name!</p>';
	}
	
	// Check for an email address:
	if (preg_match ('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $trimmed['email'])) {
		$email = $trimmed['email'];
	} else {
		echo '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$password = $trimmed['password1'];
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($first_name && $last_name && $email && $password) { // If everything's OK...
		  
		//Query to check if the email address is available:
		$query_email = "SELECT user_id FROM users_table WHERE email='$email'";
		try {
			$pdo = new PDO($dsn, $dbUser, $dbPassword);
		}
		catch (PDOException $e){
		  die("Fatal Error - Could not connect to the database" . "</body></html>" );
		}
		$result  = $pdo->query($query_email);
		
		if (!$result->rowCount()) { // Available... Email not found
		    $hash = password_hash($password, PASSWORD_DEFAULT);
		
			if (add_user($pdo, $email, $hash, $first_name, $last_name, date("Y-m-d"))) { // If it ran OK.
				/* Send the email:
				$body = "Thank you for registering at our site. To activate your account, please click on this link:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($email) . "&y=$a";
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@sitename.com');
				*/
				// Finish the page:
				echo '<h3>Thank you for registering!</h3>';
				include ('footer.html'); 
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error!</p>';
			}
			
		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
	  	}
		
	} else { // If one of the data tests failed.
		echo '<p class="error">Please re-enter your passwords and try again.</p>';
	}
} // End of the main Submit
function add_user($pdo, $email, $hash, $first_name, $last_name, $registration_date)
{
  //PHP Supports executing a prepared statement, which is used to execute the same statement repeatedly with high efficiency.
  $stmt = $pdo->prepare('INSERT INTO users_table(first_name, last_name, email, pass, registration_date) VALUES(?,?,?,?,?)');
  //Binds variables to a prepared statement as parameters
  //PARAM_STR: Used to represents the SQL CHAR, VARCHAR, or other string data type
  //$stmt->bindParam($first_name, $last_name, $email, $hash, $registration_date);
  $stmt->bindParam(1, $first_name, PDO::PARAM_STR, 40);
  $stmt->bindParam(2, $last_name, PDO::PARAM_STR, 80);
  $stmt->bindParam(3, $email, PDO::PARAM_STR, 80);
  $stmt->bindParam(4, $hash, PDO::PARAM_STR, 256);
  $stmt->bindParam(5, $registration_date, PDO::PARAM_STR, 256);
  //if ($stmt->execute([$email, $hash, $first_name, $last_name, $registration_date]))
  if ($stmt->execute())
	return true;
  else 
	return false;
  //echo "<h3>User " . $last_name . " has been added to the database successfully.</h3>";
}
?>
	
<h1>Register</h1>
<form action="register.php" method="post">
	<fieldset>
	
	<div class="myRow">
		<label class="labelCol" for="firstName">First Name</label> 
		<input type="text" name="first_name" size="20" maxlength="20" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" />
	</div>
	
	<div class="myRow">
		<label class="labelCol" for="lastName">Last Name</label>  
		<input type="text" name="last_name" size="20" maxlength="40" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" />
	</div>
	
	<div class="myRow">
		<label class="labelCol" for="email">Email</label>
		<input type="text" name="email" size="30" maxlength="80" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /> 
	</div>
		
	<div class="myRow">
		<label class="labelCol" for="passw1">Password</label>
		<input type="password" name="password1" size="20" maxlength="20" />
		<small>Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.</small>
	</div>
	
	<div class="myRow">
		<label class="labelCol" for="passw2">Confirm Password</label>
		<input type="password" name="password2" size="20" maxlength="20" />
	</div>
	<div class="mySubmit">
		<input type="submit" name="submit" value="Register" />
	</div>
  </fieldset>
</form>

<?php // Include the HTML footer.
include ('footer.html');
?>
