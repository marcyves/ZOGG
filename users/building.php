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

require_once 'init.php';
require_once $abs_us_root.$us_url_root.'users/includes/header.php';
require_once $abs_us_root.$us_url_root.'users/includes/navigation.php';
if (!securePage($_SERVER['PHP_SELF'])){die();}

require_once($abs_us_root.$us_url_root.'users/includes/config.php');
require_once($abs_us_root.$us_url_root.'users/includes/functions.php');
require_once($abs_us_root.$us_url_root.'users/themes/'.$theme.'/theme.php');
require_once($abs_us_root.$us_url_root.'users/includes/classes.php');
require_once($abs_us_root.$us_url_root.'users/includes/my_functions.php');

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);

error_reporting(E_ALL);
ini_set("display_errors", 1);
//print_r($user);

if ($user->isLoggedIn()) {
  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

  Page for permission level 1 (user)

  = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  = */
  if (hasPerm([1],$user->data()->id)) { //User
  }
  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

           Page for permission level 2 (admin)

   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
   if (hasPerm([2],$user->data()->id)) {
     /*
     * Le code commence ici
     */
     openPage("Building");

     if ($my_group->getStatus()){
       //DEBUG      print_r($_GET);
       /*
       Here we build the teams
       */
       if (isset($_GET['sub'])) {
         switch($_GET['sub']){
           case "remove":
              discardStudentFromTeam($_GET['student'], $_GET['team']);
              // Display Updated List
              listTeamsAvailable('building', $_GET['groupId'], $_GET['job'], $_GET['course']);
           break;
           case "create":
              createTeam($_GET['teamName'], $_GET['groupId'], $_GET['job']);
              listTeamsAvailable('building', $_GET['groupId'], $_GET['job'], $_GET['course']);
           break;
           case "discard":
              discardTeam($_GET['teamId']);
              listTeamsAvailable('building', $_GET['groupId'], $_GET['job'], $_GET['course']);
           break;
           case "solo":
             echo '<h2>SOLO</h2>';
             createSoloTeam($_GET['groupId'], $_GET['job']);
             // Display Updated List
             listTeamsAvailable('building', $_GET['groupId'], $_GET['job'], $_GET['course']);
           break;
         }
       } else if (isset($_GET['teamId'])) {
         // Enroll Student
         EnrollStudentInTeam($_GET['studentId'],$_GET['teamId']);
         // Display Updated List
         listTeamsAvailable('building', $_GET['groupId'], $_GET['job'], $_GET['course']);
       } else if (isset($_GET['groupId'])) {
         //Second step : display list of teams in a group TD with their members, for a set job
         listTeamsAvailable('building', $_GET['groupId'], $_GET['job'], $_GET['course']);
       } else {
         //First step : Display the list of Group TD to select one
         listTD(('building'));
       }
     } else {
       echo ("<h2>You have first to select the team you want to work on</h2>");
     }
   }
  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

  Page for permission level 3 (professor)

   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
  if (hasPerm([3],$user->data()->id)) {
  }
  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

  Page for permission level 4 (student)

   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
  if (hasPerm([4],$user->data()->id)) {
  }
} else {
  openPage("You have to loggin first");
  echo "User not logged.";
}
closePage();

?>
