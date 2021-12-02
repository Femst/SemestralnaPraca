<?php
// Initialize the session
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
<h1>User: <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b></h1>
<p>
    <a href="delete.php" >Delete account</a>
    <a href="index.html" >Home</a>
    <a href="reset-password.php" >Reset Password</a>
    <a href="logout.php" >Sign Out</a>
</p>
</body>
</html>