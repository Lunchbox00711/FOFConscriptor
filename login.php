<?php
/***************************************************************************
 *                                login.php
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
require_once('includes/config.inc.php');
$statement = "select team_id, team_password from team where team_name = '".mysqli_real_escape_string($mysql, $_POST['team_name'])."'";
$result = mysqli_fetch_row(mysqli_query($mysql, $statement));
if ($result) {
    if (password_verify($_POST['team_password'], $result[1])) {
        $_SESSION['team_id'] = $result[0];
    }
}
header("Location: ./selections.php");
exit;
