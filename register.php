<?php
// This is the registration page for the site, which uses a sticky form

require_once ('config.php');
$page_title = 'Registration Main';
include ('header.html');
include ('register.html');

if (isset($_POST['submit'])) { // Handle the form.

	require_once (MYSQL_CONNECT);
	
	// Trim all the incoming data:
	//array_map() returns an array containing all the elements of an 
	//array, $_POST, after applying the callback function (trim) to each one
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$first_name = $last_name = $email = $password = FALSE;
	
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['registerFirstName'])) {
		$first_name = $trimmed['registerFirstName'];
	} else {
		echo '<p class="error">Please enter your first name!</p>';
	}
	
	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['registerLastName'])) {
		$last_name = $trimmed['registerLastName'];
	} else {
		echo '<p class="error">Please enter your last name!</p>';
	}
	
	// Check for an email address:
	if (preg_match ('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $trimmed['registerEmail'])) {
		$email = $trimmed['registerEmail'];
	} else {
		echo '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{6}$/', $trimmed['password']) ) {
		if ($trimmed['password'] == $trimmed['confirmedPassword']) {
			$password = $trimmed['password'];
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($first_name && $last_name && $email && $password) { // If everything's OK...
		//Query to check if the email address is available:
		$query_email = "SELECT user_id FROM owners WHERE email='$email'";
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
  $stmt = $pdo->prepare('INSERT INTO owners(first_name, last_name, email, pass, registration_date) VALUES(?,?,?,?,?)');
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
}
?>

<?php // Include the HTML footer.
include ('footer.html');
?>
