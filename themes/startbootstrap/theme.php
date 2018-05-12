<?php

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
    // Hugly
    if (isset($row['CourseYear'])){
      $tmp .= "<option value='".$row[$col1]."' $sel>".$row[$col2]."(".$row['CourseYear'].")</option>";
    } else {
      $tmp .= "<option value='".$row[$col1]."' $sel>".$row[$col2]."</option>";
    }
  }
  $tmp .= "</select>";
  if ($name != 'admin_init'){
    $tmp .= "&nbsp;<input  class='btn btn-outline-primary btn-sm' type='submit' value='Change'>";
  }
  $tmp .= "</form>&nbsp;";

  mysqli_free_result($rc);
  return $tmp;
}

function toolBarActiveLink($url){
  echo '<li class="nav-item active">
      <a class="nav-link" href="'.$url.'">Home <span class="sr-only">(current)</span></a>
    </li>';
}

function toolBarLink($url, $label, $icon){
/*
      fa-area-chart
      fa-table
      fa-wrench
      fa-file
      fa-sitemap
      fa-angle-left
      fa-sign-out
*/
  echo '<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a class="nav-link" href="'.$url.'">
                  <i class="fa fa-fw '.$icon.'"></i>
                  <span class="nav-link-text">'.$label.'</span>
                </a>
              </li>';
}


function openPage($title)
{
    global $websiteName, $websiteDescription, $template, $mysqli, $emailActivation, $loggedInUser, $INX_SIDE1, $INX_LINKS;
    global $errors;
    global $successes;

echo '<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>'.$websiteName.'</title>
  <!-- Bootstrap core CSS-->
  <link href="themes/startbootstrap/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="themes/startbootstrap/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="themes/startbootstrap/css/sb-admin.css" rel="stylesheet">'.
  "<link rel='stylesheet' href='themes/startbootstrap/style.css'>".
'</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">';

    if(isUserLoggedIn())
    {
      //Links for logged in user
      if ($loggedInUser->checkPermission(array(1)))
      {
        $text_group = "";
        $userLevel = "Student";
      }
      if ($loggedInUser->checkPermission(array(2)))
      {
        $userLevel = "Professor";
      }
      if ($loggedInUser->checkPermission(array(3)))
      {
        $text_group = "";
        $userLevel = "Admin";
      }

      $text = $loggedInUser->displayname." (<i>$userLevel</i>)";

      echo '<!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <a class="navbar-brand" href="index.php">'.$websiteDescription.'</a>
        <div class="collapse navbar-collapse" id="navbarNav">
        <a class="navbar-nav" href="user_settings.php">'.$text.'</a>
        </div>
          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">';

    toolBarLink('index.php', 'Home', 'fa-sitemap');
    if (isUserReady($loggedInUser->user_id))
     {
      	if ($loggedInUser->checkPermission(array(1)))
        { //Links for permission level 1 (student)
          toolBarLink('dropbox.php', 'DropBox', 'fa-file');
 		    }
		    if ($loggedInUser->checkPermission(array(2)))
        { //Links for permission level 2 (professor)
          toolBarLink('grading.php', 'Team Grading', 'fa-table');
          toolBarLink('building.php', 'Team Building','fa-table');
          toolBarLink('student.php', 'Student Management','fa-wrench');
          toolBarLink('admin_assignment.php', 'Assignment Management','fa-wrench');
		    }
        if ($loggedInUser->checkPermission(array(3)))
        { //Links for permission level 3 (admin)
          toolBarLink('admin.php', 'Campus and Courses Management','fa-wrench');
          toolBarLink('admin_assignment.php', 'Assignment Management','fa-wrench');
          toolBarLink('admin_configuration.php', 'Admin Configuration','fa-wrench');
          toolBarLink('admin_users.php', 'Admin Users','fa-wrench');
          toolBarLink('admin_permissions.php', 'Admin Permissions','fa-wrench');
          toolBarLink('admin_pages.php', 'Admin Pages','fa-wrench');
          toolBarLink('admin_init.php', 'Initialisation des comptes users','fa-wrench');
        }

     }
     toolBarLink('logout.php', 'Logout', 'fa-sign-out');

    echo '</ul>
        <ul class="navbar-nav sidenav-toggler">
          <li class="nav-item">
            <a class="nav-link text-center" id="sidenavToggler">
              <i class="fa fa-fw fa-angle-left"></i>
            </a>
          </li>
        </ul>';

    echo '<ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-toggle="modal" data-target="#exampleModal" href="logout.php">
            <i class="fa fa-fw fa-sign-out"></i>Logout</a>
        </li>
      </ul>
    </div>
  </nav>';
} else {
//Links for users not logged in

echo '  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.php">Home</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
          <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="forgot-password.php" data-parent="#exampleAccordion">
            <i class="fa fa-fw fa-wrench"></i>
            <span class="nav-link-text">Forgot Password</span>
          </a>
        </li>
      </ul>
    </div>
  </nav>';
}


  // Display error or success messages
  if (!empty($successes)) {
      echo "<h4 style='color: blue;'>" . $successes[0] . "</h4>";
  }
  if (!empty($errors)) {
      echo "<h4 style='color: red;'>" . $errors[0] . "</h4>";
  }
  echo '<div class="content-wrapper">
      <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index.php">ZOGG</a>
          </li>
          <li class="breadcrumb-item active">Blank Page</li>
        </ol>';
        if ($loggedInUser->checkPermission(array(2)))
        {
          echo displayCurrentGroup();
        }


        echo '
        <div class="row">
          <div class="col-12">';

  echo '<h1><img src="themes/default/images/examples.png" alt="examples" />'.
              $title.'</h1>';

}

function closePage()
{
    echo '</div>
          </div>
        </div>
        <!-- /.container-fluid-->
        <!-- /.content-wrapper-->';

    echo '<footer class="sticky-footer">
      <div class="container">
        <div class="text-center">';

    echo "<small>&copy; <a href='http://about.me/marc.augier'>Marc Augier</a> 2015-2018
       | <a href='https://github.com/marcyves/zogg'>ZOGG on GitHub</a></small>";

    echo '</div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="themes/startbootstrap/vendor/jquery/jquery.min.js"></script>
    <script src="themes/startbootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="themes/startbootstrap/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="themes/startbootstrap/js/sb-admin.min.js"></script>
  </div>
</body>

</html>';
}

function sign_in(){
  return '<div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form class="form-signin" method="post">
          <div class="form-group">
            <label for="inputName">User Name</label>
            <input class="form-control" id="inputName" name="username" placeholder="Enter User Name">
          </div>
          <div class="form-group">
            <label for="inputPassword">Password</label>
            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password">
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox">Remember Password</label>
            </div>
          </div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        </form>
      </div>
    </div>';
}

?>
