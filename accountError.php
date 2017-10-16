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

= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =  = */
if ($loggedInUser->checkPermission(array(1))) {
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

 = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = */
if ($loggedInUser->checkPermission(array(2)))
    {
        openPage("Les transactions en erreur");

        $sql = "SELECT DISTINCT A1.`account1`, A1.`account2`,A1.`debit`, A1.`credit`, A1.`timestamp`, U1.display_name, U2.display_name FROM `account` A1, sk_users U1, sk_users U2 WHERE (A1.credit <= '0' OR A1.debit <= '0' OR A1.credit > '10000' OR A1.debit > '10000') AND U1.id = A1.account1 AND U2.id = A1.account2  ORDER BY A1.account1, A1.account2";
//        echo $sql;
        if ($result = mysqli_query($mysqli, $sql))
            {
            echo "<h2>Les transactions nulles ou négatives</h2>";

			$i = 0;
    		echo "<table style='width:100%; border-spacing:0;'>".
				  "<tr>
				  <th>no</th>
				  	<th>depuis Compte</th>
				  	<th>vers Compte</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                    <th>Timestamp</th>";
                while (list($account1, $account2, $debit, $credit, $timestamp, $user1 , $user2)  = mysqli_fetch_row($result))
                {
                	$i += 1;
        	    	echo "<tr><td>$i</td><td><b>$user1</b></td><td><a href='accountDetail.php?id=$account2'>$user2</a></td><td>$debit</td><td>$credit</td><td>$timestamp</td></tr>";
          	    } 
                echo "</table>
		<br/>
* * * * End Of Report * * * *";


            /* Libération du jeu de résultats */
                mysqli_free_result($result);        
            }
            
        // Flag these transactions as in error to remove them from account summary

        $sql = "UPDATE `account` SET errorFlag = '1' WHERE (credit <= '0' OR debit <= '0')";
//       echo $sql;
        if ($result = mysqli_query($mysqli, $sql))
            {
		        echo "<h2>Les transactions nulles ou négatives sont maintenant inactives</h2>";
			}
/*
                    
        echo "<h2>Les transactions mirroirs</h2>";
        
        $sql = "SELECT DISTINCT A1.`account1`, A1.`account2`,A1.`debit`, A1.`credit`, A1.`timestamp`, U1.display_name, U2.display_name FROM `account` A1,  `account` A2, sk_users U1, sk_users U2 WHERE A1.credit = A2.debit and A1.account1 = A2.account2 AND U1.id = A1.account1 AND U2.id = A1.account2  ORDER BY A1.account1, A1.account2";
//        echo $sql;
        if ($result = mysqli_query($mysqli, $sql))
            {
    		echo "<table style='width:100%; border-spacing:0;'>".
				  "<tr>
				  	<th>depuis Compte</th>
				  	<th>vers Compte</th>
                    <th>Débit</th>
                    <th>Crédit</th>
                    <th>Timestamp</th>";
                while (list($account1, $account2, $debit, $credit, $timestamp, $user1 , $user2) = mysqli_fetch_row($result))
                {

        	    	echo "<tr><td><b>$user1</b></td><td><a href='accountDetail.php?id=$account2'>$user2</a></td><td>$debit</td><td>$credit</td><td>$timestamp</td>";
        	    	// Display the timestamp of mirror transactions
        	    	$sql2 = "SELECT DISTINCT `timestamp` FROM `account` WHERE account1 = '$account2' AND account2 ='$account1' AND debit = '$credit'";
//        echo $sql;
					$result2 = mysqli_query($mysqli, $sql2);
                	while (list($timestamp) = mysqli_fetch_row($result2))
                	{
	        	    	echo "<td>$timestamp</td>";
    	            }
    	            echo "</tr>";					
          	    } 
                echo "</table>
		<br/>
* * * * End Of Report * * * *";
            // Libération du jeu de résultats 
                mysqli_free_result($result);        
            }
            
            */
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
