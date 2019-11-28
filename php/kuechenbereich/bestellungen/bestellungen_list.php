<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isCatererKueche($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
		<form name="form" id="form" method="post" action="" class="needs-validation" novalidate>
			<label>Zeitraum</label>
			<div class="row">
				<div class="col form-group custom-font">
					<label for="input_text0">von</label>
					<input style="font-family:verdana;font-size:16px" type="text" class="form-control" id="datetimepicker1" name="input_text0" data-toggle="datetimepicker" data-target="#datetimepicker1" required>
					<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
				</div>
				<div class="col form-group custom-font">
					<label for="input_text1">bis</label><br>
					<input style="font-family:verdana;font-size:16px" type="text" class="form-control"  id="datetimepicker2" data-toggle="datetimepicker" data-target="#datetimepicker2" name="input_text1" required>
					<div class="invalid-feedback"> Bitte füllen Sie dieses Feld aus! </div>
				</div>
		   </div>
		</form>
		<button id="submitAnzeigen" name="submitAnzeigen" class="btn btn-primary btn-custom">Anzeigen</button>
		<?php 
		require_once('../utilities/db_connection.php');
		$database = new DatabaseConnection();
		$conn = $database->getConn();
		$sqlAktionsgruppe = "SELECT * FROM keis2_aktionsgruppe";
		$resultAktionsgruppeForDropdown = $conn->query($sqlAktionsgruppe);
		if($resultAktionsgruppeForDropdown->num_rows > 0) {
				$aktionsgruppeSelect = '<div style="display:inline; float:right;">
    		                     <select class="form-control" name="input_select1" id="input_select1">
                                 <option value=-1 selected>Keine Aktionsgruppe</option>';
				                while($row = $resultAktionsgruppeForDropdown->fetch_assoc()){
				                    $aktionsgruppeSelect.='<option value="'.$row['id'].'"';
		    	                         $aktionsgruppeSelect.='>'.$row['bezeichnung'].'</option>';
		    	                     }
		    	                     $aktionsgruppeSelect.='</select>
				             </div>';
		    	                     echo $aktionsgruppeSelect;
		}?>
<?php

$sqlEssenkategorien = "SELECT * FROM keis2_essenkategorie";
$resultEssenkategorien = $conn->query($sqlEssenkategorien);
$sqlEinrichtungId = "SELECT einrichtung_id FROM keis2_einrichtung_kueche WHERE kueche_id = ".$session->einrichtung_kueche;
$resultEinrichtungId = $conn->query($sqlEinrichtungId);
$rowEinrichtungId = $resultEinrichtungId->fetch_assoc();
$sqlEinrichtungen = "SELECT id FROM keis2_einrichtung WHERE id = '".$rowEinrichtungId['einrichtung_id']."'";
while($rowEinrichtungId = $resultEinrichtungId->fetch_assoc()) {
    $sqlEinrichtungen = $sqlEinrichtungen." OR id = '".$rowEinrichtungId['einrichtung_id']."'";
}
$resultEinrichtungen = $conn->query($sqlEinrichtungen);
$anzahlEinrichtungen = $resultEinrichtungen->num_rows;
$arrayEinrichtungen = array();
for($i=0;$i<$anzahlEinrichtungen;$i++) {
    $rowEinrichtungen = $resultEinrichtungen->fetch_assoc();
    $arrayEinrichtungen[$i] = $rowEinrichtungen['id'];
}
require_once('../utilities/functions.php');
$customFunction = new CustomFunctions();
?>

		<div class="row mt-3">
        	<div class="col-sm-12">
				<table id="tab1" class="table table-striped table-bordered dt-responsive display nowrap" style="width:100%">
			    	<thead>
					    <tr>
					    	<th></th>
						    <th scope="col">Gesamt</th>
						    <?php 
						    for($i = 0; $i < $anzahlEinrichtungen;$i++) {
						        echo '<th scope="col">'.$customFunction->getNameEinrichtung($arrayEinrichtungen[$i]).'</th>';
						    }
						    ?>
				    	</tr>
			  		</thead>
			  		<tbody>			
<?php
while($rowEssenkategorien = $resultEssenkategorien->fetch_assoc()) {
    ?>
						<tr>
							<td></td>
							<td><?php echo $rowEssenkategorien['kategorie']?></td>
							<?php 
							for($i = 0; $i < $anzahlEinrichtungen;$i++) {
					        	echo '<td id="ess'.$rowEssenkategorien['id'].'einr'.$arrayEinrichtungen[$i].'"></td>';
							}?>
						</tr>
<?php 
} ?>
						<tr>
							<td></td>
							<td>Summe</td>
							<?php
							for($i = 0; $i < $anzahlEinrichtungen;$i++) {
					        	echo '<td id="summe'.$arrayEinrichtungen[$i].'"></td>';
							}?>
						</tr>
<?php
$database->closeConnection();
?>	
					</tbody>
				</table>
			</div>
		</div>
<script>var kuechenId = "<?php echo $session->einrichtung_kueche;?>";</script>