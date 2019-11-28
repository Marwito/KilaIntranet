<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup', 'einrichtung')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
require_once('../utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sqlKind = "SELECT * FROM keis2_kind";
if($benutzer->isLeiter($session->usergroup)) {
    $sqlKind = $sqlKind." WHERE zuordnung_einrichtung=".$session->einrichtung_kueche;
}
$resultKind = $conn->query($sqlKind);
$sqlDauerbestellung = "SELECT * FROM keis2_dauerbestellung WHERE DATEDIFF(essenende,'".$conn->real_escape_string(date('Y-m-d'))."')>=0 AND kind = '";
?>
        <div class="row mt-3">
        	<div class="col-sm-12">
				<table id="table1" class="table table-striped table-bordered dt-responsive display nowrap" style="width:100%">
			    	<thead>
					    <tr>
					    	<th></th>
					    	<th></th>
						    <th scope="col">Vorname</th>
						    <th scope="col">Name</th>
						    <th scope="col">Geburtsdatum</th>
						    <th scope="col">Beitragszahler Vorname</th>
						    <th scope="col">Beitragszahler Name</th>
						    <th scope="col">Debitorennummer</th>
						    <th scope="col">Straße</th>
						    <th scope="col">Postleitzahl</th>
						    <th scope="col">Ort</th>
						    <th scope="col">Email</th>
						    <th scope="col">Essenskategorie</th>
						    <th scope="col">Dauerbestellung</th>
						    <th scope="col">Montag</th>
						    <th scope="col">Dienstag</th>
						    <th scope="col">Mittwoch</th>
						    <th scope="col">Donnerstag</th>
						    <th scope="col">Freitag</th>
						    <th scope="col">Essenstart</th>
						    <th scope="col">Essenende</th>
						    <th scope="col">Zuordnung Einrichtung</th>
						    <th scope="col">Zuordnung Gruppe</th>
						    <th scope="col">Zuordnung Aktionsgruppe</th>
				    	</tr>
			  		</thead>
			  		<tbody>			
<?php
if ($resultKind->num_rows > 0) {
    require_once('../utilities/functions.php');
    $customFunction = new CustomFunctions();
	while($rowKind = $resultKind->fetch_assoc()) {
	    $resultDauerbestellung = $conn->query($sqlDauerbestellung.$rowKind['id']."'");
	    if($resultDauerbestellung->num_rows > 0) {
	        $rowDauerbestellung = $resultDauerbestellung->fetch_assoc();
	        $dauerbestellung = true;
	    } else {
	        $dauerbestellung = false;
	    }
?>
						<tr id="<?php echo $rowKind['id']; ?>">
							<td></td>
							<td><a href="#" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a></td>
					        <td><?php echo $rowKind['vorname']; ?></td>									        
					        <td><?php echo $rowKind['name']; ?></td>
					        <td><?php echo DateTime::createFromFormat('Y-m-d', $rowKind['geburtsdatum'])->format('d.m.Y'); ?></td>
					        <td><?php echo $rowKind['beitragszahler_vorname']; ?></td>
					        <td><?php echo $rowKind['beitragszahler_name']; ?></td>
					        <td><?php echo $rowKind['debitorennummer']; ?></td>
					        <td><?php echo $rowKind['strasse']; ?></td>
					        <td><?php echo $rowKind['plz']; ?></td>
					        <td><?php echo $rowKind['ort']; ?></td>
					        <td><?php echo $rowKind['email']; ?></td>
					        <td><?php echo ($dauerbestellung ? $customFunction->getNameEssenkategorie($rowDauerbestellung['essenkategorie']) : ""); ?></td>
					        <td><?php echo ($dauerbestellung ? "Ja" : "Nein")?></td>
					        <td style="text-align:center"> <input type="checkbox" <?php echo ($dauerbestellung ? ($rowDauerbestellung['montag'] == 1 ? 'checked' : ''): ''); ?> disabled></td>
					        <td style="text-align:center"> <input type="checkbox" <?php echo ($dauerbestellung ? ($rowDauerbestellung['dienstag'] == 1 ? 'checked' : ''): ''); ?> disabled></td>
					        <td style="text-align:center"> <input type="checkbox" <?php echo ($dauerbestellung ? ($rowDauerbestellung['mittwoch'] == 1 ? 'checked' : ''): ''); ?> disabled></td>
					        <td style="text-align:center"> <input type="checkbox" <?php echo ($dauerbestellung ? ($rowDauerbestellung['donnerstag'] == 1 ? 'checked' : ''): ''); ?> disabled></td>
					        <td style="text-align:center"> <input type="checkbox" <?php echo ($dauerbestellung ? ($rowDauerbestellung['freitag'] == 1 ? 'checked' : ''): ''); ?> disabled></td>
					        <td><?php echo ($dauerbestellung ? DateTime::createFromFormat('Y-m-d', $rowDauerbestellung['essenstart'])->format('d.m.Y') : ""); ?></td>
					        <td><?php echo ($dauerbestellung ? DateTime::createFromFormat('Y-m-d', $rowDauerbestellung['essenende'])->format('d.m.Y') : ""); ?></td>
					        <td><?php echo $customFunction->getNameEinrichtung($rowKind['zuordnung_einrichtung']); ?></td>
					        <td><?php echo $customFunction->getNameGruppe($rowKind['zuordnung_gruppe']); ?></td>
					        <td><?php if($rowKind['zuordnung_aktionsgruppe']) { echo $customFunction->getNameAktionsgruppe($rowKind['zuordnung_aktionsgruppe']);} ?></td>
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
<script>var url = "<?php echo Constants::getBaseURL(); ?>"</script>