<?php 
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isEltern($session->usergroup) || $benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css">
<link rel="stylesheet" href="../../css/datatables.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link rel="stylesheet" href="../../css/styles.css">
<link rel="stylesheet" href="../../css/layout.css">
<title>Elternbereich</title>
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
					<h2><b>Elternbereich</b></h2>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<div class="card">
        			<div class="card-header">
        		    	<ul class="nav nav-tabs card-header-tabs" role="tablist" id="myTab">
        		      		<li class="nav-item">
        		        		<a class="nav-link active" href="#bestellung" id="bestellungen-tab" data-toggle="tab" role="tab" aria-controls="bestellungen" aria-selected="true">Bestellungen</a>
        		      		</li>
        		      		<?php 
        		      		if($benutzer->isEltern($session->usergroup)) {
        		      		    echo '<li class="nav-item">
        	        			        <a class="nav-link" href="#rechnung" id="rechnungen-tab" data-toggle="tab" role="tab" aria-controls="rechnungen" aria-selected="false">Rechnungen</a>
        		     		           </li>';
        		      		} ?>
        		      		<li class="nav-item">
        		        		<a class="nav-link" href="#speiseplan" id="speiseplan-tab" data-toggle="tab" role="tab" aria-controls="speiseplan" aria-selected="true">Speiseplan</a>
        		      		</li>
        		    	</ul>
        		  	</div>
        	  		<div class="card-body">
        				<div class="tab-content" id="myTabContent">
        					<div class="tab-pane fade show active" id="bestellung" role="tabpanel" aria-labelledby="bestellungen-tab">
                                <?php
                                require_once('bestellungen/bestellung_list.php');
                                ?>	
        					</div>
        					<?php 
        					if($benutzer->isEltern($session->usergroup)) {
        					echo '<div class="tab-pane fade" id="rechnung" role="tabpanel" aria-labelledby="rechnungen-tab">';
                                      require_once("rechnungen/rechnungen_list.php");
    						echo '</div>';
        					} ?>
        					<div class="tab-pane fade show" id="speiseplan" role="tabpanel" aria-labelledby="speiseplan-tab">
                                <?php
                                require_once('speiseplan/speiseplan.php');
                                ?>	
        					</div>
    					</div>
    				</div>
    			</div>		
			</div>
		</div>
	</div>	
    <div class="navbar_bottom">
        <a href="#" class="navbar_bottom_link_left">Impressum</a>
        <a href="#" class="navbar_bottom_link_right">Datenschutz</a>
    </div>   
</div>
<div class="modal" id="modalEssenskategorie" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bestellen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="essenskategorieForm" id="essenskategorieForm" method="post" action="" class="needs-validation" novalidate>
    		<label for="input_select0">Essenkategorie</label>
    		<select class="form-control" name="input_select0" id="input_select0" required>
		    	<option value="" selected>Wählen...</option>
		    </select>
		<div class="invalid-feedback">Bitte wählen Sie eine Essenskategorie aus! </div>
    	</form>
      </div>
      <div class="modal-footer">
        <button type="button" id="modalSave" class="btn btn-primary">Bestellen</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="modalEssenskategorieZeitraum" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bestellen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="essenskategorieFormZeitraum" id="essenskategorieFormZeitraum" method="post" action="" class="needs-validation" novalidate>
    		<label for="input_select2">Essenkategorie</label>
    		<select class="form-control" name="input_select2" id="input_select2" required>
		    	<option value="" selected>Wählen...</option>
		    </select>
		<div class="invalid-feedback">Bitte wählen Sie eine Essenskategorie aus! </div>
    	</form>
      </div>
      <div class="modal-footer">
        <button type="button" id="modalSaveZeitraum" class="btn btn-primary">Bestellen</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment-with-locales.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../../js/elternbereich/elternbereich.js"></script>
<script src="../../js/sidenav.js"></script>
</body>
</html>