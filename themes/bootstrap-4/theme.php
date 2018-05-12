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
    $tmp .= "<input type='submit' value='Change'>";
  }
  $tmp .= "</form>";

  mysqli_free_result($rc);
  return $tmp;
}

function toolBarActiveLink($url){
  echo '<li class="nav-item active">
      <a class="nav-link" href="'.$url.'">Home <span class="sr-only">(current)</span></a>
    </li>';
}

function toolBarLink($url, $label){
  echo '<li class="nav-item">
      <a class="nav-link" href="'.$url.'">'.$label.'</a>
    </li>';
}

function openPage($title)
{
    global $websiteName, $websiteDescription, $template, $mysqli, $emailActivation, $loggedInUser, $INX_SIDE1, $INX_LINKS;
    global $errors;
    global $successes;

    echo '<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->'.
    "<link rel='stylesheet' href='themes/bootstrap-4/css/bootstrap.min.css'>".
    "<!-- Custom styles for this template -->".
    "<link rel='stylesheet' href='themes/bootstrap-4/style.css'>".
    "<title>".$websiteName."</title>".
    "</head>".
    "<body>";

    if(isUserLoggedIn())
    {
      //Links for logged in user

      echo '<header>
      <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">'.
      '<ul class="navbar-nav mr-auto">';
      toolBarLink('index.php', 'Home');
      if (isUserReady($loggedInUser->user_id))
	     {
        	if ($loggedInUser->checkPermission(array(1)))
          { //Links for permission level 1 (student)
            toolBarLink('dropbox.php', 'DropBox');
   		    }
  		    if ($loggedInUser->checkPermission(array(2)))
          { //Links for permission level 2 (professor)
            toolBarLink('grading.php', 'Team Grading');
            toolBarLink('building.php', 'Team Building');
            toolBarLink('student.php', 'Student Management');
            toolBarLink('admin_assignment.php', 'Assignment Management');
  		    }
          if ($loggedInUser->checkPermission(array(3)))
          { //Links for permission level 3 (admin)
            toolBarLink('admin.php', 'Campus and Courses Management');
            toolBarLink('admin_assignment.php', 'Assignment Management');
  		    }
	     }

       toolBarLink('user_settings.php', 'Profile');
       toolBarLink('logout.php', 'Logout');

       echo "</nav>
       </header>";

       echo '<!-- Begin page content -->
    <main role="main" class="container">
    <h2 class="mt-5">'.$websiteDescription.'</h2>
    <div class="container-fluid">';

       if ($loggedInUser->checkPermission(array(1)))
       {
         $text_group = "";
         $userLevel = "Student";
       }
       if ($loggedInUser->checkPermission(array(2)))
       {
         echo displayCurrentGroup();
         $userLevel = "Professor";
       }
       if ($loggedInUser->checkPermission(array(3)))
       {
         $text_group = "";
         $userLevel = "Admin";
       }

       echo '<div class="row">
    <div class="col-md-2">';

       $text = "<h3>".$loggedInUser->displayname."</h3><p><i>$userLevel</i></p>".
       "$INX_LINKS";

       echo displaySideMenu($text);

	     //Links for permission level 3 (default admin)
	     if ($loggedInUser->checkPermission(array(3)))
       {
	        $text = '<h3>Admin Menu</h3>'.
          "<ul>
        	<li><a href='admin_configuration.php'>Admin Configuration</a></li>
        	<li><a href='admin_users.php'>Admin Users</a></li>
        	<li><a href='admin_permissions.php'>Admin Permissions</a></li>
        	<li><a href='admin_pages.php'>Admin Pages</a></li>
        	<li><a href='admin_init.php'>Initialisation des comptes users</a></li>";

           echo displaySideMenu($text);
       }
       echo '</div>
       <div class="col-md-10">';
} else {
//Links for users not logged in
  echo '<header><nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">'.
  '<ul class="navbar-nav mr-auto">';
  toolBarLink('index.php', 'Home');
  toolBarLink('forgot-password.php', 'Forgot Password');
  echo "</ul>".
  "</nav></header>".
  '<div class="row">
<div class="col-md-2">'.
         "$INX_LINKS".
         "$INX_SIDE1".
         "</div>".
         '<div class="col-md-10">';
}

echo '<h1><img src="themes/default/images/examples.png" alt="examples" />'.
      $title.'</h1>';

// Display error or success messages
if (!empty($successes)) {
        echo "<h4 style='color: blue;'>" . $successes[0] . "</h4>";
    }
    if (!empty($errors)) {
        echo "<h4 style='color: red;'>" . $errors[0] . "</h4>";
    }
}

function closePage()
{
    echo "</div>
        </div></main>";
    echo '<footer class="footer">
      <div class="container">'.
      "&copy; <a href='http://about.me/marc.augier'>Marc Augier</a> 2015-2018
     | <a href='https://github.com/marcyves/zogg'>ZOGG on GitHub</a>
    </div>
    </footer>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script>
<script src='themes/bootstrap-4/js/bootstrap.min.js'></script>
</body>
</html>";

}

function displaySideMenu($t)
{
    return '<img class="paperclip" src="themes/default/images/paperclip.png" alt="paperclip" />
        <div class="sidebar">'.$t."</div>";
}

function sign_in(){
  return '<form class="form-signin" method="post">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputName" class="sr-only">User Name</label>
  			<input type="text"  id="inputName" class="form-control" name="username" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>';

}
?>
