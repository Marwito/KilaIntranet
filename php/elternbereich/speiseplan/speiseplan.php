<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isEltern($session->usergroup) || $benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>

<div class="container-fluid">
	<div class="row">
    	<div class="col-sm-12" style="padding-bottom:20px;">
    		<?php 
    		if (file_exists("../../doc/Speiseplan.pdf")) {
    		    echo "<a href='../../doc/Speiseplan.pdf' class='btn btn-primary btn-custom' target='_blank'>Aktuellen Speiseplan anzeigen</a>";
    		} else {
    		    echo "Es wurde noch kein Speiseplan hinterlegt!";
    		}
    		?>
    	</div>
	</div>
</div>