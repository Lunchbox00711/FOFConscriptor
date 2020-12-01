<?php
$user = "database_user";
$password = "database_password";
$database = "database_name";
$host = "localhost";
mysqli_connect($host, $user, $password) or die("I cannot connect to the database server because: ".mysqli_error($mysql));
mysqli_select_db($database) or die("I cannot connect to the database because: ".mysqli_error($mysql));
define('kAdminUser', '1');
