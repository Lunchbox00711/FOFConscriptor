<?php
$user = "root";
$password = "";
$database = "conscriptor";
$host = "localhost";
$league_page = "https://myfootballnow.com";
$mysql = mysqli_connect($host, $user, $password) or die ("I cannot connect to the database server because: ".mysqli_error($mysql));
mysqli_select_db($mysql, $database) or die ("I cannot connect to the database because: ".mysqli_error($mysql));
define ('kAdminUser', '0');