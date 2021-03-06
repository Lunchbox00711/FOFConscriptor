<?php
/***************************************************************************
 *                                position.inc.php
 *                            -------------------
 *   begin                : Monday, Apr 7, 2008
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

define('kPositionK', 10);
define('kPositionP', 9);

class position
{
    public function option_list($staff = false)
    {
        global $mysql;
        $html = '';
        if (!$staff) {
            $statement = "select * from position order by position_id";
            $result = mysqli_query($mysql, $statement);
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['position_id'] == ($_GET['position_id'] ?? null)) {
                    $selected = " selected";
                } else {
                    $selected = '';
                }
                $html .= '
              <option value="'.$row['position_id'].'"'.$selected.'>'.$row['position_name'].'</option>';
            }
        } else {
            $statement = "select * from staff_roles order by staff_role_id";
            $result = mysqli_query($mysql, $statement);
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['staff_role_id'] == ($_GET['position_id'] ?? null)) {
                    $selected = " selected";
                } else {
                    $selected = '';
                }
                $html .= '
              <option value="'.$row['staff_role_id'].'"'.$selected.'>'.$row['staff_role_name'].'</option>';
            }
        }
        return $html;
    }
}
