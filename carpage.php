<?php

require_once ('config.php'); 
$page_title = 'Car Page';
include ('header.html');

if (!isset($_SESSION['user_id'])) {
    echo "<p class='error'>You must login before you can find the value of your vehicle!</p>";
    exit();
}
else {
    include ('carpage.html');
}

if (isset($_POST['submit'])) {
	require_once (MYSQL_CONNECT);
	$trimmed = array_map('trim', $_POST);

    $vin = $trimmed['vinNum'];
    $make = $trimmed['make'];
    $model = $trimmed['model'];
    $year = $trimmed['year'];
    $datePurchased = $trimmed['datePurchased'];
    $retailPrice = $trimmed['retailPrice'];
    $milesDriven = $trimmed['milesDriven'];
}


function add_car($pdo, $vin, $make, $model, $year, $datePurchased, $retailPrice)
{
  //PHP Supports executing a prepared statement, which is used to execute the same statement repeatedly with high efficiency.
  $stmt = $pdo->prepare('INSERT INTO cars(vin, make, model, carYear, date_purchased, retail_price) VALUES(?,?,?,?,?,?)');
  //Binds variables to a prepared statement as parameters
  //PARAM_STR: Used to represents the SQL CHAR, VARCHAR, or other string data type
  //$stmt->bindParam($first_name, $last_name, $email, $hash, $registration_date);
  $stmt->bindParam(1, $vin, PDO::PARAM_STR, 20);
  $stmt->bindParam(2, $make, PDO::PARAM_STR, 40);
  $stmt->bindParam(3, $model, PDO::PARAM_STR, 80);
  $stmt->bindParam(4, $year, PDO::PARAM_INT, 4);
  $stmt->bindParam(5, $datePurchased, PDO::PARAM_STR, 256);
  $stmt->bindParam(6, $retailPrice, PDO::PARAM_STR, 256);
  //if ($stmt->execute([$email, $hash, $first_name, $last_name, $registration_date]))
  if ($stmt->execute())
	return true;
  else 
	return false;
}
?>
<?php
include ('footer.html');
?>