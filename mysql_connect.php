<?php
require_once 'dblogin.php';
// This file contains the database access information. 
// This file also estables a connection to MySQL 

try {
	$pdo = new PDO($dsn, $dbUser, $dbPassword);
}
catch (PDOException $e){
  die("Fatal Error - Could not connect to the database" . "</body></html>" );
}

?>
