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
<?php
include ('forgot_password.html');

if(isset($_POST['submit'])){
    print("here");
    require_once (MYSQL_CONNECT);
    $trimmed = array_map('trim', $_POST);

    $loginEmail = $trimmed['loginEmail'];
    print($loginEmail);

    $query_email = "SELECT pass from owners WHERE email='$loginEmail'";
    print($query_email);

    try {
        $pdo = new PDO($dsn, $dbUser, $dbPassword);
    }
    catch (PDOException $e){
      die("Fatal Error - Could not connect to the database" . "</body></html>" );
    }
    try{
        $result  = $pdo->query($query_email);
    } catch(Exception $e){
        print $e;
    }
        
    if ($result->rowCount() == 1) {
        $query_change = "UPDATE owners SET pass='temporary' WHERE email='$loginEmail'";
        print($query_change);

        $result = $pdo->query($query_change);

        print("<p>Your password has been set as \"temporary\". Make sure to
            go to the change password page to set a new permanent password!</p>");
    }
}

include ('footer.html');
?>
