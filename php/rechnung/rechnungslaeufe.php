<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
<div class="row mt-3">
	<div id="show1" class="col-sm-12">
	</div>
</div>