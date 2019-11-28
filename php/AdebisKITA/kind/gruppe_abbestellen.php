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
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
		<form name="formAbbestellung" id="formAbbestellung" method="post" action="" class="needs-validation" novalidate>
			<label>Zeitraum</label>
			<div class="row">
				<div class="col-2 form-group custom-font">
					<label for="input_text1">von</label>
					<input style="font-family:verdana;font-size:16px" type="text" class="form-control" id="datetimepicker2" name="input_text1" data-toggle="datetimepicker" data-target="#datetimepicker2" required>
					<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
				</div>
				<div class="col-2 form-group custom-font">
					<label for="input_text2">bis</label><br>
					<input style="font-family:verdana;font-size:16px" type="text" class="form-control"  id="datetimepicker3" data-toggle="datetimepicker" data-target="#datetimepicker3" name="input_text2" required>
					<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
				</div>
				<?php 
				if($benutzer->isAdmin($session->usergroup)) {
				    echo '<div class="col form-group" id="einrichtung">
				            <label for="input_einrichtung">Einrichtung</label>
				            <select class="form-control" name="input_einrichtung" id="input_einrichtung" required>
				            <option value="" selected>Wählen...</option>
				            </select>
				        <div class="invalid-feedback"> Bitte wählen Sie eine Einrichtung aus! </div>
				        </div>';
				} else {
				    echo '<input type="hidden" id="input_einrichtung" name="input_einrichtung" value="'.$session->einrichtung_kueche.'">';
				}
				?>
				<div class="col form-group" id="gruppe">
    				    <label for="input_einrichtung">Gruppe</label>
    				    <select class="form-control" name="input_gruppe" id="input_gruppe">
		    				<option value="-1" selected>Wählen...</option>
		    			</select>
				</div>
				<div class="col form-group" id="aktionsgruppe">
    				    <label for="input_aktionsgruppe">Aktionsgruppe</label>
    				    <select class="form-control" name="input_aktionsgruppe" id="input_aktionsgruppe">
		    				<option value="-1" selected>Wählen...</option>
		    			</select>
				</div>
				<div class="col-12 form-group">
    				    <label for="input_grund">Grund</label>
    				    <input type="text" class="form-control" name="input_grund" id="input_grund" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			</div>
		   </div>
		</form>
		<button id="submitAbbestellen" name="submitAbbestellen" class="btn btn-primary btn-custom">Abbestellen</button>