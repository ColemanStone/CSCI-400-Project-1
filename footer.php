<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Document</title>
</head>
<body>

<div id="Menu">
    <a href="index.php" title="Home Page" class="button">Home</a><br />
    <?php
if (isset($_SESSION['user_id'])) {
    ?>
    <a href="carpage.php" title="Car Page" class="button">Get Car Value</a><br />
    <a href="change_password.php" title="Change Your Password" class="button">Change Password</a><br />
	<a href="logout.php" title="Logout">Logout</a><br />
    <?php
    } else { //  Not logged in.
    ?>
    <a href="register.php" title="Register for the Site" class="button">Register</a><br />
    <a href="login.php" title="Login" class="button">Login</a><br />
    <a href="forgot_password.php" title="Password Retrieval" class="button">Reset Password</a><br />
    <a href="carpage.php" title="Change Your Password" class="button">Get Car Value</a><br />
    <?php
    }
    
    ?>
</div>
</body>
</html>