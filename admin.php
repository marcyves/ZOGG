<?php

/*
==============================================================================

	Copyright (c) 2017 Marc Augier

	For a full list of contributors, see "credits.txt".
	The full license can be read in "license.txt".

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	See the GNU General Public License for more details.

	Contact: m.augier@me.com
==============================================================================
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("inc/functions.php");
require_once("themes/$theme/theme.php");
require_once("inc/classes.php");
require_once("inc/my_functions.php");

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);

error_reporting(E_ALL);
ini_set("display_errors", 1);

/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

Page for permission level 1 (user)

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  = */
if ($loggedInUser->checkPermission(array(1))) {
    openPage("nothing here");
    echo "nothing for level 1";
}

/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

         Page for permission level 2 (professor)

 = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
if ($loggedInUser->checkPermission(array(2))) {
   /*
   * Le code commence ici
   */

   openPage("Administration");

   if ($my_group->getStatus()){
     //DEBUG      print_r($_GET);
     if (isset($_GET['cmd'])) {
       $cmd = $_GET['cmd'];
     } else {
       $cmd = "";
     }
     switch ($cmd) {
       case 'remove':
         echo "<h4>You are about to remove School ".$_GET['schoolId']." from group ".$_GET['groupId']."</h4>";
         echo "<a href='?cmd=remove2&studentId=".$_GET['studentId']."&groupId=".$_GET['groupId']."'>OK ?</a>";
       break;
       case 'remove2':
         $sql = "DELETE FROM `StudentTeam` WHERE IdStudent =  '".$_GET['studentId']."' AND IdTeam = '".$_GET['groupId']."'";
         $result = mysqli_query($mysqli, $sql);
       break;
       default:
         schoolManagement();
       break;
     }
   } else {
     echo ("<h2>You have first to select the team you want to work on</h2>");
   }
}
/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

Page for permission level 3 (administrator)

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
if ($loggedInUser->checkPermission(array(3))) {
  openPage("nothing here");
  echo "nothing for level 3";
}

closePage();

?>
