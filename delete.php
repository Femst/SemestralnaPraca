<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
}
require_once("config.php");
if($link->connect_error){
    die("connection failed");
}
$username = $_SESSION['username'];
$sql = "DELETE from users WHERE username = '$username'";
//$link = mysqli_connect('localhost', 'root', 'password', 'myDB');

if ($link->query($sql) == TRUE) {
    echo "Successful!";
} else {
    echo "Error: " . $sql . "<br>" . $link->error;
}

session_destroy();
?>