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

echo '<div class="container">';
// echo '<div class="row">';
// echo '<div class="col-sm-3">';

$view = "td";

$course = $my_group->getCurrentCourseDetails();

switch ($view){
  case "td":
    // Now the list of administrative groups and the tasks for each one

    echo '<b>Group: '. $course['GroupName'] .'</b><ul>';
    $sql = 'SELECT  id, Name FROM `Job` WHERE CourseId = '. $course['CourseId'] .' ORDER BY sortOrder';
    $result = mysqli_query($mysqli, $sql);
    while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
      echo '<li><a href="'.$myScript.'.php?groupId='.$course['GroupId'].'&job=' . $row['id'] . '&course='.$course['CourseId'].'">'.$row['Name'].'</a></li>';
    }

    echo '</ul>';
    echo '</div>';
//    echo '</div>';
    mysqli_free_result($result);
  break;
  case "job":
  default:
  // Now the list of administrative groups and the tasks for each one
  $result = mysqli_query($mysqli, 'SELECT  id, Name FROM `Job` ORDER BY sortOrder');
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
    echo '<li class="list-group-item text-success">';
    echo '<a href="building.php?sub=remove&team='.$teamId.'&student=' . $row['ID'] . '&groupId='.$td.'&job='.$job.'&course='.$course.'">';
    echo '<i class="fas fa-trash-alt"></i>&nbsp;</a>';
    echo ucfirst(strtolower($row['Prenom'])) . " ";
    echo ucfirst(strtolower($row['NOM'])) . ' ';
    echo '</li>';
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
echo '<div class="container-fluid mb-3">
    <div class="row">';

echo '<div class="card text-white bg-primary mb-3" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Team creation</h5>
    <p class="card-text">.</p>';
echo "<form method='GET'>
<input type='text'   name='teamName'  value='?'>
<input type='hidden' name='step'   value='grading'>
<input type='hidden' name='sub'   value='create'>
<input type='hidden' name='groupId'     value='$td'>
<input type='hidden' name='course' value='$course'>
<input type='hidden' name='job'    value='$job'>
<input class='btn btn-secondary btn-sm' type='submit'               value='Create new team'>
</form>";
echo "<form method='GET'>
<input type='hidden' name='step'   value='grading'>
<input type='hidden' name='sub'   value='solo'>
<input type='hidden' name='groupId'     value='$td'>
<input type='hidden' name='course' value='$course'>
<input type='hidden' name='job'    value='$job'>
<input class='btn btn-secondary btn-sm' type='submit'               value='Create solo'>
</form>";
echo '</div>
</div>';

  $sql = 'SELECT DISTINCT Team.ID, `Team`.`TeamName`, `Team`.`Grade`, `Team`.`Comment`, `Team`.`JobId` FROM `Team`
  WHERE JobId = \''.$job.'\' AND groupId ='.$td.' ORDER BY TeamName';
  //DEBUG echo "<br>$sql<br>";
  $result = mysqli_query($mysqli, $sql);

//  echo '<div class="row">';

  while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
    echo '<div class="card text-white bg-secondary mb-3" style="width: 18rem;">
    <h5 class="card-title">Team: '. $row['TeamName'].'</h5>
  <div class="card-header">';
  if ($row['Grade'] != '') {
    echo '  Grade: ' . $row['Grade'];
  } else {
    echo "<form method='GET'>
    <input type='hidden' name='step'    value='building'>
    <input type='hidden' name='sub'    value='discard'>
    <input type='hidden' name='groupId' value='$td'>
    <input type='hidden' name='course'  value='$course'>
    <input type='hidden' name='job'     value='$job'>
    <input type='hidden' name='teamId'  value='".$row['ID']."'>
    <input class='btn btn-secondary btn-sm' type='submit' value='Discard'>
    </form>";
  }
  echo '</div>
  <ul class="list-group list-group-flush">';
  listFreeStudentsForEnroll($row['ID'], $td, $job, $course);
  listStudentsByTeam($row['ID'], $td, $job, $course);
  echo '</ul>
</div>';
  }

  displayFreeStudentsForEnroll( $td, $job, $course);
  echo "</div></div>";
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
// Loop with multiple forms
/*
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
*/
echo '<div class="grading">'.
"<form method='POST'>";

  while (($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
    echo '<label><span>'.$row['TeamName'].
    "</span>
    <input type='text' name='grade[".$row['ID']."]' size='4' value='".$row['Grade']."'>
    <textarea name='comment[".$row['ID']."]'>".$row['Comment']."</textarea></label>";
  }
  echo "<input type='hidden' name='step'    value='grading'>
      <input type='hidden' name='groupId' value='$td'>
      <input type='hidden' name='course'  value='$course'>
      <input type='hidden' name='job'     value='$job'>
      <input class='btn btn-primary btn-sm' type='submit' value='Set'>
      </form>".
      "</div>\n\n";


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

    echo '<div class="card border-danger mb-3" style="width: 18rem;">
  <div class="card-header text-danger">Standalone students</div>
  <div class="card-body text-danger">
    <ul class="list-group list-group-flush">';
    while (($student = mysqli_fetch_array($result, MYSQLI_ASSOC)) != NULL) {
      echo '<li class="list-group-item">';
      echo $student['NOM']." ".$student['Prenom'];
      echo "</li>";
    }
    echo '</ul>
  </div>
</div>';
  }
}

/*

*/
function listFreeStudentsForEnroll($teamId, $TD, $jobId, $course) {
  global $mysqli;

  $sql = 'SELECT ID, NOM, Prenom FROM Student S WHERE S.StudentGroupId = \''.$TD.'\' AND  S.ID NOT IN (SELECT IdStudent FROM Student S, StudentTeam X, Team T WHERE X.idTeam = T.ID AND T.JobId = \''.$jobId.'\' AND X.idStudent = S.ID AND S.StudentGroupId = \''.$TD.'\') ORDER BY NOM';
  $result = mysqli_query($mysqli, $sql);

  if (mysqli_num_rows($result) > 0) {
      echo "<li class='list-group-item'>
      <form method='GET'>
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
      <input class='btn btn-primary btn-sm' type='submit' value='enroll'>
      </form>
      </li>";
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

    echo '<table class="table">';
    echo '<tr>';
    echo '<th>Nom</th>';
    echo '<th>Prénom</th>';
    $sql = 'SELECT DISTINCT J.id, J.Name, J.weight FROM Job J WHERE J.CourseId = '.$rowCourse['CourseId'].' ORDER BY sortOrder';

    $result = mysqli_query($mysqli, $sql);
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
          <input type='submit' class='btn btn-primary btn-sm' value='Move'>
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

    echo '<table class="table">';
    echo '<tr>';
    echo '<th>Nom</th>';
    echo '<th>Prénom</th>';
    $result = mysqli_query($mysqli, 'SELECT id, Name, weight FROM Job WHERE CourseId = '.$rowCourse['CourseId'].' ORDER BY sortOrder');
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
          $tmpLine .= '<td>' . number_format($tmp_grade,2) . '</td>';
        } else {
          $tmpLine .= '<td>&nbsp;</td>';
        }
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
      echo '<table class="table">';
      echo '<tr>';
      echo '<th>Nom</th>';
      echo '<th>Prénom</th>';
      echo '<th>TD</th>';
      //		    echo '<th>Team</th>';
      $result = mysqli_query($mysqli, 'SELECT Name FROM Job ORDER BY sortOrder');
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

  function displayCurrentGroup(){
    global $mysqli, $my_group;

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

  function schoolManagement(){
    global $mysqli;

    $result1 = mysqli_query($mysqli, 'SELECT CampusId, CampusName FROM Campus ORDER BY CampusName');
    $lastJobId = 0;

    while (list($CampusId, $CampusName) = mysqli_fetch_row($result1)){
      echo '<div class="school-details">';
      echo '<h2>'.$CampusName.'</h2>';

      echo '<div class="school-details">';

      $result2 = mysqli_query($mysqli, "SELECT ProgramId, ProgramName FROM Program WHERE ProgramCampusId = '$CampusId' ORDER BY ProgramName ;");
      if (mysqli_num_rows($result2)>0){
        while (list($ProgramId, $ProgramName) = mysqli_fetch_row($result2)){

          echo '<h3>'.$ProgramName.' Course List</h3>';

          echo '<div class="program-details">';

          $result3 = mysqli_query($mysqli, "SELECT CourseId, CourseName, CourseYear, CourseSemester FROM Course WHERE CourseProgramId = '$ProgramId' ORDER BY CourseYear, CourseName ;");
          if (mysqli_num_rows($result3)>0){
            echo '<ul class="course-list">';
            while (list($id, $name, $year, $semester) = mysqli_fetch_row($result3)){
              echo '<li><form method="post">
              <input type="hidden" name="CourseId" value="'.$id.'">
              <button type="submit" name="cmd" value="discardCourse" class="btn btn-danger btn-xs">delete</button>
              <button type="submit" name="cmd" value="updateCourse" class="btn btn-warning btn-xs">update</button>'.
              '<input type="text" name="CourseName" size="30" value="'.$name.'"> (<input type="text" name="CourseYear" size="4" value="'.$year.'">, <input type="text" name="CourseSemester" size="5" value="'.$semester.'">)<br>
              </form></li>';
            }
            echo '</ul>';

          } else {
            echo '<form method="post">The program is empty, you can <input type="submit" value="delete"> it.<br>
            <input type="hidden" name="ProgramId" value="'.$ProgramId.'">
            <input type="hidden" name="cmd" value="discardProgram">
            </form>';
          }
          echo '<h2>New Course</h2>
          <form method="post">'.
          '<input type="text" name="CourseName" size="30"> (<input type="text" name="CourseYear" size="4">, <input type="text" name="CourseSemester" size="5">)<br>
          <input type="hidden" name="cmd" value="newCourse">
          <input type="hidden" name="CampusId" value="'.$CampusId.'">
          <input type="hidden" name="ProgramId" value="'.$ProgramId.'">
          <input type="submit" value="Create New Course">
          </form>';
          echo'</div>'; // Close program-details
        }
      } else {
        echo '<form method="post">This school or campus is empty, you can <input type="submit" value="delete"> it.<br>
        <input type="hidden" name="CampusId" value="'.$CampusId.'">
        <input type="hidden" name="cmd" value="discardCampus">
        </form>';
      }
      echo '</div>';

      echo '<div class="school-details">';
      echo '<h2>New Program</h2>'.
      '<form method="post">'.
      '<input type="text" name="ProgramName" size="30"><br>
      <input type="hidden" name="CampusId" value="'.$CampusId.'">
      <input type="hidden" name="cmd" value="newProgram">
      <input type="submit" value="Create New Program">
      </form>';
      echo '</div>';
      echo '</div>';
    }

    // End loop, let admin create a school
    echo '<div class="school-details">';
    echo '<h2>New School/Campus</h2>
    <form method="post">
    <input type="text" name="campusName" size="30"><br>
    <input type="hidden" name="cmd" value="newCampus">
    <input type="submit" value="Create New School">
    </form>';
    echo '</div>';


  }

  function assignmentManagement($userLevel){
    global $mysqli, $loggedInUser;

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
        $sql = "SELECT `Name` FROM `Job` WHERE id =  '".$_POST['AssignmentId']."'";
        $result = mysqli_query($mysqli, $sql);
        if(mysqli_num_rows($result) == 1){
          list($Name) = mysqli_fetch_row($result);
          $sql = "UPDATE `Job` SET Name = '".$_POST['AssignmentName']."' ,weight = ".$_POST['AssignmentWeight'].", sortOrder = ".$_POST['AssignmentSortOrder']." WHERE id =  '".$_POST['AssignmentId']."'";
          $result = mysqli_query($mysqli, $sql);
          echo "<h2>Assignment $Name updated.</h2>";
        } else {
          echo "<h2>Invalid Assignment id, nothing to modify.</h2>";
        }
      break;
      case 'discardAssignment':
        $sql = "SELECT `Name` FROM `Job` WHERE id =  '".$_POST['AssignmentId']."'";
        $result = mysqli_query($mysqli, $sql);
        if(mysqli_num_rows($result) == 1){
          list($Name) = mysqli_fetch_row($result);
          $sql = "DELETE FROM `Job` WHERE id =  '".$_POST['AssignmentId']."'";
          $result = mysqli_query($mysqli, $sql);
          echo "<h2>Assignment $Name deleted.</h2>";
        } else {
          echo "<h2>Invalid Assignment id, nothing deleted.</h2>";
        }
      break;
      default:
      // nothing here
      break;
    }

    switch ($userLevel) {
      case 1:
      // Nothing here yet
        $sql = "SELECT CourseId, CourseName, CourseYear, CourseSemester, ProgramName, CampusName".
                " FROM Course C, Campus S, Program P ".
                " WHERE 0;";
      break;

      case 2:
      $sql = "SELECT C.CourseId, C.CourseName, C.CourseYear, C.CourseSemester, ProgramName, CampusName".
            " FROM Course C, Campus S, Program P ,course_x_professor X".
            " WHERE CourseProgramId = P.ProgramId  AND ProgramCampusId = CampusId AND C.CourseId = X.CourseId AND X.ProfessorId = '".$loggedInUser->user_id."' ".
            "ORDER BY CampusName, ProgramName, CourseYear, CourseName ;";

      break;

      case 3:
        $sql = "SELECT CourseId, CourseName, CourseYear, CourseSemester, ProgramName, CampusName".
              " FROM Course C, Campus S, Program P ".
              " WHERE CourseProgramId = ProgramId  AND ProgramCampusId = CampusId ORDER BY CampusName, ProgramName, CourseYear, CourseName ;";
      break;

      default:
        // code...
        break;
    }
    $result1 = mysqli_query($mysqli, $sql);
    $lastJobId = 0;

    $previousCampusName = "";
    $previousProgramName = "";
    $flagTitle = FALSE;

    while (list($CourseId, $CourseName, $CourseYear, $CourseSemester, $ProgramName, $CampusName) = mysqli_fetch_row($result1)){
      if($ProgramName != $previousProgramName) {
        if ($flagTitle){
          echo '</div>';
        }
        echo '<div class="school-details">';
        echo "<h2>$CampusName > $ProgramName </h2>";
        $previousProgramName = $ProgramName;
        $flagTitle = TRUE;
      }
      echo "<h2>$CourseName ($CourseYear, $CourseSemester)</h2>";

      $result2 = mysqli_query($mysqli, "SELECT id, Name, weight, sortOrder FROM Job WHERE CourseId = '$CourseId' ORDER BY sortOrder");
      echo '<ul class="course-list">';
      while (list($JobId, $JobName, $JobWeigh, $JobSortOrder) = mysqli_fetch_row($result2)) {
        echo '<li><form method="post">
        <input type="hidden" name="AssignmentId" value="'.$JobId.'">
        <button type="submit" name="cmd" value="discardAssignment" class="btn btn-danger btn-xs">delete</button>
        <button type="submit" name="cmd" value="updateAssignment" class="btn btn-warning btn-xs">update</button>'.
        '<input type="text" name="AssignmentName" value="'.$JobName.'" size="30"> ('.
        '<input type="text" name="AssignmentWeight" value="'.$JobWeigh.'" size="8">) Order : '.
        '<input type="text" name="AssignmentSortOrder" value="'.$JobSortOrder.'" size="3"><br>
        </form></li>';
      }
      echo "</ul>";

      echo '<div class="school-details">';
      echo '<h2>New Assignment</h2>'.
      '<form method="post">'.
      '<input type="text" name="AssignmentName" size="30"> ('.
      '<input type="text" name="AssignmentWeight" size="8">) Order : '.
      '<input type="text" name="AssignmentSortOrder" size="3"><br>
      <input type="hidden" name="CourseId" value="'.$CourseId.'">
      <input type="hidden" name="cmd" value="newAssignment">
      <input type="submit" value="Create New Assignment">
      </form>';
      echo '</div>';
    }
    echo '</div>';
  }

  ?>
