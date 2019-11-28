<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup', 'einrichtung_kueche')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
setlocale(LC_TIME, 'de');
?>
		<form name="form" id="form" method="post" action="" class="needs-validation" novalidate>
			<div class="row">
				<div class="col-auto form-group custom-font">
					<label for="input_date">Datum</label>
					<input style="font-family:verdana;font-size:16px" type="text" class="form-control" id="datetimepicker1" name="input_date" data-toggle="datetimepicker" data-target="#datetimepicker1" required>
					<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
				</div>
			</div>
		</form>
		<button id="submitAnzeigen" name="submitAnzeigen" class="btn btn-primary btn-custom">Anzeigen</button>
		<div class="row mt-3">
        	<div id="show" class="col-sm-12">
			</div>
		</div>