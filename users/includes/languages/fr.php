<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

/*
%m1% - Marqueurs dynamiques qui sont remplacés lors de l'exécution par l'indice pertinent.
*/

$lang = array();

//Account
$lang = array_merge($lang,array(
	"ACCOUNT_SPECIFY_USERNAME" 		=> "Merci d'entrer votre nom d'utilisateur",
	"ACCOUNT_SPECIFY_PASSWORD" 		=> "Merci d'entrer votre mot de passe",
	"ACCOUNT_SPECIFY_EMAIL"			=> "Merci d'entrer votre adresse email",
	"ACCOUNT_INVALID_EMAIL"			=> "Adresse email invalide",
	"ACCOUNT_USER_OR_EMAIL_INVALID"		=> "Nom d'utilisateur ou email invalide",
	"ACCOUNT_USER_OR_PASS_INVALID"		=> "Nom d'utilisateur ou mot de passe invalide",
	"ACCOUNT_ALREADY_ACTIVE"		=> "Votre compte est déjà activé",
	"ACCOUNT_INACTIVE"			=> "Votre compte est inactif. Vérifiez vos emails et dossiers spams pour les instructions d'activation de votre compte",
	"ACCOUNT_USER_CHAR_LIMIT"		=> "Votre nom d'utilisateur doit comprendre entre %m1% et %m2% caractères",
	"ACCOUNT_DISPLAY_CHAR_LIMIT"		=> "Votre nom d'usage doit comprendre entre %m1% et %m2% caractères",
	"ACCOUNT_PASS_CHAR_LIMIT"		=> "Votre mot de passe doit comprendre entre %m1% et %m2% caractères",
	"ACCOUNT_TITLE_CHAR_LIMIT"		=> "Les titres doivent comprendre entre %m1% et %m2% caractères",
	"ACCOUNT_PASS_MISMATCH"			=> "Votre mot de passe et sa confirmation doivent correspondrent",
	"ACCOUNT_DISPLAY_INVALID_CHARACTERS"	=> "Le nom d'usage ne peut être composé que de caractères alpha-numériques",
	"ACCOUNT_USERNAME_IN_USE"		=> "Le nom d'utilisateur %m1% est déjà utilisé",
	"ACCOUNT_DISPLAYNAME_IN_USE"		=> "Le nom d'usage %m1% est déjà utilisé",
	"ACCOUNT_EMAIL_IN_USE"			=> "L'email %m1% est déjà utilisé",
	"ACCOUNT_LINK_ALREADY_SENT"		=> "Un email d'activation a déjà été envoyé à cette adresse email il y a %m1% heure(s)",
	"ACCOUNT_NEW_ACTIVATION_SENT"		=> "Nous vous avons envoyé un nouveau lien d'activation par email, merci de vérifier vos emails",
	"ACCOUNT_SPECIFY_NEW_PASSWORD"		=> "Merci d'entrer votre nouveau mot de passe",	
	"ACCOUNT_SPECIFY_CONFIRM_PASSWORD"	=> "Merci de confirmer votre nouveau mot de passe",
	"ACCOUNT_NEW_PASSWORD_LENGTH"		=> "Le nouveau mot de passe doit comprendre entre %m1% et %m2% caractères",	
	"ACCOUNT_PASSWORD_INVALID"		=> "Le mot de passe actuel ne correspond pas avec celui que nous avons enregistré",	
	"ACCOUNT_DETAILS_UPDATED"		=> "Détails du compte mis à jour",
	"ACCOUNT_ACTIVATION_MESSAGE"		=> "Vous aurez besoin d'activer votre compte avant de pouvoir vous connecter. Suivez le lien ci-dessous pour activer votre compte. \n\n
	%m1%activate-account.php?token=%m2%",
	"ACCOUNT_ACTIVATION_COMPLETE"		=> "Vous avez activé votre compte avec succès. Vous pouvez maintenant vous connecter <a href=\"login.php\">En cliquant ici</a>.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE1"	=> "Vous vous êtes enregistré avec succès. Vous pouvez maintenant vous connecter <a href=\"login.php\">En cliquant ici</a>.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"	=> "Vous vous êtes enregistré avec succès. Vous allez bientôt recevoir un email d'activation. 
	Vous devez activer votre compte avez de pouvoir vous connecter.",
	"ACCOUNT_PASSWORD_NOTHING_TO_UPDATE"	=> "Vous ne pouvez pas mettre à jour avec le même mot de passe",
	"ACCOUNT_PASSWORD_UPDATED"		=> "Mot de passe du compte mis à jour",
	"ACCOUNT_EMAIL_UPDATED"			=> "Email du compte mis à jour",
	"ACCOUNT_TOKEN_NOT_FOUND"		=> "Ce jeton n'existe pas / Compte déjà activé",
	"ACCOUNT_USER_INVALID_CHARACTERS"	=> "Le nom d'utilisateur ne peut contenir que des caractères alpha-numériques",
	"ACCOUNT_DELETIONS_SUCCESSFUL"		=> "Vous avez effacé avec succès %m1% utilisateurs",
	"ACCOUNT_MANUALLY_ACTIVATED"		=> "Le compte de %m1% a été activé manuellement",
	"ACCOUNT_DISPLAYNAME_UPDATED"		=> "Nom d'usage changé pour %m1%",
	"ACCOUNT_TITLE_UPDATED"			=> "Le titre de %m1% à été changé pour %m2%",
	"ACCOUNT_PERMISSION_ADDED"		=> "Accès ajouté aux permissions de niveau %m1%",
	"ACCOUNT_PERMISSION_REMOVED"		=> "Accès supprimé aux permissions de niveau %m1%",
	"ACCOUNT_INVALID_USERNAME"		=> "Nom d'utilisateur non valide",
	"ACCOUNT_NEW_USER_WELCOME"		=> "Bienvenue à SKEM Bank",
	));

//Configuration
$lang = array_merge($lang,array(
	"CONFIG_NAME_CHAR_LIMIT"		=> "Le nom du site doit comprendre entre %m1% et %m2% caractères",
	"CONFIG_URL_CHAR_LIMIT"			=> "Le nom du site doit comprendre entre %m1% et %m2% caractères",
	"CONFIG_EMAIL_CHAR_LIMIT"		=> "Le nom du site doit comprendre entre %m1% et %m2% caractères",
	"CONFIG_ACTIVATION_TRUE_FALSE"		=> "L'email d'activation doit être soit `vrai` soit `faux`",
	"CONFIG_ACTIVATION_RESEND_RANGE"	=> "Le seuil d'activation doit être compris entre %m1% et %m2% heures",
	"CONFIG_LANGUAGE_CHAR_LIMIT"		=> "Le nom du chemin du fichier language doit être compris entre %m1% et %m2% caractères",
	"CONFIG_LANGUAGE_INVALID"		=> "Il n'y a aucun fichier de language pour la clé `%m1%`",
	"CONFIG_TEMPLATE_CHAR_LIMIT"		=> "Le nom du chemin du fichier de template doit être compris entre %m1% et %m2% caractères",
	"CONFIG_TEMPLATE_INVALID"		=> "Il n'y a aucun fichier de template pour la clé `%m1%`",
	"CONFIG_EMAIL_INVALID"			=> "L'email que vous avez entré n'est pas valide",
	"CONFIG_INVALID_URL_END"		=> "Merci d'inclure le / de fin dans l'URL de votre site",
	"CONFIG_UPDATE_SUCCESSFUL"		=> "La configuration de votre site à été mise à jour. Vous devez charger une nouvelle page pour que tous les paramètres prennent effet",
	));

//Forgot Password
$lang = array_merge($lang,array(
	"FORGOTPASS_INVALID_TOKEN"		=> "Votre jeton d'activation n'est pas valide",
	"FORGOTPASS_NEW_PASS_EMAIL"		=> "Nous vous avons envoyé un nouveau mot de passe par email",
	"FORGOTPASS_REQUEST_CANNED"		=> "Requête de nouveau mot de passe annulée",
	"FORGOTPASS_REQUEST_EXISTS"		=> "Une demande de nouveau mot de passe est déjà en attente pour ce compte",
	"FORGOTPASS_REQUEST_SUCCESS"		=> "Nous vous avons envoyé par email les instructions pour récupérer l'accès à votre compte",
	));

//Mail
$lang = array_merge($lang,array(
	"MAIL_ERROR"				=> "Une erreur fatale est survenue lors l'envoi de l'email, contactez l'administrateur de votre serveur",
	"MAIL_TEMPLATE_BUILD_ERROR"		=> "Une erreur est survenue lors de la construction du template d'email",
	"MAIL_TEMPLATE_DIRECTORY_ERROR"		=> "Impossible d'ouvrir le dossier mail-template. Essayez peut-être d'indiquer comme nom de répertoire mail %m1%",
	"MAIL_TEMPLATE_FILE_EMPTY"		=> "Fichier de template vide... rien à envoyer",
	));

//Miscellaneous
$lang = array_merge($lang,array(
	"CAPTCHA_FAIL"				=> "Question de sécurité echouée",
	"CONFIRM"				=> "Confirmer",
	"DENY"					=> "Refuser",
	"SUCCESS"				=> "Succès",
	"ERROR"					=> "Erreur",
	"NOTHING_TO_UPDATE"			=> "Rien a mettre à jour",
	"SQL_ERROR"				=> "Erreur SQL fatale",
	"FEATURE_DISABLED"			=> "Cette option est actuellement désactivée",
	"PAGE_PRIVATE_TOGGLED"			=> "Cette page est maintenant %m1%",
	"PAGE_ACCESS_REMOVED"			=> "Accès à la page retiré pour le(s) niveau(x) de permission %m1%",
	"PAGE_ACCESS_ADDED"			=> "Accès à la page ajouté pour le(s) niveau(x) de permission %m1%",
	));

//Permissions
$lang = array_merge($lang,array(
	"PERMISSION_CHAR_LIMIT"			=> "Les noms de permission doivent comprendre entre %m1% et %m2% caractères",
	"PERMISSION_NAME_IN_USE"		=> "Nom de permission %m1% est déjà utilisé",
	"PERMISSION_DELETIONS_SUCCESSFUL"	=> "Niveau(x) de permission  %m1% supprimé(s) avec succès",
	"PERMISSION_CREATION_SUCCESSFUL"	=> "Niveau(x) de permission  %m1% créé(s) avec succès",
	"PERMISSION_NAME_UPDATE"		=> "Nom de niveau de permission modifié en `%m1%`",
	"PERMISSION_REMOVE_PAGES"		=> "Accès supprimé avec succès à %m1% page(s)",
	"PERMISSION_ADD_PAGES"			=> "Accès ajouté avec succès à %m1% page(s)",
	"PERMISSION_REMOVE_USERS"		=> "%m1% utilisateur(s) supprimé(s) avec succès",
	"PERMISSION_ADD_USERS"			=> "%m1% utilisateur(s) ajouté(s) avec succès",
	"CANNOT_DELETE_NEWUSERS"		=> "Vous ne pouvez pas supprimer le groupe par défaut 'new user'",
	"CANNOT_DELETE_ADMIN"			=> "Vous ne pouvez pas supprimer le groupe par défaut 'admin'",
	));
?>