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
        <div class="row mt-2">
        	<div class="col-sm-12">
        		<form name="form" class="needs-validation" novalidate>
        			<div class="form-row">
            			<div class="col-md-3 mb-3">
            				<label class="sr-only" for="datetimepicker1">Von :</label>
        					<div class="form-group custom-font">
        					    <input style="font-family:verdana;font-size:16px" type="text" placeholder="Zeitraum_von" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker1" id="datetimepicker1" name="datetimepicker1" required>
        				  		<div class="invalid-feedback" style="font-family:verdana"> Bitte füllen Sie dieses Feld aus! </div>
        				  	</div>
        			    </div>
        			    <div class="col-md-3 mb-3">
        				    <label class="sr-only" for="datetimepicker2">Bis :</label>
        					<div class="form-group custom-font">
        					    <input style="font-family:verdana;font-size:16px" type="text" placeholder="Zeitraum_bis" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker2" id="datetimepicker2" name="datetimepicker2" required>
        				  		<div class="invalid-feedback" style="font-family:verdana"> Bitte füllen Sie dieses Feld aus! </div>
        				  	</div>
    				    </div>
    				    <div class="col-md-3 mb-3">
    				    	<button id="submitRechnungen" name="submitRechnungen" class="btn btn-primary btn-custom">zurücksetzen</button>
    				    	<div class="invalid-feedback">Bitte füllen Sie diese Felder aus !</div>
    				    </div>
				    </div> 	
        		</form>
			</div>
		</div>
		<div class="row mt-3">
    		<div id="show" class="col-sm-12">
    		</div>
    	</div>