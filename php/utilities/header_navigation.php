<?php

require_once('constants.php');

if(!@include_once('../login/session.php')) {
    require_once('../../login/session.php');
}

if(!@include_once('../benutzerverwaltung/benutzer/benutzer_class.php')) {
    require_once('../../benutzerverwaltung/benutzer/benutzer_class.php');
}

$session = Session::getInstance();
$benutzer = new Benutzer();

echo "<div>";
echo "<div class='nav_icons_right'>";
echo "<a class='nav-link' href='".Constants::getLogoutURL()."'><img src='../../../img/KiLa_Icons_20180719_Logout.png' style='width:100%; height:auto;'/></a>";
echo "</div>";

if($benutzer->isEltern($session->usergroup)) {
    echo "<div class='nav_icons_right'>";
    echo "<a class='nav-link' href='".Constants::getContactDetailsURL()."'><img src='../../../img/KiLa_Icons_20180719_Profil.png' style='width:100%; height:auto;'/></a>";
    echo "</div>";
}

echo "</div>";
?>