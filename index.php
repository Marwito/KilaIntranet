<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<link rel="stylesheet" href="css/styles_loginform.css">
</head>

<body>
<div class="container vertical-center" style="max-width:450px; background-color:#FFF;">
    <div class="row" style="padding:20px;">
    	<div class="col-md-12">
    		<div id="message">
        		<?php 
        		if (isset($_GET['login_error']) || isset($_GET['password_error']) || isset($_GET['checklogin_error']) || isset($_GET['no_login']) || isset($_GET['user_deaktiviert'])) {
                    
                    	echo "<div class='alert alert-danger alert-dismissible fade show' role='alert' style='font-size:0.8rem'>";
                        
                        if (isset($_GET['login_error']) || isset($_GET['password_error'])) {
                        		echo "Der Benutzername und/oder das Passwort wurde falsch eingegeben!";	
                    	} 
                    	if (isset($_GET['checklogin_error'])) {
                    	    echo "Beim Überprüfen Ihrer Anmeldedaten ist ein Problem aufgetreten!";
                    	}
                    	
                    	if (isset($_GET['no_login'])) {
                    	    echo "Sie müssen sich anmelden !";
                    	}
                    	
                    	if (isset($_GET['user_deaktiviert'])) {
                    	    echo "Ihr Konto ist bereits deaktiviert !";
                    	}
  
                        echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
                        echo "</div>";				
                    }
                ?>	
    		</div>
    		<h4><b>LOGIN</b></h4>
    		<div class="alert alert-light" style="padding:0px; font-size:0.8rem; margin-top: 1rem">Geben Sie Ihren Benutzernamen und Ihr Passwort ein, um sich an der Website anzumelden:
    		</div>
            <!-- ###LOGIN_FORM### -->
    		<form id="form1" action="php/login/check_login.php" method="post" class="needs-validation" novalidate>
    			<div class="form-group">
					<input type="text" class="form-control" placeholder="Benutzername" name="myusername" required>
					<div class="invalid-feedback">Bitte füllen Sie dieses Feld aus !</div>
    			</div>
    			<div class="form-group">
    				<input type="password" class="form-control" placeholder="Passwort" name="mypassword" required>
    				<div class="invalid-feedback">Bitte füllen Sie dieses Feld aus !</div>
    			</div>
    			<div style="text-align:right;">
    			  <input type="submit" id="submit1" name="submit" value="Anmelden" class="btn btn-primary btn-custom">
    			</div>
      		</form>
      			<a href='#' data-toggle="modal" data-target="#modal1" style="font-size: 0.8rem">Passwort vergessen ?</a>
      		<hr>
            <!-- ###LOGIN_FORM### -->  
    		<div style="text-align:center; margin-top:5px;">
    		  <img src="img/logo_kinderland_plus_ggmbh.jpg">
    		</div>
    	</div>
    </div>
</div>
<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1Label" aria-hidden="true">
   	<div class="modal-dialog" role="document">
       	<div class="modal-content">
       		<div class="modal-header">
           		<h5 class="modal-title" id="modal1Label">Passwort zurücksetzen</h5>
           		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
           		</button>
       		</div>
       		<form id="form2" name="form2" method="post" action="" class="needs-validation" novalidate>
	   			<div class="modal-body">
	   				<div class="form-group">
	   					<p style="font-size: 0.8rem">Bitte geben Sie Ihre E-Mail-Adresse an, damit wir Ihnen den Link zum Zurücksetzen senden können.</p>
	   				</div>
		    		<div class="form-group">
    				    <label for="input_text0">Email</label>
    				    <input type="email" class="form-control" name="input_text0" id="input_text0" required>
    				    <div class="invalid-feedback" id="custom-fehler1"></div>
    			  	</div>
	      		</div>
	       		<div class="modal-footer">
			       	<button type="submit" class="btn btn-primary btn-custom" id="submit2">Submit</button>
	       		</div>
       		</form>
       	</div>
   	</div>
</div>		
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script src="js/index.js"></script>
</body>
</html>