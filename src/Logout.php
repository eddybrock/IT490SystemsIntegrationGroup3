<?php
session_start();
$_SESSION['username'] = $username;
header("Location: ../src/index.php");
?>