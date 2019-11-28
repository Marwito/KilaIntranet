<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        require_once('../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        if ($benutzer->isAdmin($session->usergroup)) {
            $sql = "SELECT * FROM keis2_einrichtung";
        } else {
            $sql = "SELECT * FROM keis2_einrichtung WHERE id=(SELECT 
                    einrichtung FROM keis2_benutzer WHERE benutzername =
                    '".$conn->real_escape_string($session->username)."')";
        }
        $result = $conn->query($sql);
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
						<div class="row mt-3">		
						    <div class="col-sm-12">
								<table id="table1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>
									    	<?php 
                                            if ($benutzer->isAdmin($session->usergroup)) {
                                            ?>
									    	<th scope="col" style="text-align:center">
									    		<a class="btn btn-success btn-circle custom1" href="einrichtung/add_einrichtung.php"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
								    		</th>
								    		<?php 
                                            }
                                            ?>									    		
										    <th scope="col">Name</th>
										    <th scope="col">Straße/Hsnr</th>
										    <th scope="col">PLZ</th>
										    <th scope="col">Ort</th>
										    <th scope="col">Abmeldefrist</th>
										    <th scope="col">Vortag</th>
								    	</tr>
							  		</thead>
							  		<tbody>			
<?php 
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
										<tr id="<?php echo $row['id']; ?>">
											<td></td>
											<?php 
                                            if ($benutzer->isAdmin($session->usergroup)) {
                                            ?>
									        <td style="text-align:center">
									        	<a href="einrichtung/edit_einrichtung.php?id=<?php echo $row['id'];?>" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a>
									        	<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
                                            
									        </td>
									        <?php 
                                            }
                                            ?>
									        <td> <?php echo $row['name']; ?> </td>									        
									        <td> <?php echo $row['strasse']; ?> </td>
									        <td> <?php echo $row['plz']; ?> </td>
									        <td> <?php echo $row['ort']; ?> </td>
									        <td> <?php echo DateTime::createFromFormat('H:i:s', $row['abmeldefrist'])->format('H:i'); ?> </td>
									        <td> <?php echo $row['vortag']; ?> </td>
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