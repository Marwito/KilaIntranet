<?php
if(isset($_GET['id'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "SELECT * FROM keis2_rezept WHERE id =
    		".$conn->real_escape_string($_GET['id'])."";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
    $database->closeConnection();
}else{
    echo 'Rezept nicht gefunden';
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
<link rel="stylesheet" href="../../../../css/styles.css">
<link rel="stylesheet" href="../../../../css/layout.css">
<title>Küchenbereich</title>
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
					<h2><b>Küchenbereich</b></h2>
					<p>ein Rezept bearbeiten</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">
        		<form name="form" method="post" action="" class="needs-validation" novalidate>
        			<input type="hidden" id="rezept" name="rezept" value="<?php echo $row['id']; ?>">
        			<div class="form-group">
    				    <label for="input_text0">Name</label>
    				    <input type="text" class="form-control" name="input_text0" id="input_text0" required value ="<?php echo $row['name']?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_select0">Zutat</label>
    				    <select class="form-control" name="input_select0" id="input_select0" required>
		    				<option value="" selected>Wählen...</option>
		    			</select>
		    			<div class="invalid-feedback"> Bitte wählen Sie eine Einheit aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_text1">Menge</label>
    				    <input type="text" class="form-control" name="input_text1" id="input_text1" required value ="<?php echo $row['menge']?>">
    				    <div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_select1">Essenskategorie</label>
    				    <select class="form-control" name="input_select1" id="input_select1" required>
		    				<option value="" selected>Wählen...</option>
		    			</select>
		    			<div class="invalid-feedback"> Bitte wählen Sie eine Essenskategorie aus! </div>
    			  	</div>
    			  	<div class="form-group">
    				    <label for="input_select2">Speisenart</label>
    				    <select class="form-control" name="input_select2" id="input_select2" required>
		    				<option value="" selected>Wählen...</option>
		    			</select>
		    			<div class="invalid-feedback"> Bitte wählen Sie eine Speisenart aus! </div>
    			  	</div>
    			  	<div class="text-right my-5">							  	
    				  	<a href="../essen_list.php#rezept" class="btn btn-primary btn-custom">Zurück</a>
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

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script>var zutatId = "<?php echo $row['zutat'];?>";</script>
<script>var essenkategorieId = "<?php echo $row['essenkategorie'];?>";</script>
<script>var speisenartId = "<?php echo $row['speisenart'];?>";</script>
<script src="../../../../js/kuechenbereich/essen/rezept/edit_rezept.js"></script>
<script src="../../../../js/sidenav.js"></script>
</body>
</html>