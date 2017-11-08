<?php
/*
==============================================================================

Copyright (c) 2015 Marc Augier

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
// Parameters
//$maxGroupNumber = 9;

$my_group = new Current_Group;

$result = mysqli_query($mysqli, 'SELECT max(StudentGroupId) as groupNb FROM `Student`');
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$maxGroupNumber = $row['groupNb'];


function assignGradeToTeam($id, $grade, $comment){
  global $mysqli;

  $comment = str_replace("'","\'", $comment);
  $sql = 'UPDATE Team  SET Grade = \''.$grade.'\', Comment =\''.$comment.'\' WHERE ID = '.$id;
  $result = mysqli_query($mysqli, $sql);
}
function EnrollStudentInTeam($student, $team){
  global $mysqli;

  $result = mysqli_query($mysqli, 'INSERT INTO StudentTeam  (idStudent, idTeam) VALUES ('.$student.', '.$team.')');
}

function discardStudentFromTeam($student, $team){
  global $mysqli;

  $result = mysqli_query($mysqli, 'DELETE FROM StudentTeam  WHERE idStudent =  '.$student.' AND IdTeam = '.$team.'');
}

function discardTeam($team){
  global $mysqli;

  $result = mysqli_query($mysqli, 'DELETE FROM Team  WHERE ID = '.$team.'');
}

function discardStudentFromGroup($student, $team){
  global $mysqli;

  $result = mysqli_query($mysqli, 'DELETE FROM StudentTeam  WHERE idStudent =  '.$student.' AND IdTeam = '.$team.'');
}



/*
List TD groups
*/
function listTD($myScript) {
  global $mysqli, $maxGroupNumber, $my_group;

  /*  $myScript = "index";
  if ($cmd == 'AddTeams') {
  $myScript = "admin";
} else if ($cmd == 'AddMockTeams') {
$myScript = "admin";
}
*/

echo '<div class=container>';
//echo '<div class="row">';
echo '<div class="col-sm-3">';

$view = "td";

$course = $my_group->getCurrentCourseDetails();

switch ($view){
  case "td":
    // Now the list of administrative groups and the tasks for each one

    echo '<b>Group: '. $course['GroupName'] .'</b></br/>';
    $sql = 'SELECT  id, Name FROM `Job` WHERE CourseId = '. $course['CourseId'] .' ORDER BY Name';
    $result = mysqli_query($mysqli, $sql);
    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
      echo '<a href="'.$myScript.'.php?groupId='.$course['GroupId'].'&job=' . $row['id'] . '&course='.$course['CourseId'].'">'.$row['Name'].'</a><br/>';
    }

//    echo '</div>';
    echo '</div></div>';
    mysqli_free_result($result);
  break;
  case "job":
  default:
  // Now the list of administrative groups and the tasks for each one
  $result = mysqli_query($mysqli, 'SELECT  id, Name FROM `Job` ORDER BY Name');
  while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
    echo '<li>';
    echo '<b>'. $row['Name'] . '</b></br/>';
    for($i=$course['CourseMinTD'];$i<$course['CourseMaxTD']+1;$i++){
      echo '<a href="'.$myScript.'.php?td='.$i.'&job=' . $row['id'] . '&course='.$course['CourseId'].'"> TD: ' . $i . '</a><br/>';
    }
    echo '</li>';
  }
  echo '</ul></div>';
  mysqli_free_result($result);
}
}

/*
listStudentsByTeam($teamId)
List Students for a specific Team
*/
function listStudentsByTeam($teamId, $td, $job, $course) {
  global $mysqli;

  $result = mysqli_query($mysqli, 'SELECT Student.ID, Prenom, NOM  FROM `Student` , StudentTeam WHERE Student.ID = idStudent AND idTeam = \''.$teamId.'\' ORDER BY StudentGroupId');

  while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
    echo '<p>';
    echo ucfirst(strtolower($row['Prenom'])) . " ";
    echo ucfirst(strtolower($row['NOM'])) . ' ';
    echo '<a href="building.php?sub=remove&team='.$teamId.'&student=' . $row['ID'] . '&groupId='.$td.'&job='.$job.'&course='.$course.'">';
    echo '<img src="themes/default/images/delete.png">';
//    echo '<span class="delete">&nbsp;</span>';
    echo '</a></p>';
  }
  mysqli_free_result($result);
}

function GetJobName($id){
  global $mysqli;

  $result = mysqli_query($mysqli, 'SELECT  Name FROM Job  WHERE  id = \''.$id.'\'');
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

  return $row['Name'];
}
function createTeam($name, $groupId, $jobId){
  global $mysqli;
  $sql = "INSERT INTO Team (teamName, GroupId, jobId) VALUES ('".$name."', '".$groupId."', '".$jobId."') ";
  echo "<br/>$sql<br/>";
  mysqli_query($mysqli,$sql);
}

function createSoloTeam($groupId, $jobId){
  global $mysqli;
  $sql ="SELECT Prenom, NOM from Student WHERE StudentGroupId = '".$groupId."'";
  $result = mysqli_query($mysqli,$sql);
  while ((list($prenom, $nom) = mysqli_fetch_row($result))) {
    $name = "$nom $prenom";
    $sql = "INSERT INTO Team (teamName, GroupId, jobId) VALUES ('".$name."', '".$groupId."', '".$jobId."') ";
    mysqli_query($mysqli,$sql);
  }
}

/*

*/
function listTeamsAvailable($cmd, $td, $job, $course) {
  global $mysqli;
/*
  Parameters
  ----------
  cmd: calling command, building or grading
  td: TD #
  job: job id
  course: course id

  Displays the list of teams for a job in a course
  - building
  Allows to add more participants to a team.
  Allows to create new teams.

  - grading
  Set grades to teams
*/
  echo "<h4>Task: <b>".GetJobName($job)."</b></h4>";
  if ($cmd == 'building') {
    listTeamsForBuilding($td, $job, $course);
  } else if ($cmd == 'grading') {
    listTeamsForGrading($td, $job, $course);
  }
}

/*

*/
function listTeamsForBuilding($td, $job, $course) {
  global $mysqli;
/*
  Parameters
  ----------
  td: TD #
  job: job id
  course: course id

  Displays the list of teams for a job in a course
  - building
  Allows to add more participants to a team.
  Allows to create new teams.

*/
    echo '<div class=container>';
    echo '<div class="col-sm-3">';
    echo "<form method='GET'>
    <input type='text'   name='teamName'  value='?'>
    <input type='hidden' name='step'   value='grading'>
    <input type='hidden' name='sub'   value='create'>
    <input type='hidden' name='groupId'     value='$td'>
    <input type='hidden' name='course' value='$course'>
    <input type='hidden' name='job'    value='$job'>
    <input class='button' type='submit'               value='Create new team'>
    </form>";
    echo "<form method='GET'>
    <input type='hidden' name='step'   value='grading'>
    <input type='hidden' name='sub'   value='solo'>
    <input type='hidden' name='groupId'     value='$td'>
    <input type='hidden' name='course' value='$course'>
    <input type='hidden' name='job'    value='$job'>
    <input class='button' type='submit'               value='Create solo'>
    </form>";
    echo '</div>';

  $sql = 'SELECT DISTINCT Team.ID, `Team`.`TeamName`, `Team`.`Grade`, `Team`.`Comment`, `Team`.`JobId` FROM `Team`
  WHERE JobId = \''.$job.'\' AND groupId ='.$td.' ORDER BY TeamName';
  //DEBUG echo "<br>$sql<br>";
  $result = mysqli_query($mysqli, $sql);

//  echo '<div class="row">';

  while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
    echo '<div class="col-sm-3">';
    echo '<h4>' . $row['TeamName'];
    echo '</h4>';
    if ($row['Grade'] != '') {
      echo '  Grade: ' . $row['Grade'];
    } else {
      echo "<p>
      <form method='GET'>
      <input type='hidden' name='step'    value='building'>
      <input type='hidden' name='sub'    value='discard'>
      <input type='hidden' name='groupId' value='$td'>
      <input type='hidden' name='course'  value='$course'>
      <input type='hidden' name='job'     value='$job'>
      <input type='hidden' name='teamId'  value='".$row['ID']."'>
      <input class='button' type='submit' value='Discard'>
      </form></p>";
    }

    listFreeStudentsForEnroll($row['ID'], $td, $job, $course);
    listStudentsByTeam($row['ID'], $td, $job, $course);

    echo "</div>";
  }
  echo "</div>";
//  echo "</div>";

  displayFreeStudentsForEnroll( $td, $job, $course);
  mysqli_free_result($result);
}


/*

*/
function listTeamsForGrading($td, $job, $course) {
  global $mysqli;
/*
  Parameters
  ----------
  td: TD #
  job: job id
  course: course id

  Displays the list of teams for a job in a course
  - grading
  Set grades to teams
*/
  $sql = 'SELECT DISTINCT Team.ID, `Team`.`TeamName`, `Team`.`Grade`, `Team`.`Comment`, `Team`.`JobId` FROM `Team`
  WHERE JobId = \''.$job.'\' AND groupId ='.$td.' ORDER BY TeamName';
  //DEBUG echo "<br>$sql<br>";
  $result = mysqli_query($mysqli, $sql);

  while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
    echo '<div class="grading">'.
    "<form method='GET'><label>
    <span>".
    $row['TeamName'].
    "</span>
    <input type='text' name='grade' size='4' value='".$row['Grade']."'>
    <textarea name='comment'>".$row['Comment']."</textarea>
    <input type='hidden' name='step'    value='grading'>
    <input type='hidden' name='groupId' value='$td'>
    <input type='hidden' name='course'  value='$course'>
    <input type='hidden' name='job'     value='$job'>
    <input type='hidden' name='teamId'  value='".$row['ID']."'>
    <input class='tiny-button' type='submit' value='Set'>
    </form></label>".
    "</div>\n\n";
  }

  displayFreeStudentsForEnroll( $td, $job, $course);
  mysqli_free_result($result);
}

/*

*/
function displayFreeStudentsForEnroll($groupId, $jobId, $course) {
  global $mysqli;

  $sql = 'SELECT ID, NOM, Prenom FROM Student S WHERE S.StudentGroupId = \''.$groupId.'\' AND  S.ID NOT IN (SELECT IdStudent FROM Student S, StudentTeam X, Team T WHERE X.idTeam = T.ID AND T.JobId = \''.$jobId.'\' AND X.idStudent = S.ID AND S.StudentGroupId = \''.$groupId.'\') ORDER BY NOM';
  $result = mysqli_query($mysqli, $sql);

  if (mysqli_num_rows($result)>0) {
    echo '<div class="col-sm-3">';
    echo "<h2>Students in this group without a team</h2>";
    echo "<ul>";

    while (($student = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
      echo "<li>".$student['NOM']." ".$student['Prenom']."</li>";
    }
    echo "</ul>";
    echo "</div>";
  }
}

/*

*/
function listFreeStudentsForEnroll($teamId, $TD, $jobId, $course) {
  global $mysqli;

  $sql = 'SELECT ID, NOM, Prenom FROM Student S WHERE S.StudentGroupId = \''.$TD.'\' AND  S.ID NOT IN (SELECT IdStudent FROM Student S, StudentTeam X, Team T WHERE X.idTeam = T.ID AND T.JobId = \''.$jobId.'\' AND X.idStudent = S.ID AND S.StudentGroupId = \''.$TD.'\') ORDER BY NOM';
  $result = mysqli_query($mysqli, $sql);

  if (mysqli_num_rows($result) > 0) {
      echo "<form method='GET'>
      <input type='hidden' name='cmd' value='building'>
      <input type='hidden' name='teamId' value='$teamId'>
      <input type='hidden' name='groupId' value='$TD'>
      <input type='hidden' name='job' value='$jobId'>
      <input type='hidden' name='course' value='$course'>
      <select name='studentId'>";

      while (($student = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
        echo "<option value='".$student['ID']."' >".$student['NOM']." ".$student['Prenom']."</option>";
      }
      echo "</select>
      <input class='button' type='submit' value='enroll'>
      </form>";
  }

}
/*
Display all teams in all TD groups
*/
function listAllTeams() {
  global $mysqli;

  $result = mysqli_query($mysqli, 'SELECT DISTINCT TD FROM `Student` ORDER BY StudentGroupId');

  while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
    echo '<h2>Teams in TD : ' . $row['StudentGroupId'] . '</h2>';
    echo '<div class="post">';
    listTeams($row['StudentGroupId']);
    echo '</div>';
  }
  mysqli_free_result($result);
}

/*
Display the list of teams in a specific TD
*/
function listGroupDetails($id){
  listTeams($id);
}
function listTeams($id) {
  global $mysqli;

  $result = mysqli_query($mysqli, 'SELECT `Student`.`Prenom`, `Student`.`NOM`, `Team`.`TeamName`, `Job`.`Name` , Grade, Student.StudentGroupId
    FROM `StudentTeam`, `Student`, `Team`, `Job`
    WHERE `StudentTeam`.`IdStudent` = `Student`.`ID` AND `StudentTeam`.`IdTeam` = `Team`.`ID` AND  StudentGroupId = \''.$id.'\' '
    . 'ORDER BY Job.id, TeamName');

    $currentTeam = '';

    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
      if ($currentTeam != $row['TeamName'])
      {
        if ($currentTeam != '')
        {
          echo '</table>';
        }
        $currentTeam = $row['TeamName'];

        echo "<h3>TEAM : ".$row['TeamName']."</h3>";
        echo '<table>';
        echo '<tr>';
        echo '<th>Prenom</th>';
        echo '<th>Nom</th>';
        echo '<th>Real Group</th>';
        echo '<th>Work</th>';
        echo '<th>Grade</th>';
        echo '</tr>';
      }
      echo '<tr>';
      echo '<td>' . $row['Prenom'] . '</td>';
      echo '<td>' . $row['NOM'] . '</td>';
      echo '<td>' . $row['StudentGroupId'] . '</td>';
      echo '<td>' . $row['Name'] . '</td>';
      echo '<td>' . $row['Grade'] . '</td>';
      echo '</tr>';
    }
    mysqli_free_result($result);
    echo '</table>';
  }

  /*
  Display Students for a Group
  */
  function displayStudentsbyGroup() {
    global $mysqli, $my_group;

    $rowCourse = $my_group->getCurrentCourseDetails();

    echo '<table border=1>';
    echo '<tr>';
    echo '<th>Nom</th>';
    echo '<th>Prénom</th>';
    $result = mysqli_query($mysqli, 'SELECT id, Name, weight FROM Job ORDER BY id');
    $lastJobId = 0;
    while (list($id, $name, $weight) = mysqli_fetch_row($result)){
      echo '<th>'.$name.' ('.$weight.')</th>';
      $jobIdList[$lastJobId] = $id;
      $jobWeightList[$lastJobId] = $weight;
      $lastJobId++;
    }
    echo '<th>Grade</th>';
    echo '<th colspan="2">Command</th>';
    echo '</tr>';
    //
    // Loop to Display Student Information
    $sql = 'SELECT `ID`, `NOM`, `Prenom` FROM `Student`'
    . ' WHERE `StudentGroupId` = \''.$rowCourse['GroupId'].'\''
    . ' ORDER BY `NOM`, `Prenom`';
    $result = mysqli_query($mysqli, $sql);
    $finalGrade = 0;
    $currentStudentId = 0;
    $tmpLine = '';
    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
      if ($row['ID'] != $currentStudentId){
        //		                $finalGrade = round($finalGrade/3,1);
        //		                $tmpLine .= '<td>' . $finalGrade . '</td>';
        $finalGrade = 0;
        $finalWeight = 0;
        $currentStudentId = $row['ID'];

        $tmpLine = '</tr>'
                    . '<td>' . $row['NOM']    . '</td>'
                    . '<td>' . $row['Prenom'] . '</td>';

        //Loop to display each grade of one student
        for($i=0;$i < $lastJobId; $i++){

          $sqlGrade = 'SELECT `Team`.`Grade` '
          . 'FROM `StudentTeam`,  `Team` '
          . 'WHERE `StudentTeam`.`IdStudent` =  \''. $currentStudentId.'\' '
          . 'AND `StudentTeam`.`IdTeam` = `Team`.`ID` '
          . 'AND `Team`.`JobId` = '.$jobIdList[$i].' ';

          $rcGrade = mysqli_query($mysqli, $sqlGrade);
          $rowGrade = mysqli_fetch_array($rcGrade, MYSQLI_ASSOC);

          $tmpLine .= '<td>' . $rowGrade['Grade'] . '</td>';
            if ($rowGrade['Grade'] != '') {
              $finalGrade += $rowGrade['Grade']*$jobWeightList[$i];
              $finalWeight += $jobWeightList[$i];
            }
          mysqli_free_result($rcGrade);
        }
        if ($finalWeight != 0) {
          $tmp_grade = $finalGrade/$finalWeight;
        } else {
          $tmp_grade = "&nbsp;";
        }
        $tmpLine .= '<td>' . $tmp_grade . '</td>';
        if ($tmp_grade > 0 ) {
          $tmpLine .= '<td>&nbsp;</td><td>&nbsp;</td>';
        } else {
          $tmpLine .= "<td>
          <form method='GET'>
          <select name='groupId'>
          <option></option>
          </select>
          </form>
          <input type='submit' value='Move'>
          </td>";
          $tmpLine .= '<td><a href="?cmd=discard&studentId='.$currentStudentId.'&groupId='.$rowCourse['GroupId'].'">Discard</a></td>';
        }
        $tmpLine .= '</tr>';
        echo $tmpLine;
      }
    }
    mysqli_free_result($result);
    echo '</table>';
  }

  /*
  Display Grades for a Group
  */
  function displayGroupGrades() {
    global $mysqli, $my_group;

    $rowCourse = $my_group->getCurrentCourseDetails();

    echo '<table border=1>';
    echo '<tr>';
    echo '<th>Nom</th>';
    echo '<th>Prénom</th>';
    $result = mysqli_query($mysqli, 'SELECT id, Name, weight FROM Job ORDER BY id');
    $lastJobId = 0;
    while (list($id, $name, $weight) = mysqli_fetch_row($result)){
      echo '<th>'.$name.' ('.$weight.')</th>';
      $jobIdList[$lastJobId] = $id;
      $jobWeightList[$lastJobId] = $weight;
      $lastJobId++;
    }
    echo '<th>Grade</th>';
    echo '</tr>';
    //
    // Loop to Display Student Information
    $sql = 'SELECT `ID`, `NOM`, `Prenom` FROM `Student`'
    . ' WHERE `StudentGroupId` = \''.$rowCourse['GroupId'].'\''
    . ' ORDER BY `NOM`, `Prenom`';
    $result = mysqli_query($mysqli, $sql);
    $finalGrade = 0;
    $currentStudentId = 0;
    $tmpLine = '';
    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
      if ($row['ID'] != $currentStudentId){
        //		                $finalGrade = round($finalGrade/3,1);
        //		                $tmpLine .= '<td>' . $finalGrade . '</td>';
        $finalGrade = 0;
        $finalWeight = 0;
        $currentStudentId = $row['ID'];
        $tmpLine = '</tr>'
        . '<td>' . $row['NOM'] . '</td>'
        . '<td>' . $row['Prenom'] . '</td>';
        //Loop to display each grade of one student
        for($i=0;$i < $lastJobId; $i++){
          $sqlGrade = 'SELECT `Team`.`Grade` '
          . 'FROM `StudentTeam`,  `Team` '
          . 'WHERE `StudentTeam`.`IdStudent` =  \''. $currentStudentId.'\' '
          . 'AND `StudentTeam`.`IdTeam` = `Team`.`ID` '
          . 'AND `Team`.`JobId` = '.$jobIdList[$i].' ';
          $rcGrade = mysqli_query($mysqli, $sqlGrade);
          $rowGrade = mysqli_fetch_array($rcGrade, MYSQLI_ASSOC);
            $tmpLine .= '<td>' . $rowGrade['Grade'] . '</td>';
            if ($rowGrade['Grade'] != '') {
              $finalGrade += $rowGrade['Grade']*$jobWeightList[$i];
              $finalWeight += $jobWeightList[$i];
            }
          mysqli_free_result($rcGrade);
        }
        if ($finalWeight != 0) {
          $tmp_grade = $finalGrade/$finalWeight;
        } else {
          $tmp_grade = "&nbsp;";
        }
        $tmpLine .= '<td>' . number_format($tmp_grade,2) . '</td>';
        $tmpLine .= '</tr>';
        echo $tmpLine;
      }
    }
    mysqli_free_result($result);
    echo '</table>';
  }

  /*
  Display everything
  */
  function listAll() {
    global $mysqli;

    // First loop on Courses
    $rcCourse= mysqli_query($mysqli, 'SELECT CourseId, CourseName FROM Course');
    while (($rowCourse = mysqli_fetch_array($rcCourse, MYSQLI_ASSOC)) != NULL) {
      echo "<h2>Course: ".$rowCourse['CourseName']."</h2>";
      //Now display result table for students in this course
      echo '<table border=1>';
      echo '<tr>';
      echo '<th>Nom</th>';
      echo '<th>Prénom</th>';
      echo '<th>TD</th>';
      //		    echo '<th>Team</th>';
      $result = mysqli_query($mysqli, 'SELECT Name FROM Job ORDER BY id');
      while (list($name) = mysqli_fetch_row($result)){
        echo '<th>'.$name.'</th>';
      }
      echo '<th>Grade</th>';
      echo '</tr>';
      //Query with total for all taks
      /*
      $sql = 'SELECT `Student`.`ID`, `Student`.`NOM`, `Student`.`Prenom`,  `Student`.`TD`, Team.JobId,  sum(`Team`.`Grade`) as Grade '
      . 'FROM `StudentTeam`, `Student`, `Team` '
      . 'WHERE `StudentTeam`.`IdStudent` = `Student`.`ID` '
      . 'AND `StudentTeam`.`IdTeam` = `Team`.`ID` '
      . 'AND `Student`.`CourseId` = \''.$rowCourse['CourseId'].'\''
      . 'GROUP BY Team.JobId, `Student`.`ID`, `Student`.`NOM`, `Student`.`Prenom`, `Student`.`TD` '
      . 'ORDER BY   `Student`.`NOM`, `Student`.`Prenom`, Team.JobId';

      $sql = 'SELECT `Student`.`ID`, `Student`.`NOM`, `Student`.`Prenom`,  `Student`.`TD`, Team.JobId,  `Team`.`Grade` '
      . 'FROM `StudentTeam`, `Student`, `Team` '
      . 'WHERE `StudentTeam`.`IdStudent` = `Student`.`ID` '
      . 'AND `StudentTeam`.`IdTeam` = `Team`.`ID` '
      . 'AND `Student`.`CourseId` = \''.$rowCourse['CourseId'].'\''
      . 'ORDER BY   `Student`.`NOM`, `Student`.`Prenom`, Team.JobId';

      */
      //
      // Loop to Display Student Information
      $sql = 'SELECT `ID`, `NOM`, `Prenom`, `StudentGroupId` FROM `Student`, `Group`'
      . ' WHERE `GroupCourseId` = \''.$rowCourse['CourseId'].'\' AND `StudentGroupId` = `GroupId`'
      . ' ORDER BY   `Student`.`NOM`, `Student`.`Prenom`';
      $result = mysqli_query($mysqli, $sql);
      $finalGrade = 0;
      $currentStudentId = 0;
      $tmpLine = '';
      while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
        if ($row['ID'] != $currentStudentId){
          //		                $finalGrade = round($finalGrade/3,1);
          //		                $tmpLine .= '<td>' . $finalGrade . '</td>';
          $finalGrade = 0;
          $currentStudentId = $row['ID'];
          $tmpLine = '</tr>'
          . '<td>' . $row['NOM'] . '</td>'
          . '<td>' . $row['Prenom'] . '</td>'
          . '<td>' . $row['StudentGroupId'] . '</td>';
          //Loop to display each grade of one student
          $sqlGrade = 'SELECT `Team`.`JobId`,  `Team`.`Grade` '
          . 'FROM `StudentTeam`,  `Team` '
          . 'WHERE `StudentTeam`.`IdStudent` =  \''. $currentStudentId.'\' '
          . 'AND `StudentTeam`.`IdTeam` = `Team`.`ID` ';
          $rcGrade = mysqli_query($mysqli, $sqlGrade);
          $i = 0;
          while (($rowGrade = mysqli_fetch_array($rcGrade, MYSQLI_ASSOC)) != NULL) {
            if ($rowGrade['Grade'] != '') {
              $tmpLine .= '<td>' . $rowGrade['Grade'] . '</td>';
              $i++;
              $finalGrade += $rowGrade['Grade'];
            }
          }
          mysqli_free_result($rcGrade);
          $tmpLine .= '</tr>';
          echo $tmpLine;
        }
      }
      mysqli_free_result($result);
      echo '</table>';
    }
    mysqli_free_result($rcCourse);
  }

  function buildSelect($label,$name, $col1, $col2, $sql) {
    global $mysqli;

    $tmp = "<form method='post'>$label : <select name='$col1'>";

    $rc = mysqli_query($mysqli, $sql);
    while (($row = mysqli_fetch_array($rc, MYSQLI_ASSOC)) != NULL) {
      if ($row[$col2] == $name) {
        $sel = "selected";
      } else {
        $sel = "";
      }
          $tmp .= "<option value='".$row[$col1]."' $sel>".$row[$col2]."</option>";
    }
    $tmp .= "</select>
    <input type='submit' value='Change'>
              </form>";

    mysqli_free_result($rc);
    return $tmp;
  }

  function displayCurrentGroup(){
    global $mysqli, $my_group;

    // print_r($_POST);

    if (isset($_POST['CampusId'])) {
      $my_group->setCampus($_POST['CampusId']);
    }
    if (isset($_POST['ProgramId'])) {
      $my_group->setProgram($_POST['ProgramId']);
    }
    if (isset($_POST['CourseId'])) {
      $my_group->setCourse($_POST['CourseId']);
    }
    if (isset($_POST['GroupId'])) {
      $my_group->setGroup($_POST['GroupId']);
    }

    $tmp = "<div class='breadcrumb'>" . selectCampus("Campus", $my_group->CampusName);

    if ($my_group->CampusId > 0) {
      $tmp .= selectProgram("Programme", $my_group->ProgramName, $my_group->CampusId);
      if ($my_group->ProgramId > 0) {
        $tmp .= selectCourse("Cours", $my_group->CourseName, $my_group->ProgramId);
        if ($my_group->CourseId > 0 ) {
          $tmp .= selectGroup("Groupe", $my_group->GroupName, $my_group->CourseId);
        }
      }
    }
    $tmp .= "</div>";

    return $tmp;
  }


  function selectCampus($label, $name) {
    $sql = 'SELECT CampusId, CampusName FROM Campus';
    return buildSelect($label, $name, 'CampusId', 'CampusName', $sql);
  }

  function selectProgram($label, $name, $id) {
    $sql = 'SELECT ProgramId, ProgramName FROM Program WHERE ProgramCampusId = '.$id.' ORDER BY ProgramName';
    return buildSelect($label, $name, 'ProgramId', 'ProgramName', $sql);
  }
  function selectCourse($label, $name, $id) {
    $sql = "SELECT CourseId, CourseName, CourseYear, CourseSemester FROM Course WHERE CourseProgramId = $id ORDER BY CourseName";
    return buildSelect($label, $name, 'CourseId', 'CourseName', $sql);
  }
  function selectGroup($label, $name, $id) {
    $sql = "SELECT GroupId, GroupName FROM `Group` WHERE GroupCourseId = $id ORDER BY GroupName";
    return buildSelect($label, $name, 'GroupId', 'GroupName', $sql);
  }

  ?>
