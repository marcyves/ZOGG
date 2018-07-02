<?php

/*
==============================================================================

	Copyright (c) 2018 Marc Augier

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
UserCake Version: 2.0.2
http://usercake.com
*/

if (is_file(stream_resolve_include_path("db-settings.php"))) {

    require_once("db-settings.php");

    if (!empty($errors))
    {
        echo "<h1>Database Connection Error: ".$errors[0]."</h1>";
        die();
    } else {
      //    echo "pas erreur";
      //Retrieve settings
      $stmt = $mysqli->prepare("SELECT id, name, value FROM ".$db_table_prefix."configuration");
      $stmt->execute();
      $stmt->bind_result($id, $name, $value);

      while ($stmt->fetch()){
        switch($name){
          case "activation":
            $emailActivation = $value;
          break;
          case "email":
            $emailAddress = $value;
          break;
          case "website_name":
            $websiteName = $value;
          break;
          case "website_description":
            $websiteDescription = $value;
          break;
          case "website_url":
            $websiteUrl = $value;
          break;
          case "resend_activation_threshold":
            $resend_activation_threshold = $value;
          break;
          case "language":
            $language = $value;
          break;
          case "template":
            $template = $value;
          break;
          case "lang":
          $cool_lang = $value;
          break;
          case "init_bank":
          $init_bank = $value;
          break;
          case "platform":
          $platform = $value; // pool or cool
          break;
          case "theme":
          $theme = $value;
          break;
        }
      }
      $stmt->close();

      //Set Settings
      $mail_templates_dir = "includes/mail-templates/";
      $emailDate = date('dmy');
      $master_account = -1;

      $default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
      $default_replace = array($websiteName,$websiteUrl,$emailDate);

      if (!file_exists($language)) {
      	$language = "models/languages/fr.php";
      }

      if(!isset($language)) $language = "includes/languages/fr.php";
      if(!isset($theme)) $theme = "default";



      $whoiam = $_SERVER['PHP_SELF'];
      $end = strlen($whoiam)-4;
      $pos = strrpos($whoiam,"/",-4)+1;
      $end = strlen($whoiam)-$pos-4;
      $whoiam = substr($whoiam,$pos,$end);
      //echo "$cool_lang,$pos, $end, $whoiam";
      $mxalanguage = "lang/$cool_lang/$whoiam.php";
      //debug echo "<h1>$language</h1>";
      if (file_exists($mxalanguage)) {
          require_once($mxalanguage);
      }

      //Pages to require
      require_once($language);
    }
} else {
    echo "<h1>Installation required</h1>";
    die();
}; //Require DB connection




?>
