<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>

<div class="container-fluid">
	<div class="row">
    	<div class="col-sm-12" style="padding-bottom:20px;">
    		<a href="../../../doc/Speiseplan.pdf" class="btn btn-primary btn-custom" target="_blank">Aktuellen Speiseplan anzeigen</a>
    	</div>
	</div>
    <div class="row">
    	<div class="col-sm-12">
    		<form id="formSpeiseplan" name="formSpeiseplan" method="post" action="" enctype="multipart/form-data">
		    	<div class="form-group">
		    		<input type="file" id="inputFile" name="inputFile" accept="application/pdf">
	    		</div>    		
                <div class="text-right my-5">							  	
				  	<button id="submit" name="submit" class="btn btn-primary btn-custom">Speiseplan hochladen</button>
			  	</div>
			</form>
		</div>
	</div>
</div>