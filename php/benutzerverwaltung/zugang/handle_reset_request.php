<?php
// Check for tokens in the URL
$selector = filter_input(INPUT_GET, 'selector');
$validator = filter_input(INPUT_GET, 'validator');
?>
<!doctype html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" href="../../../css/styles.css">
<link rel="stylesheet" href="../../../css/layout.css">
<title>Benutzerverwaltung</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light nav_backgroundImage" id="myNavbar" style="padding: 2.5rem 1rem">
</nav>
<div id="main" style="padding:0; margin:0;">
	<div class="container-fluid" style="background-color:#f6f5f5;">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-sm-11 offset-sm-1" style="padding-top:70px; padding-bottom:70px;">
					<h2><b>Benutzerverwaltung</b></h2>
					<p>Passwort rücksetzen</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
		<div class="row">
			<div class="col-sm-10 offset-sm-1" id="message"></div>
		</div>
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form id="form1" name="form1" method="post" action="" class="needs-validation" novalidate>
        			<input type="hidden" name="selector" value="<?php echo $selector; ?>">
        			<input type="hidden" name="validator" value="<?php echo $validator; ?>">
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
    				    <div class="contact-benutzen-tip"></div>
    			  	</div>
    			  	<div class="text-right my-5">							  	
    				  	<button id="submit" name="submit" class="btn btn-primary btn-custom">Submit</button>
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
<script src="../../../js/benutzerverwaltung/zugang/handle_reset_request.js"></script>
</body>
</html>