<?php
// This is the login page for the site.
require_once ('config.php'); 
$page_title = 'Login Page';
include ('header.html');
$displayForm = true;

if (isset($_POST['submit'])) {
	require_once (MYSQL_CONNECT);
	$trimmed = array_map('trim', $_POST);

	// Validate the email address:
	if (!empty($_POST['email'])) {
		$email = $trimmed['email'];
	} else {
		$email = FALSE;
		echo '<p class="error">You forgot to enter your email address!</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['pass'])) {
		$password = $trimmed['pass'];
	} else {
		$password = FALSE;
		echo '<p class="error">You forgot to enter your password!</p>';
	}
	
	if ($email && $password) { // If everything's OK.
		// Query the database: 
		$query_user = "SELECT user_id, first_name, email, pass FROM users_table WHERE email='$email'";		
		try {
			$pdo = new PDO($dsn, $dbUser, $dbPassword);
		}
		catch (PDOException $e){
		  die("Fatal Error - Could not connect to the database" . "</body></html>" );
		}
		$result  = $pdo->query($query_user);
			
		if ($result->rowCount() == 1) { // A match was found
		
		  $row = $result->fetch(PDO::FETCH_NUM);
		  if (password_verify($password, $row[3])) { 
			// Register the values & redirect:
			$_SESSION['user_id'] = $row[0];
			$_SESSION['name'] = $row[1];
			echo htmlspecialchars("Hi $row[1], you are now logged in as '$row[2]'");
			$displayForm = false;

		  }	else { // No match was made.
				echo '<p class="error">Either the email address and password entered do not match those on file or you have no account yet.</p>';
		  }
	    } else { 
			echo '<p class="error">Either the email address and password entered do not match those on file or you have no account yet.</p>';
	  }
		
	   } else { 
		echo '<p class="error">Please try again.</p>';
	}

} // End of SUBMIT conditional.

if ($displayForm) {
?>
<h1>Login</h1>
<form action="login.php" method="post">
	<fieldset>
	<div class="myRow">
		<label class="labelCol" for="email">Email</label> 
		<input type="text" name="email" size="20" maxlength="40" />
	</div>
	<div class="myRow">
		<label class="labelCol" for="[assw">Password</label>
		<input type="password" name="pass" size="20" maxlength="20" />
    </div>
	<div class="mySubmit">
		<input type="submit" name="submit" value="Login" /></div>
	</div>
	</fieldset>
</form>
<?php
}
?> 
<?php // Include the HTML footer.
include ('footer.html');
?>
