<?php

function openPage($title)
{
    global $websiteName, $websiteDescription, $template, $mysqli, $emailActivation, $loggedInUser, $INX_SIDE1, $INX_LINKS;
    global $errors;
    global $successes;

    echo "
<!DOCTYPE html>
<head>
<meta charset='utf-8' />
<title>".$websiteName."</title>
  <meta name='description' content='$websiteDescription' />
  <link rel='stylesheet' href='themes/clouds/style.css' />
  <!-- modernizr enables HTML5 elements and feature detects -->
  <script src='themes/clouds/js/modernizr-1.5.min.js'></script>
</head>

<body>
    <div id='main'>
    <header>
        <div id='logo'>
        <div id='logo_text'>
          <!-- class='logo_colour', allows you to change the colour of the text -->
          <h1><a href='index.php'>$websiteName</a></h1>
          <h2>$websiteDescription</h2>"
    . "</div>"
    . "</div>";

    if(isUserLoggedIn()) {
//Links for logged in user

    echo
	"<nav>
            <div id='menu_container'>
                <ul class='sf-menu' id='nav'>";
	if (isUserReady($loggedInUser->user_id))
	{
        	if ($loggedInUser->checkPermission(array(1))){ //Links for permission level 1 (student)
                	echo "          <li><a href='dropbox.php'>Dropbox</a>";
   		}
/*
		echo "          <li><a href='market.php'>Marketplace</a>";
   		if ($loggedInUser->checkPermission(array(1))){ //Links for permission level 1 (student)
   		echo "           <ul>
                        <li><a href='market.php'>Vos questions (seeker)</a></li>
                        <li><a href='market.php?cmd=bbuy'>Répondre aux questions (solver)</a></li>
                    </ul>";
   		}
   		echo "           </li>
                    <li><a href='wok.php'>WOC</a>";
   		if ($loggedInUser->checkPermission(array(1))){ //Links for permission level 1 (student)
  		echo "
                    <ul>
                        <li><a href='wok.php'>Vos compétences</a></li>
                        <li><a href='wok.php?cmd=bsell'>Acheter une compétence</a></li>
                    </ul>";
   		}
*/
   		echo "           </li>";
  		if ($loggedInUser->checkPermission(array(2))){ //Links for permission level 2 (professor)
  	   		echo '	<li><a href="grading.php">Team Grading</a></li>
				<li><a href="building.php">Team Building</a></li>';
  		}
	}
   	echo "           <li><a href='user_settings.php'>Profile</a></li>
                    <li><a href='logout.php'>Logout</a></li>
                    </ul>
            </div>
        </nav>
        ";


if ($loggedInUser->checkPermission(array(1)))
	$userLevel = "Student";
if ($loggedInUser->checkPermission(array(2)))
	$userLevel = "Professor";
if ($loggedInUser->checkPermission(array(3)))
	$userLevel = "Admin";


        echo '<div id="site_content">';
        $text = "<h3>".$loggedInUser->displayname."</h3><i>$userLevel</i>".
         "$INX_LINKS";

        echo displaySideMenu($text);

	//Links for permission level 3 (default admin)
	if ($loggedInUser->checkPermission(array(3))){
	$text = '</div>
            <h3>Admin Menu</h3>'.
                "<ul>
	<li><a href='admin.php'>Admin</a></li>
	</ul>";
/*
  	<li><a href='admin_configuration.php'>Admin Configuration</a></li>
	<li><a href='admin_users.php'>Admin Users</a></li>
	<li><a href='admin_permissions.php'>Admin Permissions</a></li>
	<li><a href='admin_pages.php'>Admin Pages</a></li>
	<li><a href='admin_init.php'>Initialisation des comptes users</a></li>
 */
        echo displaySideMenu($text);


        }
} else {
//Links for users not logged in
    /*
    echo "
	<nav>
         <div id='menu_container'>
          <ul class='sf-menu' id='nav'>
	<li><a href='index.php'>Home</a></li>
	<li><a href='login.php'>Login</a></li>
	<li><a href='register.php'>Register</a></li>
	<li><a href='forgot-password.php'>Forgot Password</a></li>";
	if ($emailActivation)
	{
            echo "<li><a href='resend-activation.php'>Resend Activation Email</a></li>";
	}
	echo "</ul>
                    </div>
        </nav>
        </header>";

*/
    echo "
	<nav>
         <div id='menu_container'>
          <ul class='sf-menu' id='nav'>
	<li><a href='index.php'>Home</a></li>
	<li><a href='forgot-password.php'>Forgot Password</a></li>";
	echo "</ul>
                    </div>
        </nav>
        </header>";

        echo '<div id="site_content">
      <div id="sidebar_container">
        <img class="paperclip" src="images/paperclip.png" alt="paperclip" />
        <div class="sidebar">'.
         "<h3>".$loggedInUser->displayname."</h3>".
         "<h3>".$loggedInUser->title."</h3>".
         "$INX_LINKS".
         "$INX_SIDE1".
         "</div>
            ";
}

echo "</div>
<div class='content'>".
        '<img style="float: left; vertical-align: middle; margin: 0 10px 0 0;" src="images/examples.png" alt="examples" />
            <h1 style="margin: 15px 0 0 0;">'.$title.'</h1>';

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
<footer><small>
<p>(c) <a href='http://about.me/marc.augier'>Marc Augier</a> 2015
     | <a href='https://github.com/marcyves/cool'>Cool on GitHub</a>
     | <a href='http://www.css3templates.co.uk'>design from css3templates.co.uk</a></p>
     | <a href='http://www.000webhost.com'>Freely hosted by 000webhost.com</a></p>
</small>
</footer>

</body>
</html>";

}

function displaySideMenu($t)
{
    return '<div id="sidebar_container">
        <img class="paperclip" src="images/paperclip.png" alt="paperclip" />
        <div class="sidebar">'.$t."</div>";
}

?>
