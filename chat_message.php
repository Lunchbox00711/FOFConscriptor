<?php
include "includes/classes.inc.php";

if (get_magic_quotes_gpc()) {
    $_POST['message'] = stripslashes($_POST['message']);
}

if ($_POST['chat_room_id']) {
    $statement = "select * from chat_room where team_1_id = '".$login->team_id()."' or team_2_id = '".$login->team_id()."'
and chat_room_id = '".$_POST['chat_room_id']."'";
    if (mysqli_num_rows(mysqli_query($mysql, $statement))) {
        $statement = "insert into chat (team_id, team_owner, chat_time, chat_message, chat_room_id) values
('".$login->team_id()."', '".$login->team_owner()."', '".date("Y-m-d H:i:s")."', '".addslashes($_POST['message'])."', '".$_POST['chat_room_id']."')";
        mysqli_query($mysql, $statement);
        $value = $login->latest_message() + 1;
        $statement = "update last_update set latest_message='".$value."'";
        mysqli_query($mysql, $statement);
    }
} else {
    $statement = "insert into chat (team_id, team_owner, chat_time, chat_message) values
('".$login->team_id()."', '".$login->team_owner()."', '".date("Y-m-d H:i:s")."', '".addslashes($_POST['message'])."')";
    mysqli_query($mysql, $statement);
    $value = $login->latest_message() + 1;
    $statement = "update last_update set latest_message='".$value."'";
    mysqli_query($mysql, $statement);
}
echo $statement;
