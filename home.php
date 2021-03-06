<?php

/*
==============================================================================

	Copyright (c) 2013 Marc Augier

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

if (isset($loggedInUser)) {
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
    //DEBUG	print_r($_GET);
    openPage("Les scores globaux");

    if (isset($_GET['cmd'])) {
      $cmd = $_GET['cmd'];
    } else {
      $cmd = "";
    }

    if (displayCurrentGroup()){
      switch ($cmd){
      case 'fullist';
        listAll();
      	// listAllTeams();
      break;
      default;
        displayGroupGrades();
        echo "<ul><li><a href='home.php?cmd=fullist'>Liste complète</a></li></ul>";
      }
    } else {
      echo ("<h2>You have first to select the team you want to work on</h2>");
    }
  }
  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

  Page for permission level 3 (administrator)

   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
  if ($loggedInUser->checkPermission(array(3))) {
      openPage("Administration");
      echo "Welcome to ZOGG administration.";
  }
} else {
  openPage("You have to loggin first");
  echo "User not logged.";
}
closePage();

?>
