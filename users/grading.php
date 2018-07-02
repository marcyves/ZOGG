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


if ($user->isLoggedIn()) {
  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

  Page for permission level 1 (user)

  = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  = */
  if (hasPerm([1],$user->data()->id)) {
      openPage("nothing here");
      echo "nothing for level 1";
  }

  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

           Page for permission level 2 (professor)

   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
  if (hasPerm([2],$user->data()->id)) {
      /*
       * Le code commence ici
      */
      openPage("Grading");

      if ($my_group->getStatus()){
        //DEBUG      print_r($_POST);
        /*
        GRADING
        Here we grade the teams
        */

        if (isset($_GET['groupId'])) {
              if (isset($_POST['step'])) {
                //Third step : update grade
                $grade = $_POST['grade'];
                $comment = $_POST['comment'];

                foreach( $grade as $key => $n ) {
                  // print "The teamId is ".$key." and grade is ".$grade[$key]." and comment ".$comment[$key]."<br>";
                  // echo "<h4>Grade updated to ".$grade[$key]."</h4>";
                  assignGradeToTeam($key, $grade[$key], $comment[$key]);
                }
                listTeamsAvailable('grading', $_GET['groupId'], $_GET['job'], $_GET['course']);

              }else{
                  //Second step : display list of teams
                  listTeamsAvailable('grading', $_GET['groupId'], $_GET['job'], $_GET['course']);
                  //	listTeamsForGrading($_POST['td'],$_POST['job']);
              }
        } else {
              //First step : select TD and task which we want to grade
              listTD('grading');
      }
    } else {
      echo ("<h2>You have first to select the team you want to work on</h2>");
    }
  }
  /* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

  Page for permission level 3 (administrator)

   = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
  if (hasPerm([3],$user->data()->id)) {
      openPage("nothing here");
      echo "nothing for level 3";
  }
} else {
  openPage("You have to loggin first");
  echo "User not logged.";

}
closePage();

?>
