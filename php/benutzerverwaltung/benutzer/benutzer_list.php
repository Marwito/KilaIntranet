<?php 
require_once('../../login/session.php');
require_once('../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup', 'einrichtung')){
    require_once('benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
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
<link rel="stylesheet" href="../../../css/datatables.min.css">
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
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" style="padding-top:60px;">
        <div class="row">
        	<div class="col-sm-10 offset-sm-1">

<?php
require_once('../../utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT * FROM keis2_benutzer";
$user = 1;
if($benutzer->isLeiter($session->usergroup)) {
    $sql = $sql." WHERE einrichtung = '".$conn->real_escape_string($session->einrichtung_kueche)."'
                  OR id IN (SELECT eltern FROM keis2_kind WHERE zuordnung_einrichtung=".$conn->real_escape_string($session->einrichtung_kueche).")
                  OR kueche IN (SELECT kueche_id FROM keis2_einrichtung_kueche WHERE einrichtung_id=".$conn->real_escape_string($session->einrichtung_kueche).")";
    $user = 0;
}
$result = $conn->query($sql);
?>
				<div class="row mt-3">		
				    <div class="col-sm-12">
						<table id="table1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
					    	<thead>
							    <tr>
							    	<th></th>
							    	<th scope="col" style="text-align:center">
							    		<a class="btn btn-success btn-circle custom1" href="add_benutzer.php"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
						    		</th>
						    		<th scope="col">Benutzername</th>
								    <th scope="col">Vorname</th>
								    <th scope="col">Name</th>
								    <th scope="col">Position</th>
								    <?php 
                                    if ($benutzer->isAdmin($session->usergroup)) {
                                    ?>
								    <th scope="col">Einrichtung/Küche</th>
								    <?php 
                                    }
                                    ?>
								    <th scope="col">Aktiv</th>
								    <th scope="col">Telefon</th>
								    <th scope="col">Mobil</th>
								    <th scope="col">Email</th>
								    <th scope="col">Straße/Hsnr</th>
								    <th scope="col">PLZ</th>
								    <th scope="col">Ort</th>
						    	</tr>
					  		</thead>
					  		<tbody>			
<?php
if ($result->num_rows > 0) {
    require_once('../../utilities/functions.php');
    $customFunction = new CustomFunctions();
	while($row = $result->fetch_assoc()) {
?>
								<tr id="<?php echo $row['id']; ?>" <?php if($row['benutzername'] == $session->username) { echo ' style="background-color:lightblue;"'; } ?>>
									<td></td>
							        <td style="text-align:center">
							        	<a href="edit_benutzer.php?id=<?php echo $row['id'];?>" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a>
							        	<!-- <a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a> -->
							        </td>
							        <td> <?php echo $row['benutzername']; ?> </td>
							        <td> <?php echo $row['vorname']; ?> </td>									        
							        <td> <?php echo $row['name']; ?> </td>
							        <td> <?php echo $customFunction->getNamePosition($row['position']); ?> </td>
							        <?php 
                                    if ($benutzer->isAdmin($session->usergroup)) {
                                        if ($row['position'] == 4 || $row['position'] == 5) {
                                            $value = $customFunction->getNameEinrichtung($row['einrichtung']);
                                        } else if($row['position'] == 3){
                                            $value = $customFunction->getNameKueche($row['kueche']);
                                        } else {
                                            $value = '';
                                        }
                                    ?>
							        <td> <?php echo $value; ?> </td>
									<?php 
                                    }
                                    ?>
							        <td style="text-align:center"> <input type="checkbox" <?php echo ($row['aktiv'] == 1 ? 'checked' : ''); ?>> </td>
							        <td> <?php echo $row['telefon']; ?> </td>
							        <td> <?php echo $row['mobil']; ?> </td>
							        <td> <?php echo $row['email']; ?> </td>
							        <td> <?php echo $row['strasse']; ?> </td>
							        <td> <?php echo $row['plz']; ?> </td>
							        <td> <?php echo $row['ort']; ?> </td>
	      						</tr>
<?php 
	}
}
$database->closeConnection();
?>	
							</tbody>
						</table>
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
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script>var user = "<?php echo $user;?>";</script>
<script src="../../../js/benutzerverwaltung/benutzer/benutzer_list.js"></script>
<script src="../../../js/sidenav.js"></script>
</body>
</html>