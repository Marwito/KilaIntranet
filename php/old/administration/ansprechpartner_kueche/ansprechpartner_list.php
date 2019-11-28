<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        require_once('../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $sql = "SELECT * FROM keis2_ansprechpartner_kueche ORDER BY amt";
        $result = $conn->query($sql);
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
						<div class="row mt-3">		
						    <div class="col-sm-12">
								<table id="table7" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>						    		
										    <th scope="col">Vorname</th>
										    <th scope="col">Name</th>
										    <th scope="col">Küche</th>
										    <th scope="col">Telefon</th>
										    <th scope="col">Mobil</th>
										    <th scope="col">Email</th>
										    <th scope="col">Fax</th>
										    <th scope="col">Straße/Hsnr</th>
										    <th scope="col">PLZ</th>
										    <th scope="col">Ort</th>
								    	</tr>
							  		</thead>
							  		<tbody>			
<?php
if ($result->num_rows > 0) {
    require_once('../utilities/functions.php');
    $customFunction = new CustomFunctions();
	while($row = $result->fetch_assoc()) {
?>
										<tr id="<?php echo $row['id']; ?>">
											<td></td>
									        <td> <?php echo $row['vorname']; ?> </td>									        
									        <td> <?php echo $row['name']; ?> </td>
									        <td> <?php echo $customFunction->getNameKueche($row['kueche']); ?> </td>
									        <td> <?php echo $row['telefon']; ?> </td>
									        <td> <?php echo $row['mobil']; ?> </td>
									        <td> <?php echo $row['email']; ?> </td>
									        <td> <?php echo $row['fax']; ?> </td>
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