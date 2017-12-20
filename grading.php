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
    openPage("Grading");

    if ($my_group->getStatus()){
      //DEBUG      print_r($_GET);
      /*
      GRADING
      Here we grade the teams
      */

      if (isset($_GET['groupId'])) {
            if (isset($_GET['step'])) {
                    echo "<h4>Grade updated to ".$_GET['grade']."</h4>";
                    assignGradeToTeam($_GET['teamId'], $_GET['grade'], $_GET['comment']);
                    listTeamsAvailable('grading', $_GET['groupId'], $_GET['job'], $_GET['course']);
            }else{
              echo "Second step";
                    //Second step : display list of teams
                    listTeamsAvailable('grading', $_GET['groupId'], $_GET['job'], $_GET['course']);
            //	listTeamsForGrading($_GET['td'],$_GET['job']);
            }
      } else {
            //First step : select TD and task which we want to grade
            listTD('grading');
    }
    //Third step : update grade
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
