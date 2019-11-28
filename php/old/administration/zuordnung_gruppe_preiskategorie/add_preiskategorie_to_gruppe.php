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
        if(!isset($_GET['preiskategorie'])){
            echo 'Fehler : Preiskategorie nicht gefunden';
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
    <div id='mySidenav' class='sidenav'>
    	<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>&times;</a>
    	<a href=''><img src='../../../img/KiLa_Icons_20180719_Home.png' title='Home'/></a><br>
    	<a href=''><img src='../../../img/KiLa_Icons_20180813_Newsarchiv.png' title='Newsarchiv'/></a><br>
    	<a href='./interface/interface_typo3_php.php?page=dienstplan'><img src='../../../img/KiLa_Icons_20180719_Dienstplan.png' title='Dienstplan'/></a><br>
    	<a href='./interface/interface_typo3_php.php?page=stundenzettel'><img src='../../../img/KiLa_Icons_20180719_Stundenzettel.png' title='Stundenzettel'/></a><br>
    	<a href='./interface/interface_typo3_php.php?page=stellenanzeigen'><img src='../../../img/KiLa_Icons_20180719_Stellen.png' title='Stellenangebote'/></a><br>
    	<a href='./interface/interface_typo3_php.php?page=qmdokumente'><img src='../../../img/KiLa_Icons_20180719_QMDokumente.png' title='QM-Dokumente'/></a><br>
    
    	<?php
    	   //if($user->isAdmin($session->usergroup) || $user->isLeiter($session->usergroup)) {
    	       echo "<a href='./interface/interface_typo3_php.php?page=einstellungen'><img src='../../../img/KiLa_Icons_20180719_Einstellungen.png' title='Einstellungen'/></a><br>";
    	       echo "<a href='./interface/interface_typo3_php.php?page=benutzerverwaltung'><img src='../../../img/KiLa_Icons_20180719_AllgMitarbeiterverwaltung.png' title='Mitarbeiterverwaltung'/></a><br>";
            //}
        ?>
    
    </div>
<nav class="navbar navbar-expand-lg navbar-light nav_backgroundImage" id="myNavbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="#"><i class="fas fa-align-justify" onclick="openNav()"></i></a>
		</div>
    	<div>
        	<div class="nav_icons_right">
        		<a class="nav-link" href="./interface/logout.php"><img src="../../../img/KiLa_Icons_20180719_Logout.png" style="width:100%; height:auto;"/></a>
        	</div>
        	<div class="nav_icons_right">
        		<a class="nav-link" href="./benutzerverwaltung/mitarbeiter_daten.php"><img src="../../../img/KiLa_Icons_20180719_Profil.png" style="width:100%; height:auto;"/></a>
        	</div>
    	</div>
	</div>
</nav>
<div id="main" style="padding:0; margin:0;">
	<div class="container-fluid" style="background-color:#f6f5f5;">
		<div class="row">
			<div class="col-lg-12">
				<div class="col-sm-11 offset-sm-1" style="padding-top:70px; padding-bottom:70px;">
					<h2><b>Administrationbereich</b></h2>
					<p>eine Gruppe mit einer Preiskategorie zuordnen</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form name="form" method="post" action="" class="needs-validation" novalidate>
        			<input type="hidden" id="preiskategorie" name="preiskategorie" value="<?php echo $_GET['preiskategorie']; ?>">
    			  	<div class="form-group">
    				    <label for="input_select0">Gruppe</label>
    				    <select class="form-control" name="input_select0" id="input_select0" required>
    				    	<option value="" selected>Wählen ...</option>
    				    </select>
    				    <div class="invalid-feedback"> Bitte wählen Sie eine Gruppe aus! </div>
    			  	</div>
                    <div class="text-right my-5">							  	
    				  	<a href="../preiskategorie/edit_preiskategorie.php?id=<?php echo $_GET['preiskategorie']; ?>" class="btn btn-primary btn-custom">Zurück</a>
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
<script src="../../../js/administration/preiskategorie/add_preiskategorie_to_gruppe.js"></script>
<script src="../../../js/sidenav.js"></script>
</body>
</html>