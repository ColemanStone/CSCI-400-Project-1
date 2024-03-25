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
	require (MYSQL_CONNECT);
	$trimmed = array_map('trim', $_POST);

    $error = true;

    if(!empty($_POST['vinNum']))
        $vin = $trimmed['vinNum'];
    else 
        $vin = null;
    $make = $_POST['make'];
    if($make == 'default'){
        $make = 'Select';
    }
    $model = $_POST['model'];
    if($model == ''){
        $model = 'Select';
    }
    if(!empty($_POST['year']))
        $year = $trimmed['year'];
    else
        $year = null;
        
    if(!empty($_POST['datePurchased']))
        $datePurchased = $trimmed['datePurchased'];
    else
        $datePurchased = null;
    if(!empty($_POST['retailPrice']))
        $retailPrice = $trimmed['retailPrice'];
    else
        $retailPrice = null;
    if(!empty($_POST['milesDriven']))
        $milesDriven = $trimmed['milesDriven'];
    else
        $milesDriven = null;
    $carCondition = $_POST['condition'];
    if(isset($_POST['addon']))
        $addon = $_POST['addon'];
    else 
        $addon = null;
    $privatePrice = $retailPrice * 1.15;
    $preOwnedPrice = $privatePrice * 1.1;

    $ownerID = htmlspecialchars($_SESSION['user_id']);

    $checkVin = validateVin($vin);
    $checkMake = validateMake($make);
    $checkModel = validateModel($model);
    $checkYear = validateYear($year);
    $checkDate = validateDate($datePurchased);
    $checkPrice = validatePrice($retailPrice);
    $checkMilesDriven = validateMilesDriven($milesDriven);
    $checkCondition = validateCondition($carCondition);

    if($checkVin && $checkMake && $checkModel && $checkYear && $checkDate && $checkPrice
        && $checkMilesDriven && $checkCondition){
        $error = false;
    }

    if(!$error){
        if (add_car($pdo, $vin, $make, $model, $year, $datePurchased, $milesDriven, $carCondition)
        && add_owners_cars($pdo, $ownerID, $vin, $privatePrice, $retailPrice, $preOwnedPrice)) { 
            if($carCondition == "fair"){
                $sellPrice = $retailPrice * 0.85;
            } else if($carCondition == "good"){
                $sellPrice = $retailPrice * 0.9;
            } else if($carCondition == "veryGood"){
                $sellPrice = $retailPrice * 0.95;
            }
            else {
                $sellPrice = $retailPrice;
            }

            if($milesDriven >= 150000){
                $sellPrice = $sellPrice * 0.8;
            } else if($milesDriven >= 120000){
                $sellPrice = $sellPrice * 0.85;
            } else if($milesDriven >= 80000){
                $sellPrice = $sellPrice * 0.9;
            } else if($milesDriven >= 40000){
                $sellPrice = $sellPrice * 0.95;
            }

            if(!empty($addon)){
                $sellPrice += 50;
            }
            
            /*if(sizeof($addon) > 0){
                foreach($addon as $value){
                    $sellPrice += 50;
                }
            }*/

            $updatedRetailPrice = round($sellPrice,0);
            $updatedPrivatePrice = round($updatedRetailPrice * 1.15,0);
            $updatedPreOwnedPrice = round($updatedPrivatePrice * 1.1,0);

            $query_updatePrices = "UPDATE owners_cars SET private_price = '$updatedPrivatePrice', retail_price = '$updatedRetailPrice', pre_owned_price = '$updatedPreOwnedPrice' WHERE vid = '$vin'";

            $result = $pdo->query($query_updatePrices);

            echo "<h2>Estimated Car Value: </h2>";
            echo "<p>Based on the information provided, your $year " . ucfirst($make) . " " . ucfirst($model) . " that you bought for $$retailPrice is is worth $$updatedRetailPrice if you sell it to a dealership,
                    $$updatedPrivatePrice if you sell it to a private owner, and $$updatedPreOwnedPrice as the certified pre-owned car price.</p>";

            include ('footer.html'); 
            exit(); // Stop the page.
            
        } else { // If it did not run OK.
            echo '<p class="error">Your car could not be valuated due to a system error!</p>';
        }
    } else {
        echo "<br><p class='error'>You must fix your inputs. Try again</p>";
    }
    
}

if(isset($_POST['history'])){
    require (MYSQL_CONNECT);
	$trimmed = array_map('trim', $_POST);

    $ownerID = htmlspecialchars($_SESSION['user_id']);
    $firstName = $_SESSION['name'];

    $query_history = "SELECT VID, private_price, retail_price, pre_owned_price FROM owners_cars WHERE owner_ID = $ownerID";

    try {
        $pdo = new PDO($dsn, $dbUser, $dbPassword);
    }
    catch (PDOException $e){
      die("Fatal Error - Could not connect to the database" . "</body></html>" );
    }
    
    $result = $pdo->query($query_history);
    $count = 1;
    
    if ($result->rowCount()) { 
        echo "<h2> " . ucfirst($firstName) . ", here is the history of valuations you have received. The most recent valuations are on top.</h2>";
        foreach($result as $row){
            $vid = $row[0];
            $privPrice = $row[1];
            $retPrice = $row[2];
            $poPrice = $row[3];

            $query_db = "SELECT make, model, carYear FROM cars WHERE vin = '$vid'";

            $dbresult = $pdo->query($query_db);

            if($dbresult->rowCount() == 1){
                foreach($dbresult as $dbrow){
                    $cMake = $dbrow[0];
                    $cModel = $dbrow[1];
                    $cYear = $dbrow[2];

                    echo "<p>Vehicle " . $count . ": Make - " . ucfirst($cMake) . ", Model - " . ucfirst($cModel) . ", Year - $cYear, Retail Price - $$retPrice, Private Price - $$privPrice, Pre-Owned Price - $$poPrice</p>";
                    $count++;
                }
            } else {
                echo "<p>There are no cars registered with that vin number.</p>";
            }
        }
    } else {
        echo "<p>" . ucfirst($firstName) . ", you have not had any cars valuated yet.</p>";
    }
}


function add_car($pdo, $vin, $make, $model, $year, $datePurchased, $milesDriven, $carCondition)
{
    $stmt = $pdo->prepare('INSERT INTO cars(vin, make, model, carYear, date_purchased, miles_driven, car_condition) VALUES(?,?,?,?,?,?,?)');
    //Binds variables to a prepared statement as parameters
    //PARAM_STR: Used to represents the SQL CHAR, VARCHAR, or other string data type
    $stmt->bindParam(1, $vin, PDO::PARAM_STR, 20);
    $stmt->bindParam(2, $make, PDO::PARAM_STR, 40);
    $stmt->bindParam(3, $model, PDO::PARAM_STR, 80);
    $stmt->bindParam(4, $year, PDO::PARAM_INT, 4);
    $stmt->bindParam(5, $datePurchased, PDO::PARAM_STR, 256);
    $stmt->bindParam(6, $milesDriven, PDO::PARAM_INT, 7);
    $stmt->bindParam(7, $carCondition, PDO::PARAM_STR, 20);

    if ($stmt->execute())
        return true;
    else
        return false;
}

function add_owners_cars($pdo, $ownerID, $vin, $privatePrice, $retailPrice, $preOwnedPrice){
    $stmt = $pdo->prepare('INSERT INTO owners_cars(owner_ID, VID, private_price, retail_price, pre_owned_price) VALUES(?,?,?,?,?)');
    //Binds variables to a prepared statement as parameters
    //PARAM_STR: Used to represents the SQL CHAR, VARCHAR, or other string data type
    $stmt->bindParam(1, $ownerID, PDO::PARAM_INT, 20);
    $stmt->bindParam(2, $vin, PDO::PARAM_STR, 20);
    $stmt->bindParam(3, $privatePrice, PDO::PARAM_STR, 10);
    $stmt->bindParam(4, $retailPrice, PDO::PARAM_STR, 10);
    $stmt->bindParam(5, $preOwnedPrice, PDO::PARAM_STR, 10);

    if ($stmt->execute())
        return true;
    else
        return false;
}

function validateVin($vin){
    if(!empty($vin) && ctype_alnum($vin)){
        return true;
    } else{
        print("<br>Invalid vin");
        return false;
    }
}

function validateMake($make){
    if($make != "Select"){
        return true;
    } else{
        print("<br>You must select a make");
        return false;
    }
}

function validateModel($model){
    if($model != "Select"){
        return true;
    } else{
        print("<br>You must select a model");
        return false;
    }
}

function validateYear($year){
    if(!empty($year) && (preg_match('/^[0-9]{4}$/i', $year))) {
        return true;
    } else{
        print("<br>Invalid year");
        return false;
    }
}

function validateDate($datePurchased){
    if(!empty($datePurchased) && (preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/i', $datePurchased))) {
        return true;
    } else{
        print("<br>Invalid date");
        return false;
    }
}

function validatePrice($retailPrice){
    if(!empty($retailPrice) && (preg_match('/^[0-9]*$/i', $retailPrice))) {
        return true;
    } else{
        print("<br>Invalid price");
        return false;
    }
}

function validateMilesDriven($milesDriven){
    if(!empty($milesDriven) && (preg_match('/^[0-9]{1,7}$/i', $milesDriven))) {
        return true;
    } else{
        print("<br>Invalid number of miles driven");
        return false;
    }
}

function validateCondition($carCondition){
    if($carCondition != "select"){
        return true;
    } else{
        print("<br>You must fill in a condition");
        return false;
    }
}

?>
<?php
include ('footer.html');
?>