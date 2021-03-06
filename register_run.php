<?php
/***************************************************************************
 *                                register_run.php
 *                            -------------------
 *   begin                : Friday, Mar 28, 2008
 *   copyright            : (C) 2008 J. David Baker
 *   email                : me@jdavidbaker.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

session_name('FOFCONSCRIPTOR');
session_start();
include "includes/config.inc.php";

// Process the registration
if (!$_POST['team_password']) {
    $_SESSION['message'] = "You did not enter a password.";
    header("Location: register.php");
    exit;
}
$col[] = "team_password = '".password_hash($_POST['team_password'], PASSWORD_BCRYPT)."'";
if (preg_match("/[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z]{2,4}/", $_POST['team_email'])) {
    $col[] = "team_email = '".mysqli_real_escape_string($mysql, $_POST['team_email'])."'";
}
if (!empty($_POST['team_owner'])) {
    $col[] = "team_owner = '".mysqli_real_escape_string($mysql, $_POST['team_owner'])."'";
}
if (strlen($_POST['team_phone']) == 10 && preg_match("/[2-9]{1}\d{8}/", $_POST['team_phone'])) {
    $col[] = "team_phone = '".mysqli_real_escape_string($mysql, $_POST['team_phone'])."'";
}
if ($_POST['team_carrier']) {
    $col[] = "team_carrier = '".mysqli_real_escape_string($mysql, $_POST['team_carrier'])."'";
}
$col[] = "team_clock_adj = '1'";
$col[] = "team_autopick = '0'";
$statement = "update team set ".implode(",", $col)." where team_name like '".mysqli_real_escape_string($mysql, $_POST['team_name'])."' and
team_password is NULL";
mysqli_query($mysql, $statement);
echo mysqli_error($mysql);
if (mysqli_affected_rows($mysql) > 0) {
    $_SESSION['message'] = "Account created successfully.";
    $statement = "select team_id from team where team_name = '".mysqli_real_escape_string($mysql, $_POST['team_name'])."'";
    $result = mysqli_query($mysql, $statement);
    $row = mysqli_fetch_row($result);
    $_SESSION['team_id'] = $row[0];
    header("Location: options.php");
} else {
    $_SESSION['message'] = "Account creation failed.  Either the team name does not exist or it is already registered.";
    header("Location: register.php");
}
