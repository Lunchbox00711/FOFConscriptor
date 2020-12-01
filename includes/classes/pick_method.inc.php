<?php
/***************************************************************************
 *                                pick_method.inc.php
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

define('kPlayerQueue', 1);
define('kBPAQueue', 2);
define('kScoutPick', 3);
define('kPlayerThenBPA', 4);

class pick_method
{
    public function __construct($pick_method_id)
    {
        global $mysql;
        $statement = "select * from pick_method where pick_method_id = '".mysqli_real_escape_string($mysql, $pick_method_id)."'";
        $this->data = mysqli_fetch_array(mysqli_query($mysql, $statement));
    }

    public function option_list()
    {
        global $mysql;
        $html = '';
        $statement = "select * from pick_method order by pick_method_id";
        $result = mysqli_query($mysql, $statement);
        while ($row = mysqli_fetch_array($result)) {
            if ($row['pick_method_id'] == $this->data['pick_method_id']) {
                $selected = " selected";
            } else {
                $selected = "";
            }
            $html .= '
<option value="'.$row['pick_method_id'].'"'.$selected.'>'.$row['pick_method_name'].'</option>';
        }
        return $html;
    }
}
