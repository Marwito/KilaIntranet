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

echo "<div id='mySidenav' class='sidenav'>";
echo "<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>";

if ($benutzer->isCatererKueche($session->usergroup)) {
    echo "<a href='".Constants::getKuechenBereichURL()."'><img src='../../img/KiLa_Icons_20180719_Einstellungen.png' title='Küchenbereich'/></a><br>";
} else if($benutzer->isAdmin($session->usergroup) || $benutzer->isMitarbeiter($session->usergroup) || $benutzer->isLeiter($session->usergroup)) {
    if(file_exists("../../img/KiLa_Icons_20180719_QMDokumente.png")) {
        echo "<a href='".Constants::getBenutzerverwaltungURL()."'><img src='../../img/KiLa_Icons_20180719_AllgMitarbeiterverwaltung.png' title='Benutzerverwaltung'/></a><br>";
        echo "<a href='".Constants::getAdminBereichURL()."'><img src='../../img/KiLa_Icons_20180719_Einstellungen.png' title='Einstellungen'/></a><br>";
        echo "<a href='".Constants::getButURL()."'><img src='../../img/KiLa_Icons_20180719_Dienstplan.png' title='BuT'/></a><br>";
        echo "<a href='".Constants::getKinderUebersichtURL()."'><img src='../../img/KiLa_Icons_20180719_AllgMitarbeiterverwaltung.png' title='Kinderübersicht'/></a><br>";
        echo "<a href='".Constants::getDatevExportURL()."'><img src='../../img/KiLa_Icons_20180719_AllgMitarbeiterverwaltung.png' title='DATEV Export'/></a><br>";
    } else {
        echo "<a href='".Constants::getBenutzerverwaltungURL()."'><img src='../../../img/KiLa_Icons_20180719_AllgMitarbeiterverwaltung.png' title='Benutzerverwaltung'/></a><br>";
        echo "<a href='".Constants::getAdminBereichURL()."'><img src='../../../img/KiLa_Icons_20180719_Einstellungen.png' title='Einstellungen'/></a><br>";
        echo "<a href='".Constants::getButURL()."'><img src='../../../img/KiLa_Icons_20180719_Dienstplan.png' title='BuT'/></a><br>";
        echo "<a href='".Constants::getKinderUebersichtURL()."'><img src='../../../img/KiLa_Icons_20180719_AllgMitarbeiterverwaltung.png' title='Kinderübersicht'/></a><br>";
        echo "<a href='".Constants::getDatevExportURL()."'><img src='../../../img/KiLa_Icons_20180719_AllgMitarbeiterverwaltung.png' title='DATEV Export'/></a><br>";
    }
} else if($benutzer->isEltern($session->usergroup)) {
    echo "<a href='".Constants::getElternBereichURL()."'><img src='../../img/KiLa_Icons_20180719_Einstellungen.png' title='Elternbereich'/></a><br>";
}

echo "</div>";

?>