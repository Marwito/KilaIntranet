<?php
require_once('../../../login/session.php');
require_once('../../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../../../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        if(isset($_GET['id'])){
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "SELECT * FROM keis2_ansprechpartner_amt WHERE id =
    		".$conn->real_escape_string($_GET['id'])."";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
            $database->closeConnection();
        }else{
            echo 'Ansprechpartner nicht gefunden';
        }
    }
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
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" href="../../../css/styles.css">
<link rel="stylesheet" href="../../../css/layout.css">
<title>Administrationbereich</title>
</head>
<body>
    <?php 
        if(!@include_once('../utilities/navigation.php')) {
            require_once('../../../utilities/navigation.php');
        }
    ?>
<nav class="navbar navbar-expand-lg navbar-light nav_backgroundImage" id="myNavbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><i class="fas fa-align-justify" onclick="openNav()"></i></a>
		</div>
        <?php 
            if(!@include_once('../utilities/header_navigation.php')) {
                require_once('../../../utilities/header_navigation.php');
            }
        ?>  
	</div>
</nav>
<div id="main" style="padding:0; margin:0;">
	<div class="container-fluid" style="background-color:#f6f5f5;">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-sm-11 offset-sm-1" style="padding-top:70px; padding-bottom:70px;">
					<h2><b>Administrationbereich</b></h2>
					<p>einen Ansprechpartner bearbeiten</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form name="form" method="post" action="" class="needs-validation" novalidate>
        			<input type="hidden" id="ansprechpartner" name="ansprechpartner" value="<?php echo $row['id']; ?>">
    				<div class="form-group">
    				    <label for="input_text0">Vorname</label>
    				    <input type="text" class="form-control" name="input_text0" id="input_text0" required value="<?php echo $row['vorname']; ?>"> 
      					<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text1">Name</label>
    				    <input type="text" class="form-control" name="input_text1" id="input_text1" required value="<?php echo $row['name']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text2">Telefonnummer</label>
    				    <input type="text" class="form-control" name="input_text2" id="input_text2" required value="<?php echo $row['telefonnummer']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text3">Mobil</label>
    				    <input type="text" class="form-control" name="input_text3" id="input_text3" required value="<?php echo $row['mobil']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text4">Email</label>
    				    <input type="email" class="form-control" name="input_text4" id="input_text4" required value="<?php echo $row['email']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus oder überprüfen Sie Ihre Eingabe ! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text5">Fax</label>
    				    <input type="text" class="form-control" name="input_text5" id="input_text5" required value="<?php echo $row['fax']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text6">Straße/Hsnr</label>
    				    <input type="text" class="form-control" name="input_text6" id="input_text6" required value="<?php echo $row['strasse']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text7">PLZ</label>
    				    <input type="text" class="form-control" name="input_text7" id="input_text7" required value="<?php echo $row['plz']; ?>" maxlength="10">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text8">Ort</label>
    				    <input type="text" class="form-control" name="input_text8" id="input_text8" required value="<?php echo $row['ort']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="custom-control custom-checkbox mt-4 mb-3">
    					<input type="checkbox" class="custom-control-input" id="input_checkbox0" name="input_checkbox0" value="1" <?php echo ($row['rechnung'] == 1 ? 'checked' : ''); ?>>
    					<label class="custom-control-label" for="input_checkbox0">Ansprechepartner für Rechnungen?</label>
					</div>
                    <div class="text-right my-5">							  	
    				  	<a href="../amt/edit_amt.php?id=<?php echo $row['amt']; ?>" class="btn btn-primary btn-custom">Zurück</a>
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

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script>var amt = "<?php echo $row['amt'];?>";</script>
<script src="../../../js/administration/ansprechpartner_amt/edit_ansprechpartner.js"></script>
<script src="../../../js/sidenav.js"></script>
</body>
</html>