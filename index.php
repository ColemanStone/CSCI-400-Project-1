<?php
// This is the main page for the site.

// Include the configuration file:
require_once('config.php');

// Set the page title and include the HTML header:
$page_title = 'Welcome to this Site!';
include('header.html');

// Welcome the user (by name if they are logged in):
if (isset($_SESSION['user_id'])) {
	$name = htmlspecialchars($_SESSION['name']);
	echo "<h1>Welcome, $name! </h1>";
}
?>

<?php
include('homepage.html');

// Include the HTML footer file:
include('footer.php');
?>



