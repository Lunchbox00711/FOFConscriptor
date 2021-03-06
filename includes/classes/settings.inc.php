<?php
/***************************************************************************
 *                                settings.inc.php
 *                            -------------------
 *   begin                : Tuesday, May 6, 2008
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

define('kSettingLeagueName', 1);
define('kSettingPickTimeLimit', 2);
define('kSettingSendMails', 3);
define('kSettingStartTime', 4);
define('kSettingEndTime', 5);
define('kSettingExpiredPick', 6);
define('kSettingRolloverMethod', 7);
define('kSettingAutopickReduction', 8);
define('kSettingTimeZone', 9);
define('kSettingMaxDelay', 10);
define('kSettingChatType', 11);
define('kSettingEmailType', 12);
define('kSettingSMTPServer', 13);
define('kSettingSMTPPort', 14);
define('kSettingSMTPUser', 15);
define('kSettingSMTPPassword', 16);
define('kSettingSMTPEncryptType', 17);
define('kSettingChatTemplateCode', 18);
define('kSettingAdminEmail', 19);
define('kSettingStaffDraftOn', 20);
define('kSettingDraftType', 21);

// Config constants
define('kExpireSkipPick', 0); // Default, skip pick when expired
define('kExpireMakePick', 1); // Make a BPA selection when expired

// Settings for kSettingSendMails
define('kEmailOff', 0);
define('kEmailAll', 1);
define('kEmailNextPick', 2);

// Settings for kSettingAdminEmail
define('kAdminEmailOff', 0);
define('kAdminEmailRollback', 1);
define('kAdminEmailAll', 2);

// Settings for kSettingRolloverMethod
define('kRollIntoTomorrow', 0);
define('kFinishToday', 1);

// Settings fo kSettingChatType
define('kChatTypeFlash', 0);
define('kChatTypePHP', 1);
define('kChatTypeOff', 2);
define('kChatTypeTemplate', 3);

// Settings for kSettingEmailType
define('kEmailTypeMail', 0);
define('kEmailTypeSendmail', 1);
define('kEmailTypeSMTP', 2);
define('kEmailTypeOff', 3);

// Settings for kSettingSMTPEncryptType
define('kSettingSMTPEncryptTypeNone', 0);
define('kSettingSMTPEncryptTypeSSL', 1);
define('kSettingSMTPEncryptTypeTLS', 2);


//Round specific timeouts will be defined at IDs 100+ID.
//So round 1 is at 101, round 2 is at 102, etc.


class settings
{
    public function __construct()
    {
        global $mysql;
        $statement = "select * from settings";
        $result = mysqli_query($mysql, $statement);
        while ($row = mysqli_fetch_assoc($result)) {
            $this->setting[$row['setting_id']] = $row['setting_value'];
        }
    }

    public function get_value($id)
    {
        global $mysql;
        //ok, not so simple.  intercept the all designating kSettingPickTimeLimit checks,
        //and replace it with the timelimit for the round we are currently in!
        if ($id == kSettingPickTimeLimit) {
            $statement = "select pick.pick_id, team.team_id, team_email, team_name, team_email_prefs
from pick, team
where pick.team_id = team.team_id
and pick.player_id is NULL
order by pick_id
limit 1";
            $row = mysqli_fetch_assoc(mysqli_query($mysql, $statement));
            $currentround = ($row['pick_id'] - 1) / 32 + 1;
            return $this->setting[100 + $currentround] ?? null;
        } else {
            return $this->setting[$id] ?? null;
        }
    }

    public function set_value($id, $value)
    {
        global $mysql;
        $id = mysqli_real_escape_string($mysql, $id);
        $value = mysqli_real_escape_string($mysql, $value);
        $statement = "insert into settings (setting_id) values ('$id')";
        mysqli_query($mysql, $statement);
        $statement = "update settings set setting_value = '$value' where setting_id = '$id'";
        mysqli_query($mysql, $statement);
        $this->setting[$id] = $value;
    }
}
