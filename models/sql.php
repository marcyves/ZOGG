<?php

/*
=================================================================

     Copyright (c) 2013 Marc Augier

     This program is free software; you can redistribute it and/or
     modify it under the terms of the GNU General Public License
     as published by the Free Software Foundation; either version 2
     of the License, or (at your option) any later version.

     See the GNU General Public License for more details.

     Contact: m.augier@me.com
  =================================================================
*/

function mxa_query($sql){

    global $errorMessage;

    $result = mysql_query($sql);

    $errorMessage .= "SQL : $sql<br/>".mysql_error()."<br>";

    if (mysql_error())
    {
    	echo "ERREUR SQL";
        $errorMessage .= "SQL Error in : $sql<br/>".mysql_error()."<br>";
        return $false;
    } else {
    	echo "RETOUR SQL";
        return $result;
    }
}
?>
