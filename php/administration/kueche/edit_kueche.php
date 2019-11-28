<?php
require_once('../../login/session.php');
require_once('../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        if(isset($_GET['id'])){
            require_once('../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "SELECT * FROM keis2_kueche WHERE id =
    		".$conn->real_escape_string($_GET['id'])."";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            }
            $database->closeConnection();
        }else{
            echo 'Einrichtung nicht gefunden';
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
<link rel="stylesheet" href="../../../css/datatables.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" href="../../../css/styles.css">
<link rel="stylesheet" href="../../../css/layout.css">
<title>Administrationbereich</title>
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
					<h2><b>Administrationbereich</b></h2>
					<p>eine Küche bearbeiten</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form name="form" method="post" action="" class="needs-validation" novalidate>
        			<input type="hidden" id="kueche" name="kueche" value="<?php echo $row['id']; ?>">
    				<div class="form-group">
    				    <label for="input_text0">Name</label>
    				    <input type="text" class="form-control" name="input_text0" id="input_text0" required value="<?php echo $row['name']; ?>">
      					<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text1">Straße/Hsnr</label>
    				    <input type="text" class="form-control" name="input_text1" id="input_text1" required value="<?php echo $row['strasse']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text2">PLZ</label>
    				    <input type="text" class="form-control" name="input_text2" id="input_text2" required value="<?php echo $row['plz']; ?>" maxlength="10">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text3">Ort</label>
    				    <input type="text" class="form-control" name="input_text3" id="input_text3" required value="<?php echo $row['ort']; ?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="mt-3 mb-2">
		  			<label><strong>Ansprechpartner</strong></label>
    			  	</div>
    			  	<div class="row">
    					<div id="div_ansprechpartner" class="col-sm-12 table-responsive"></div>
    				</div>
    				<div class="mt-3 mb-2">
		  			<label><strong>Einrichtungen</strong></label>
    			  	</div>
    			  	<div class="row">
    					<div id="div_einrichtungen" class="col-sm-12 table-responsive"></div>
    				</div>   				
                    <div class="text-right my-5">							  	
    				  	<a href="../einstellungen.php#kueche" class="btn btn-primary btn-custom">Zurück</a>
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

<!-- Modal -->
<div class="modal fade" tabindex="-1" id="choice_ansprechpartner_dialog" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ansprechpartner hinzuf&uuml;gen</h4>
            </div>
            <div class="modal-body">
			<?php require_once('../amt/select_ansprechpartner.php'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" name="close_ansprechpartner_dialog" id="close_ansprechpartner_dialog">schlie&szlig;en</button>
        		<button type="button" class="btn btn-primary" data-dismiss="modal" name="transfer_ansprechpartner_dialog" id="transfer_ansprechpartner_dialog">&uuml;bernehmen</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" tabindex="-1" id="choice_einrichtungen_dialog" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Einrichtung hinzuf&uuml;gen</h4>
            </div>
            <div class="modal-body">
			<?php require_once('../amt/select_einrichtungen.php'); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" name="close_einrichtungen_dialog" id="close_einrichtungen_dialog">schlie&szlig;en</button>
        		<button type="button" class="btn btn-primary" data-dismiss="modal" name="transfer_einrichtungen_dialog" id="transfer_einrichtungen_dialog">&uuml;bernehmen</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script>var kueche = "<?php echo $row['id'];?>";</script>
<script src="../../../js/administration/kueche/edit_kueche.js"></script>
<script src="../../../js/sidenav.js"></script>
</body>
</html>