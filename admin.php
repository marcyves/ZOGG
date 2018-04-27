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
    echo "You are not allowed to anything here.";
}

/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

         Page for permission level 2 (professor)

 = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
if ($loggedInUser->checkPermission(array(2))) {
  openPage("nothing here");
  echo "You are not allowed to anything here.";
}
/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =

Page for permission level 3 (administrator)

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
if ($loggedInUser->checkPermission(array(3))) {
  /*
  * Le code commence ici
  */

  openPage("Administration");

print_r($_POST);

  if ($my_group->getStatus()){
    //DEBUG      print_r($_GET);
    if (isset($_POST['cmd'])) {
      $cmd = $_POST['cmd'];
    } else {
      $cmd = "";
    }
    switch ($cmd) {
      case 'newCampus':
        $sql = "INSERT  INTO Campus  (CampusName) VALUES ('".$_POST['campusName']."')";
        $result = mysqli_query($mysqli, $sql);
        echo "<h2>Campus ".$_POST['campusName']." created</h2>";
        schoolManagement();
      break;
      case 'newProgram':
        $sql = "INSERT  INTO Program  (ProgramName, ProgramCampusId) VALUES ('".$_POST['ProgramName']."', '".$_POST['CampusId']."')";
        $result = mysqli_query($mysqli, $sql);
        echo "<h2>Program ".$_POST['ProgramName']." created</h2>";
        schoolManagement();
      break;
      case 'newCourse':
        $sql = "INSERT  INTO Course  (CourseName, CourseProgramId, CourseYear, CourseSemester) VALUES ('".$_POST['CourseName']."', '".$_POST['ProgramId']."', '".$_POST['CourseYear']."', '".$_POST['CourseSemester']."')";
        $result = mysqli_query($mysqli, $sql);
        echo "<h2>Course ".$_POST['CourseName']." (".$_POST['CourseYear'].") created</h2>";
        schoolManagement();
      break;
      case 'removeCampus':
        // test if campus empty
        echo "<h4>You are about to remove School ".$_GET['schoolId']." from group ".$_GET['groupId']."</h4>";
        echo "<a href='?cmd=remove2&studentId=".$_GET['studentId']."&groupId=".$_GET['groupId']."'>OK ?</a>";
      break;
      case 'remove2':
        $sql = "DELETE FROM `StudentTeam` WHERE IdStudent =  '".$_GET['studentId']."' AND IdTeam = '".$_GET['groupId']."'";
        $result = mysqli_query($mysqli, $sql);
      break;
      case 'updateCampus':
      echo "<h2></h2>";
      break;
      case 'updateCourse':
      echo "<h2></h2>";
      break;
      case 'updateProgram':
      echo "<h2></h2>";
      break;
      case 'removeProgram':
      echo "<h2></h2>";
      break;
      case 'removeCourse':
      echo "<h2></h2>";
      break;
      default:
        schoolManagement();
      break;
    }
  } else {
    echo ("<h2>You have first to select the team you want to work on</h2>");
  }
}

closePage();

?>
