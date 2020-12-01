<?php
include "includes/classes.inc.php";

$statement = "select team.team_name, team.team_id, team_owner
from team where team.team_chat_time > '".date("Y-m-d H:i:s", strtotime("-10 seconds"))."'
order by team_name";
$result = mysqli_query($mysql, $statement);
echo mysqli_error($mysql);
$users = [];
while ($row = mysqli_fetch_array($result)) {
    if ($row['team_id'] != $login->team_id()) {
        if ($row['team_id'] == '1') {
            $users[] = '<a href="javascript:private_chat(\''.$row['team_id'].'\')">Admin</a>';
        } else {
            $users[] = '<a href="javascript:private_chat(\''.$row['team_id'].'\')">'.$row['team_name'].'-'.$row['team_owner'].'</a>';
        }
    } else {
        if ($row['team_id'] == '1') {
            $users[] = 'Admin';
        } else {
            $users[] = $row['team_name'].'-'.$row['team_owner'];
        }
    }
}
echo '<p style="font-size: 13px; line-height: 15px; font-weight: bold">'.implode("<br>", $users);
