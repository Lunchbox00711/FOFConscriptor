<?php
// Rolls back to the selected pick
include "includes/classes.inc.php";

// Admin only!
global $settings;

if ($login->is_admin() && $_POST['pick_id']) {
    $staff = false;
    if ($settings->get_value(kSettingStaffDraftOn) == 1) {
        $staff = true;
    }
    // First get a list of the newly available players
    $tables[] = "pick";
    $wheres[] = "pick.pick_id >= '".$_POST['pick_id']."'";
    if (!$staff) {
        $tables[] = "player";
        $wheres[] = "player.player_id = pick.player_id";
        $col[] = "player.player_name";
        $tables[] = "position";
        $wheres[] = "position.position_id = player.position_id";
        $col[] = "position.position_name";
    } else {
        $col[] = "pick.team_id";
        $tables[] = "staff";
        $wheres[] = "staff.staff_id = pick.player_id";
        $col[] = "staff.staff_name";
    }

    $statement = "select ".implode(",", $col)." from ".implode(",", $tables)." where ".implode(" and ", $wheres)." order by pick_id";
    $result = mysqli_query($mysql, $statement);
    if (mysqli_num_rows($result)) {
        // Compose the e-mail and send to all registered teams
        $subject = $settings->get_value(kSettingLeagueName)." Draft Rollback Notification";
        $message = "The draft has been rolled back to pick ".calculate_pick($_POST['pick_id']).".
The following players/staff are back on the board.  If you had them in your queue you will need to re-add them.";
        while ($row = mysqli_fetch_assoc($result)) {
            if (!$staff) {
                $message .= '

'.$row['player_name'].' ('.$row['position_name'].')';
            } else {
                $statement = "update staff set drafted = 0 where staff_name = '".$row['staff_name']."'";
                mysqli_query($mysql, $statement);
                //now make sure we check the team that made this pick didn't have a staff member that was
                //fired as a result of the pick we just un-did.
                $round = floor(($_POST['pick_id'] - 1) / 32) + 1;
                $statement = "select * from staff,team where staff.staff_role_id=".$round." and team.team_id=".$row['team_id']." and staff.staff_curr_team_id=team.in_game_id";
                $result2 = mysqli_query($mysql, $statement);
                if ($row2 = mysqli_fetch_assoc($result2)) {
                    //yep they fired a guy.  Unfire him.
                    $statement = "update staff set fired = 0 where staff_name = '".$row2['staff_name']."'";
                    mysqli_query($mysql, $statement);
                }
                $message .= '

'.$row['staff_name'].' ';
            }
        }
        $round = floor(($_POST['pick_id'] - 1) / 32) + 1;
        $pick = $_POST['pick_id'] - (($round - 1) * 32);
        update_staff_amenable($round, $pick);

        // send this message to each team
        $statement = "select * from team where team_id = '".kAdminUser."'";
        $row = mysqli_fetch_assoc(mysqli_query($mysql, $statement));
        $from = "FOF Conscriptor Admin <".$row['team_email'].">";
        $fromaddress = $row['team_email'];
        if ($settings->get_value(kSettingSendMails)) {
            $statement = "select * from team where team_email is not NULL";
            $result = mysqli_query($mysql, $statement);
            while ($row = mysqli_fetch_assoc($result)) {
                require_once './includes/lib/swift_required.php';
                if ($settings->get_value(kSettingEmailType) == kEmailTypeMail) {
                    //mail($row['team_email'], $subject, $message, "From: $from");
                    $transport = Swift_MailTransport::newInstance();
                } elseif ($settings->get_value(kSettingEmailType) == kEmailTypeSendmail) {
                    // Sendmail
                    $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
                } elseif ($settings->get_value(kSettingEmailType) == kEmailTypeSMTP) {
                    // Create the Transport
                    $transport = Swift_SmtpTransport::newInstance($settings->get_value(kSettingSMTPServer), $settings->get_value(kSettingSMTPPort))
              ->setUsername($settings->get_value(kSettingSMTPUser))
              ->setPassword($settings->get_value(kSettingSMTPPassword));
                    if ($settings->get_value(kSettingSMTPEncryptType) == kSettingSMTPEncryptTypeSSL) {
                        $transport->setEncryption('ssl');
                    } elseif ($settings->get_value(kSettingSMTPEncryptType) == kSettingSMTPEncryptTypeTLS) {
                        $transport->setEncryption('tls');
                    }
                }
                //if email is not off for regular GMs or email is not off for site admin
                if (($settings->get_value(kSettingEmailType) != kEmailTypeOff && $row['team_id'] != kAdminUser) || ($row['team_id'] == kAdminUser && $settings->get_value(kSettingAdminEmail))) {
                    // Create the Mailer using your created Transport
                    $mailer = Swift_Mailer::newInstance($transport);
                    // Create the message
                    $messagetosend = Swift_Message::newInstance()
              // Give the message a subject
              ->setSubject($subject)
              // Set the From address with an associative array
              ->setFrom([$fromaddress => 'FOF Draft Admin'])
              // Set the To addresses with an associative array
              ->setTo([$row['team_email']])
              // Give it a body
              ->setBody($message);
                    // Send the message
                    try {
                        $emailresult = $mailer->send($messagetosend);
                    } catch (Exception $e) {
                        $_SESSION['message'] = "Error sending email: ".$e->getMessage();
                    }
                }
            }
        } elseif ($settings->get_value(kSettingAdminEmail)) {
            // At least send the message to the admin if he wants to have it!
            require_once './includes/lib/swift_required.php';
            if ($settings->get_value(kSettingEmailType) == kEmailTypeMail) {
                //mail ($from, $subject, $message, "From: $from");
                $transport = Swift_MailTransport::newInstance();
            } elseif ($settings->get_value(kSettingEmailType) == kEmailTypeSendmail) {
                // Sendmail
                $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
            } elseif ($settings->get_value(kSettingEmailType) == kEmailTypeSMTP) {
                // Create the Transport
                $transport = Swift_SmtpTransport::newInstance($settings->get_value(kSettingSMTPServer), $settings->get_value(kSettingSMTPPort))
              ->setUsername($settings->get_value(kSettingSMTPUser))
              ->setPassword($settings->get_value(kSettingSMTPPassword));
                if ($settings->get_value(kSettingSMTPEncryptType) == kSettingSMTPEncryptTypeSSL) {
                    $transport->setEncryption('ssl');
                } elseif ($settings->get_value(kSettingSMTPEncryptType) == kSettingSMTPEncryptTypeTLS) {
                    $transport->setEncryption('tls');
                }
            }
        
            if ($settings->get_value(kSettingEmailType) != kEmailTypeOff) {
                // Create the Mailer using your created Transport
                $mailer = Swift_Mailer::newInstance($transport);
                // Create the message
                $messagetosend = Swift_Message::newInstance()
              // Give the message a subject
              ->setSubject($subject)
              // Set the From address with an associative array
              ->setFrom([$fromaddress => 'FOF Draft Admin'])
              // Set the To addresses with an associative array
              ->setTo([$fromaddress])
              // Give it a body
              ->setBody($message);
                // Send the message
                try {
                    $emailresult = $mailer->send($messagetosend);
                } catch (Exception $e) {
                    $_SESSION['message'] = "Error sending email: ".$e->getMessage();
                }
            }
        }
    }
    // Now we can roll back the pick
    $statement = "update pick set player_id = '".kDraftHalt."',
pick_expired = NULL
where pick_id >= '".$_POST['pick_id']."'";
    mysqli_query($mysql, $statement);
    $_SESSION['message'] = "Draft rollback completed.";

    // Re-set the team_teen table
    $statement = "update team_need set pick_id = NULL";
    mysqli_query($mysql, $statement);
    fill_team_need();
}

// Return to the main page
header("Location: options.php");
