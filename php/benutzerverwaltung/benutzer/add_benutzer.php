<?php 
require_once('../../login/session.php');
require_once('../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('benutzer_class.php');
    $benutzer = new Benutzer();
    if(!($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    }
    $position = $session->usergroup;
}else {
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
<link rel="stylesheet" href="../../../css/styles.css">
<link rel="stylesheet" href="../../../css/layout.css">
<title>Benutzerverwaltung</title>
</head>
<body>
    <?php 
        if(!@include_once('../utilities/navigation.php')) {
            require_once('../../utilities/navigation.php');
        }
    ?>
<nav class="navbar navbar-expand-lg navbar-light nav_backgroundImage" id="myNavbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><i class="fas fa-align-justify" onclick="openNav()"></i></a>
		</div>
        <?php 
            if(!@include_once('../utilities/header_navigation.php')) {
                require_once('../../utilities/header_navigation.php');
            }
        ?>  
	</div>
</nav>
<div id="main" style="padding:0; margin:0;">
	<div class="container-fluid" style="background-color:#f6f5f5;">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-sm-11 offset-sm-1" style="padding-top:70px; padding-bottom:70px;">
					<h2><b>Benutzerverwaltung</b></h2>
					<p>Einen neuen Benutzer hinzufügen</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form id="form1" name="form1" method="post" action="" class="needs-validation" novalidate>
        			<div class="form-group">
    				    <label for="input_text0">Benutzername</label>
    				    <input type="text" class="form-control" name="input_text0" id="input_text0" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_passwort0">Passwort</label>
    				    <input type="password" class="form-control" name="input_passwort0" id="input_passwort0" pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$" required>
    				    <div class="password-tip">Mindestens 8 Zeichen einschließlich einer Ziffer, eines Buchstabens und eines Sonderzeichens @$!%*#?&</div>
    				    <div class="invalid-feedback" id="custom-fehler1"></div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_passwort1">Passwort wiederholen</label>
    				    <input type="password" class="form-control" name="input_passwort1" id="input_passwort1" pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$" required>
    				    <div class="invalid-feedback" id="custom-fehler2"></div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text1">Vorname</label>
    				    <input type="text" class="form-control" name="input_text1" id="input_text1" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text2">Name</label>
    				    <input type="text" class="form-control" name="input_text2" id="input_text2" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
					<div class="form-group">
						<label for="input_select0">Position</label>
						<select class="form-control" name="input_select0" id="input_select0" required>
							<option value="" selected>Wählen...</option>
						</select>
						<div class="invalid-feedback"> Bitte wählen Sie eine Position aus! </div>
					</div>
    			  	<div class="form-group" id="einrichtung_kueche">
    				    <label for="input_select1" id="label"></label>
    				    <select class="form-control" name="input_select1" id="input_select1">
		    				<option value="" selected>Wählen...</option>
		    			</select>
		    			<div class="invalid-feedback" id="nachricht"></div>
    			  	</div>
    			  	<div class="custom-control custom-checkbox mt-4 mb-3">
    					<input type="checkbox" class="custom-control-input" id="input_checkbox0" name="input_checkbox0" value="1">
    					<label class="custom-control-label" for="input_checkbox0">Aktiv ?</label>
					</div>
				  	<div class="form-group">
    				    <label for="input_text4">Telefon</label>
    				    <input type="text" class="form-control" name="input_text4" id="input_text4" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text5">Mobil</label>
    				    <input type="text" class="form-control" name="input_text5" id="input_text5" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text6">Email</label>
    				    <input type="email" class="form-control" name="input_text6" id="input_text6" required>
    				    <div class="invalid-feedback" id="custom-fehler3"></div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text7">Straße/Hsnr</label>
    				    <input type="text" class="form-control" name="input_text7" id="input_text7" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text8">PLZ</label>
    				    <input type="text" class="form-control" name="input_text8" id="input_text8" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text9">Ort</label>
    				    <input type="text" class="form-control" name="input_text9" id="input_text9" required>
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="text-right my-5">							  	
    				  	<a href="./benutzer_list.php" class="btn btn-primary btn-custom">Zurück</a>
    				  	<button type="reset" class="btn btn-primary btn-custom">Abbrechen</button>
    				  	<button id="submit" name="submit" class="btn btn-primary btn-custom">Speichern</button>
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
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1Label" aria-hidden="true">
   	<div class="modal-dialog" role="document">
       	<div class="modal-content">
       		<div class="modal-header">
           		<h5 class="modal-title" id="modal1Label">Zugangsdaten schicken</h5>
           		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
           		</button>
       		</div>
   			<div class="modal-body" style="font-size:0.8rem">
   				<p style="font-size: 0.8rem"> bitte clicken Sie auf diesen Button, um die Zugangsdaten per Email zum Benutzer zuschicken !</p>
      		</div>
       		<div class="modal-footer">
		       	<button type="submit" class="btn btn-primary btn-custom" id="submit2">Schicken</button>
       		</div>
       	</div>
   	</div>
</div>	
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script>var position = "<?php echo $position; ?>";</script>
<script src="../../../js/benutzerverwaltung/benutzer/add_benutzer.js"></script>
<script src="../../../js/sidenav.js"></script>
</body>
</html>