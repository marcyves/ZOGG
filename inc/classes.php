<?php

class Current_Group {

  var $id;
  var $CampusId;
  var $CampusName;
  var $ProgramId;
  var $ProgramName;
  var $CourseId;
  var $CourseName;
  var $GroupId;
  var $GroupName;

  function __construct()
  {
      $id = $this->getCurrentGroup();

      if ($id == 0 )
      {
        $this->id = $this->init_values();
      } else {
        $this->id = $this->restore_values($id);
      }
  }

  function init_values()
  {
    global $mysqli;

    $this->CampusId    = 0;
    $this->CampusName  = "";
    $this->ProgramId   = 0;
    $this->ProgramName = "";
    $this->CourseId    = 0;
    $this->CourseName  = "";
    $this->GroupId     = 0;
    $this->GroupName   = "";

    mysqli_query($mysqli,"DELETE FROM Current_Group");
    mysqli_query($mysqli,"INSERT INTO Current_Group (GroupId, GroupName, CourseId, CourseName, ProgramId, ProgramName, CampusId, CampusName) VALUES ('".$this->GroupId."', '".$this->GroupName."', '".$this->CourseId."', '".$this->CourseName."', '".$this->ProgramId."', '".$this->ProgramName."', '".$this->CampusId."', '".$this->CampusName."') ");
    $rc = mysqli_query($mysqli,"SELECT Id FROM Current_Group");
    list($id) = mysqli_fetch_row($rc);

    return $id;
  }

  function restore_values($id)
  {
    global $mysqli;

    $rc = mysqli_query($mysqli,"SELECT * FROM `Current_Group` WHERE id = $id");
    list($id, $this->GroupId, $this->GroupName, $this->CourseId, $this->CourseName, $this->ProgramId, $this->ProgramName, $this->CampusId, $this->CampusName) = mysqli_fetch_row($rc);

    return $id;
  }

  function getStatus()
  {
    if ($this->GroupId > 0)
    {
      return true;
    } else {
      return false;
    }

  }


  function getCurrentGroup()
  {
    global $mysqli;

    $rc = mysqli_query($mysqli,"SELECT Id FROM Current_Group");
    list($id) = mysqli_fetch_row($rc);

    return $id;
  }


  function setCampus($id)
  {
    global $mysqli;

    $rc = mysqli_query($mysqli, "SELECT CampusName FROM Campus WHERE CampusId = $id");
    list($name) = mysqli_fetch_row($rc);

    $sql = "UPDATE Current_Group SET CampusId = $id, CampusName = '$name' WHERE Id = ".$this->id;

    $this->CampusId = $id;
    $this->CampusName = $name;
    mysqli_query($mysqli, $sql);

    $this->setProgram(0);

  }

  function setProgram($id)
  {
    global $mysqli;

    if ($id == 0)
    {
      $name = "";
    } else {
      $sql = "SELECT ProgramName FROM Program WHERE ProgramId = $id";

      $rc = mysqli_query($mysqli, $sql);
      list($name) = mysqli_fetch_row($rc);
    }

    $this->ProgramId = $id;
    $this->ProgramName = $name;
    mysqli_query($mysqli,"UPDATE Current_Group SET  ProgramId = $id, ProgramName = '$name' WHERE Id = ".$this->id);

    $this->setCourse(0);

  }

  function setCourse($id)
  {
    global $mysqli;

    if ($id == 0)
    {
      $name = "";
    } else {
      $rc = mysqli_query($mysqli, "SELECT CourseName FROM Course WHERE CourseId = $id");
      list($name) = mysqli_fetch_row($rc);
    }

    $this->CourseId = $id;
    $this->CourseName = $name;
    mysqli_query($mysqli,"UPDATE Current_Group SET  CourseId = $id, CourseName = '$name' WHERE Id = ".$this->id);

    $this->setGroup(0);

  }

  function setGroup($id)
  {
    global $mysqli;

    if ($id == 0)
    {
      $name = "";
    } else {
      $rc = mysqli_query($mysqli, "SELECT GroupName FROM `Group` WHERE GroupId = $id");
      list($name) = mysqli_fetch_row($rc);
    }

    $this->GroupId = $id;
    $this->GroupName = $name;
    mysqli_query($mysqli,"UPDATE `Current_Group` SET  GroupId = $id, GroupName = '$name' WHERE Id = ".$this->id);

  }

  function getCurrentCourseDetails(){
    global $mysqli;

    $rc = mysqli_query($mysqli,"SELECT CampusId, CampusName, GroupId, GroupName, CourseId, CourseName, ProgramId, ProgramName FROM Current_Group");
    $row = mysqli_fetch_array($rc, MYSQLI_ASSOC);

    return $row;
  }

}
