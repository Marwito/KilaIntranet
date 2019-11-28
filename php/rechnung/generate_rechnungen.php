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
}else{
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" href="../../css/styles.css">
<link rel="stylesheet" href="../../css/layout.css">
<title>Rechnungen</title>
</head>
<body>
    <?php 
        require_once('../utilities/navigation.php');
    ?>
<nav class="navbar navbar-expand-lg navbar-light nav_backgroundImage" id="myNavbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><i class="fas fa-align-justify" onclick="openNav()"></i></a>
		</div>
        <?php 
            require_once('../utilities/header_navigation.php');
        ?>
	</div>
</nav>
<div id="main" style="padding:0; margin:0;">
	<div class="container-fluid" style="background-color:#f6f5f5;">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-sm-11 offset-sm-1" style="padding-top:70px; padding-bottom:70px;">
					<h2><b>Rechnungen</b></h2>
					<p> Rechnungen erstellen</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form name="form" class="needs-validation" novalidate>
    				<label>Rechnungserstellung :</label>
    				<div class="custom-control-inline ml-3">
                		<div class="custom-control custom-radio custom-control-inline mb-2">
                        	<input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">
                          	<label class="custom-control-label" for="customRadio1">Monat/Jahr</label>
                		</div>
                		<div class="custom-control custom-radio custom-control-inline mb-2">
                          	<input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">
                          	<label class="custom-control-label" for="customRadio2">Zeitraum</label>
            			</div>
        			</div>
        			<div class="form-group" id="monat">
            		    <label for="input_select0">Monat : </label>
        			    <select class="form-control" name="input_select0" id="input_select0" required>
        			    	<option value="" selected>Wählen...</option>
        			    	<option value="1">Januar</option>
        			    	<option value="2">Februar</option>
        			    	<option value="3">März</option>
        			    	<option value="4">April</option>
        			    	<option value="5">Mai</option>
        			    	<option value="6">Juni</option>
        			    	<option value="7">Juli</option>
        			    	<option value="8">August</option>
        			    	<option value="9">September</option>
        			    	<option value="10">Oktober</option>
        			    	<option value="11">November</option>
        			    	<option value="12">Dezember</option>	
        			    </select>
        			    <div class="invalid-feedback"> Bitte wählen Sie einen Monat aus! </div>
    			    </div>
    			    <div class="form-group" id="jahr">
    				    <label for="input_text0">Jahr :</label>
    				    <input type="text" class="form-control" name="input_text0" id="input_text0" autocomplete="off" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
				    </div>
				    <div class="form-group" id="zeitraum_von">
    				    <label for="datetimepicker1">Von :</label>
    					<div class="form-group custom-font">
    					    <input style="font-family:verdana;font-size:16px" type="text" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker1" id="datetimepicker1" name="input_text1" required>
    				  		<div class="invalid-feedback" style="font-family:verdana"> Bitte füllen Sie dieses Feld aus! </div>
    				  	</div>
				  	</div>
				  	<div class="form-group" id="zeitraum_bis">
    				  	<label for="datetimepicker2">Bis :</label>
    					<div class="form-group custom-font">
    					    <input style="font-family:verdana;font-size:16px" type="text" class="form-control" data-toggle="datetimepicker" data-target="#datetimepicker2" id="datetimepicker2" name="input_text2" required>
    				  		<div class="invalid-feedback" style="font-family:verdana"> Bitte füllen Sie dieses Feld aus! </div>
    				  	</div>
				  	</div>
				  	<div class="form-group">
    				    <label for="input_text3">Verwendungszweck :</label>
    				    <input type="text" class="form-control" name="input_text3" id="input_text3" autocomplete="off" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
				    </div>
				    <div class="text-right form-group my-5">
				    	<a href="../administration/einstellungen.php#rechnungen" class="btn btn-primary btn-custom">Zurück</a>
    				  	<button id="submit" name="submit" class="btn btn-primary btn-custom">Rechnungslauf erstellen</button>
			    	</div>
        		</form>	
			</div>
		</div>
	</div>	
    <div class="navbar_bottom">
        <a href="#" class="navbar_bottom_link_left">Impressum</a>
        <a href="#" class="navbar_bottom_link_right">Datenschutz</a>
    </div>   
</div>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../../js/rechnung/generate_rechnungen.js"></script>
<script src="../../js/sidenav.js"></script>
</body>
</html>