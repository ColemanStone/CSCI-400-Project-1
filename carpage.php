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
include ('carpage.html');
include ('footer.html');
?>