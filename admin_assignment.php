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

  openPage("Assignments Administration");

  print_r($_POST);
  if (isset($_POST['cmd'])) {
    $cmd = $_POST['cmd'];
  } else {
    $cmd = "";
  }
  switch ($cmd) {
    case 'newAssignment':
      $sql = "INSERT  INTO Job  (Name, weight, CourseId, sortOrder) VALUES ('".$_POST['AssignmentName']."', '".$_POST['AssignmentWeight']."', '".$_POST['CourseId']."', '".$_POST['AssignmentSortOrder']."')";
      $result = mysqli_query($mysqli, $sql);
      echo "<h2>Job ".$_POST['AssignmentName']." created</h2>";
    break;
    case 'updateAssignment':
       echo "<h2></h2>";
    break;
    case 'discardAssignment':
       echo "<h2></h2>";
    break;
    default:
    // nothing here
    break;
  }
  assignmentManagement();
}

closePage();

?>
