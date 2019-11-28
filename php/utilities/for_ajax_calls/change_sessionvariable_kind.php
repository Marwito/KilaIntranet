<?php
if(isset($_POST['kind'])) {
    require_once('../../login/session.php');
    require_once('../../utilities/constants.php');
    $session = Session::getInstance();
    if($session->checkSessionVariables('username', 'usergroup', 'kind')){
        require_once('../../benutzerverwaltung/benutzer/benutzer_class.php');
        $benutzer = new Benutzer();
        if($benutzer->isEltern($session->usergroup) || $benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup)) {
            $_SESSION['kind'] = $_POST['kind'];
        }
    }
}
?>