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
    break;
    case 'newProgram':
      $sql = "INSERT  INTO Program  (ProgramName, ProgramCampusId) VALUES ('".$_POST['ProgramName']."', '".$_POST['CampusId']."')";
      $result = mysqli_query($mysqli, $sql);
      echo "<h2>Program ".$_POST['ProgramName']." created</h2>";
    break;
    case 'newCourse':
      $sql = "INSERT  INTO Course  (CourseName, CourseProgramId, CourseYear, CourseSemester) VALUES ('".$_POST['CourseName']."', '".$_POST['ProgramId']."', '".$_POST['CourseYear']."', '".$_POST['CourseSemester']."')";
      echo "<p>$sql</p>";
      $result = mysqli_query($mysqli, $sql);
      echo "<h2>Course ".$_POST['CourseName']." (".$_POST['CourseYear'].") created</h2>";
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
      $sql = "SELECT `CourseName` FROM `Course` WHERE CourseId =  '".$_POST['CourseId']."'";
      $result = mysqli_query($mysqli, $sql);
      if(mysqli_num_rows($result) == 1){
        list($CourseName) = mysqli_fetch_row($result);
        $sql = "UPDATE `Course` SET CourseName = '".$_POST['CourseName']."' , CourseYear = '".$_POST['CourseYear']."' , CourseSemester = '".$_POST['CourseSemester']."' WHERE CourseId =  '".$_POST['CourseId']."'";
        $result = mysqli_query($mysqli, $sql);
        echo "<h2>CourseId $CourseName updated.</h2>";
      } else {
        echo "<h2>Invalid CourseId, nothing to update.</h2>";
      }
    break;
    case 'updateProgram':
    echo "<h2></h2>";
    break;
    case 'discardCampus':
      $sql = "SELECT `CampusName` FROM `Campus` WHERE CampusId =  '".$_POST['CampusId']."'";
      $result = mysqli_query($mysqli, $sql);
      if(mysqli_num_rows($result) == 1){
        list($CampusName) = mysqli_fetch_row($result);
        $sql = "DELETE FROM `Campus` WHERE CampusId =  '".$_POST['CampusId']."'";
        $result = mysqli_query($mysqli, $sql);
        echo "<h2>Campus $CampusName deleted.</h2>";
      } else {
        echo "<h2>Invalid Campus id, nothing deleted.</h2>";
      }
    break;
    case 'discardProgram':
      $sql = "SELECT `ProgramName` FROM `Program` WHERE ProgramId =  '".$_POST['ProgramId']."'";
      $result = mysqli_query($mysqli, $sql);
      if(mysqli_num_rows($result) == 1){
        list($ProgramName) = mysqli_fetch_row($result);
        $sql = "DELETE FROM `Program` WHERE ProgramId =  '".$_POST['ProgramId']."'";
        $result = mysqli_query($mysqli, $sql);
        echo "<h2>Progam $ProgramName deleted.</h2>";
      } else {
        echo "<h2>Invalid program id, nothing deleted.</h2>";
      }
    break;
    case 'discardCourse':
      $sql = "SELECT `CourseName` FROM `Course` WHERE CourseId =  '".$_POST['CourseId']."'";
      $result = mysqli_query($mysqli, $sql);
      if(mysqli_num_rows($result) == 1){
        list($CourseName) = mysqli_fetch_row($result);
        $sql = "DELETE FROM `Course` WHERE CourseId =  '".$_POST['CourseId']."'";
        $result = mysqli_query($mysqli, $sql);
        echo "<h2>CourseId $CourseName deleted.</h2>";
      } else {
        echo "<h2>Invalid CourseId, nothing deleted.</h2>";
      }
    break;
    default:
    // nothing here
    break;
  }
  schoolManagement();
}

closePage();

?>
