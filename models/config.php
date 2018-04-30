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
      	$settings[$name] = array('id' => $id, 'name' => $name, 'value' => $value);
      }
      $stmt->close();

      //Set Settings
      $emailActivation = $settings['activation']['value'];
      $mail_templates_dir = "models/mail-templates/";
      $websiteName = $settings['website_name']['value'];
      $websiteDescription = $settings['website_description']['value'];
      $websiteUrl = $settings['website_url']['value'];
      $emailAddress = $settings['email']['value'];
      $resend_activation_threshold = $settings['resend_activation_threshold']['value'];
      $emailDate = date('dmy');
      $language = $settings['language']['value'];
      $template = $settings['template']['value'];
      $cool_lang = $settings['lang']['value'];
      $init_bank = $settings['init_bank']['value'];

      $platform = $settings['platform']['value']; // pool or cool
      $theme = $settings['theme']['value'];

      $master_account = -1;

      $default_hooks = array("#WEBSITENAME#","#WEBSITEURL#","#DATE#");
      $default_replace = array($websiteName,$websiteUrl,$emailDate);

      if (!file_exists($language)) {
      	$language = "models/languages/fr.php";
      }

      if(!isset($language)) $language = "models/languages/fr.php";
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
      require_once("class.mail.php");
      require_once("class.user.php");
      require_once("class.newuser.php");
      require_once("funcs.php");

      session_start();

      //Global User Object Var
      //loggedInUser can be used globally if constructed
      if(isset($_SESSION["userCakeUser"]) && is_object($_SESSION["userCakeUser"]))
      {
        $loggedInUser = $_SESSION["userCakeUser"];
      }
    }
} else {
    echo "<h1>Installation required</h1>";
    die();
}; //Require DB connection




?>
