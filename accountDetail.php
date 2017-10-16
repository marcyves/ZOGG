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

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("inc/functions.php");

/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  

Page for permission level 1 (user)

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  = */if ($loggedInUser->checkPermission(array(1))) {
    openPage("Account Details");

	if (isUserReady($loggedInUser->user_id))
	{
	    echo "<p></p>";
    	accountBrowse($loggedInUser->user_id);
    
	    browseMyTeam($loggedInUser->team);
	} else 	{
		echo "<h4>Vous devez d'abord remplir votre profil dans <a href='user_settings.php'>User Settings</a></h4>";
	}
}
    
/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  

         Page for permission level 2 (professor)

 = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */if ($loggedInUser->checkPermission(array(2)))
    {
        $userId = $_GET['id'];
        
        $sql = "SELECT display_name, teamId FROM sk_users WHERE id='$userId'";
        $result = mysqli_query($mysqli, $sql);
        list($userName, $teamId)  = mysqli_fetch_row($result);
        mysqli_free_result($result);        


        openPage("Détails des transactions");
        
        echo "<h2>$userName (Equipe $teamId)</h2>";
        
        $sql = "SELECT DISTINCT account2, U.display_name, debit, credit, description, timestamp FROM account A, sk_users U WHERE A.errorFlag <> '1' AND A.account1 = '$userId' AND U.id = A.account2 ORDER BY account2, timestamp";
//        echo $sql;
        if ($result = mysqli_query($mysqli, $sql))
            {
    		echo "<table style='width:100%; border-spacing:0;'>".
				  "<tr>
				  	<th>vers/depuis Compte</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                    <th>Timestamp</th>";
                while (list($account2, $user_name,  $debit, $credit,$description, $timestamp)  = mysqli_fetch_row($result))
                {
        	    	echo "<tr><td><b>$user_name</b></td><td>$debit</td><td>$credit</td><td>$timestamp</td></tr>";
          	    } 
                echo "</table>
		<br/>
* * * * End Of Report * * * *";
            /* Libération du jeu de résultats */
                mysqli_free_result($result);        
            }
    }
/* = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  

Page for permission level 3 (administrator)

 = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */    
if ($loggedInUser->checkPermission(array(3)))
    {
        openPage("Les scores globaux des équipes");
    }

closePage();

?>
