<?php


/*
==============================================================================

	Copyright (c) 2013 Marc Augier

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

/*
The User Authentication provides from:

UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Prevent the user visiting the logged in page if he is not logged in
if(!isUserLoggedIn()) { header("Location: index.php"); die(); }

require_once("inc/functions.php");
require_once("themes/$theme/theme.php");
require_once("inc/classes.php");
require_once("inc/my_functions.php");

/* Debug
echo "<h1>user</h1>";
print_r($loggedInUser);

echo "<h1>POST</h1>";
print_r($_POST);
*/

if(!empty($_POST))
{
	$errors = array();
	$successes = array();
// email and title can be modified at any moment
	$email = $_POST["email"];
	$title = $_POST["title"];

	$errors = array();
	$email = $_POST["email"];

	//Perform some validation

	if($email != $loggedInUser->email)
	{
		if(trim($email) == "")
		{
			$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
		}
		else if(!isValidEmail($email))
		{
			$errors[] = lang("ACCOUNT_INVALID_EMAIL");
		}
		else if(emailExists($email))
		{
			$errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
		}

		//End data validation
		if(count($errors) == 0)
		{
			$loggedInUser->updateEmail($email);
			$successes[] = lang("ACCOUNT_EMAIL_UPDATED");
		}
	}

	if($title != $loggedInUser->title)
	{
		$title = trim($title);
		//Validate title
		if(minMaxRange(1,50,$title))
		{
			$errors[] = lang("ACCOUNT_TITLE_CHAR_LIMIT",array(1,50));
		}
		else {
			if (updateTitle($loggedInUser->user_id, $title)){
				$loggedInUser->title = $title;
				$successes[] = lang("ACCOUNT_TITLE_UPDATED", array ($loggedInUser->displayname, $title));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
	}

// campus can only be set one time
	if($campus = $_POST["campus"])
	{
		if($campus != $loggedInUser->campus)
		{
			$loggedInUser->updateCampus($campus);
			$successes[] = lang("ACCOUNT_CAMPUS_UPDATED", array ($loggedInUser->displayname, getCampusById($campus)));
		}
	}

// team can only be set one time
	if($team = $_POST["team"])
	{
		if($team != $loggedInUser->team)
		{
			$loggedInUser->updateTeam($team);
			$successes[] = lang("ACCOUNT_TEAM_UPDATED", array ($loggedInUser->displayname, getTeamById($team)));
		}
	}

// group can only be set one time
	if($group = $_POST["group"])
	{
		if($team != $loggedInUser->group)
		{
			$loggedInUser->updateGroup($group);
			$successes[] = lang("ACCOUNT_GROUP_UPDATED", array ($loggedInUser->displayname, $group));
		}
	}

// role can only be set one time
	if($role = $_POST["role"])
	{
		if($role != $loggedInUser->role)
		{
			// Make sure user has already a team
			if ($loggedInUser->team > 0)
			{
				// Check role is available in this team
				$result = mysqli_query($mysqli, "SELECT id FROM ".$db_table_prefix."users WHERE roleId = '$role' AND teamId = '".$loggedInUser->team."'");
				//	    printf("Erreur : %s\n", mysqli_error($mysqli));
				$row_cnt = mysqli_num_rows($result);
				mysqli_free_result($result);
				if ($row_cnt>0)
				{
					$errors[] = lang("ACCOUNT_ROLE_UNAVAILABLE");
				} else
				{
					$loggedInUser->updateRole($role);
					$successes[] = lang("ACCOUNT_ROLE_UPDATED", array ($loggedInUser->displayname, getRoleById($role)));

					if ($role == 6)  // Banker
					{
						//We now have to initialise bank account with starting value.
						initUserBankAccount($loggedInUser->user_id);
						$successes[] = lang("ACCOUNT_BANK_INIT", array ($loggedInUser->displayname));
					}
				}
			}
			else
			{
				// Team not set yet, ask user for team before/while selecting role
				$errors[] = lang("ACCOUNT_SPECIFY_TEAM");
			}
		}
	}

	if(count($errors) == 0 AND count($successes) == 0){
		$errors[] = lang("NOTHING_TO_UPDATE");
	}
}

openPage("User Settings");

echo resultBlock($errors,$successes);

echo "
<form name='updateAccount' action='".$_SERVER['PHP_SELF']."' method='post'>
<div class='form_settings'>
<span>Email:</span>
<input type='text' name='email' value='".$loggedInUser->email."' />
<span>Nickname:</span>
<input type='text' name='title' value='".$loggedInUser->title."' />
<span>Role:</span>";


/*
    The Role box
 */
$roleId = $loggedInUser->role;

if ($roleId == NULL)
{
	$roleId0 = 0;
	$role0 = "--> Please Select";

	echo "
	<select name='role' />
	<option style='color:purple;' value='$roleId0' selected>$role0</option>";

	$result = mysqli_query($mysqli,"SELECT id, role FROM role ORDER BY role");

	while (list($roleId, $role) = mysqli_fetch_row($result))
	{
		if ($roleId0 != $roleId )
			echo "<option style='color:grey;' value='$roleId'>$role</option>";
	}
	mysqli_free_result($result);

	echo "
	</select>";
}
else
{
	echo "<input type='text' name='roleOk' value='".getRoleById($roleId)."' readonly>";
}

/*
   The campus Box
*/
$campusId = $loggedInUser->campus;

echo "<span>Campus:</span>";

if ($campusId == NULL)
{
	$campusId0 = 0;
	$campusName0 = "--> Please Select";

	echo "<select name='campus' />
	<option style='color:purple;' value='$campusId0' selected>$campusName0</option>";

	$result = mysqli_query($mysqli,"SELECT id, campusName FROM campus ORDER BY campusName");

	while (list($campusId, $campusName) = mysqli_fetch_row($result))
	{
		if ($campusId0 != $campusId )
			echo "<option style='color:grey;' value='$campusId'>$campusName</option>";
	}
	mysqli_free_result($result);

	echo "
	</select>";
}
else
{
	echo "<input type='text' name='campusOk' value='".getCampusById($campusId)."' readonly>";
}

/*
    The Group Box
*/
echo "<span>Group :</span>";
$groupId = $loggedInUser->group;

if ($groupId == NULL)
{
	echo "<select name='group' />
	<option style='color:red;' value='0' selected>--> Please Select</option>";

        //Get max group from campus
        //need some AJAX here
	for($groupId=1; $groupId < 10;$groupId++)
	{
            echo "<option style='color:grey;' value='$groupId'>$groupId</option>";
	}
	echo "
	</select>";
}
else
{
	echo "<input type='text' name='groupOk' value='$groupId' readonly>";
}

/*
    The Team Box
*/
echo "<span>Team :</span>";
$teamId = $loggedInUser->team;

if ($teamId == NULL)
{
	$teamId0 = 0;
	$teamName0 = "--> Please Select";
	echo "<select name='team' />
	<option style='color:red;' value='$teamId0' selected>$teamName0</option>";

	for($teamId=1; $teamId < 251;$teamId++)
	{
		if ($teamId0 != $teamId )
			echo "<option style='color:grey;' value='$teamId'>".getTeamById($teamId)."</option>";
	}
	echo "
	</select>";
}
else
{
	echo "<input type='text' name='teamOk' value='".getTeamById($teamId)."' readonly>";
}

echo "<p style='padding-top: 15px'>
<input type='submit' value='Update' class='submit' />
</div>
</form>";


closePage();

?>
