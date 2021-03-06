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

function openPage($title)
{
    global $websiteName, $websiteDescription, $template, $mysqli, $emailActivation, $loggedInUser, $INX_SIDE1, $INX_LINKS;
    global $errors;
    global $successes;

    echo "<!DOCTYPE html>".
    "<head>".
    "<meta charset=utf-8' />".
    "<title>".$websiteName."</title>".
    "<link rel='stylesheet' href='themes/default/style.css' />".
    "</head>".
    "<body>".
    "<header>".
    "<h1><a href='index.php'>$websiteName</a></h1>".
    "<h2>$websiteDescription</h2>".
    "</header>";

    if(isUserLoggedIn())
    {
      //Links for logged in user

      echo "<nav>".
      "<div id='menu_container'>".
      "<ul id='nav'>".
      "<li><a href='home.php'>Home</a></li>";
      if (isUserReady($loggedInUser->user_id))
	     {
        	if ($loggedInUser->checkPermission(array(1)))
          { //Links for permission level 1 (student)
            echo "          <li><a href='dropbox.php'>Dropbox</a></li>";
   		    }
  		    if ($loggedInUser->checkPermission(array(2)))
          { //Links for permission level 2 (professor)
  	   		    echo '<li><a href="grading.php">Team Grading</a></li>'.
              '<li><a href="building.php">Team Building</a></li>'.
              '<li><a href="student.php">Student Management</a></li>'.
              '<li><a href="admin_assignment.php">Assignment Management</a></li>';
  		    }
          if ($loggedInUser->checkPermission(array(3)))
          { //Links for permission level 3 (admin)
  	   		    echo '<li><a href="admin.php">Campus and Courses</a></li>'.
              '<li><a href="admin_assignment.php">Assignment Management</a></li>';
  		    }
	     }
   	   echo "<li><a href='user_settings.php'>Profile</a></li>".
       "<li><a href='logout.php'>Logout</a></li>".
       "</ul></div>".
       "</nav>".
       "<main>";

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
       echo '<div id="site_content">';

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
        	<li><a href='admin_init.php'>Initialisation des comptes users</a></li>".
          '</div>';

           echo displaySideMenu($text);
       }
} else {
//Links for users not logged in
    echo "<nav>".
    "<ul class='sf-menu' id='nav'>
	<li><a href='index.php'>Home</a></li>
	<li><a href='forgot-password.php'>Forgot Password</a></li>".
  "</ul>".
  "</nav>".
  "<main>".
   "<div id='site_content'>".
      '<div id="sidebar_container">
        <img class="paperclip" src="themes/default/images/paperclip.png" alt="paperclip" />
        <div class="sidebar">'.
         "<h3>Please log in</h3>".
         "$INX_LINKS".
         "$INX_SIDE1".
         "</div>
            ";
}

echo "</div>
<div class='content'>".
        '<h1><img src="themes/default/images/examples.png" alt="examples" />'.
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
        </div>
        </main>
<footer><small>
<p>(c) <a href='http://about.me/marc.augier'>Marc Augier</a> 2015
     | <a href='https://github.com/marcyves/zogg'>ZOGG on GitHub</a>
     | <a href='http://www.css3templates.co.uk'>design from css3templates.co.uk</a></p>
</small>
</footer>

</body>
</html>";

}

function displaySideMenu($t)
{
    return '<div id="sidebar_container">
        <img class="paperclip" src="themes/default/images/paperclip.png" alt="paperclip" />
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
