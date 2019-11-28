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
            $sql = "SELECT * FROM keis2_aktionsgruppe";
        } else {
            
            $sql = "SELECT keis2_gruppe_aktionsgruppe.id_aktionsgruppe AS id_aktionsgruppe, keis2_gruppe_aktionsgruppe.id_gruppe AS id_gruppe,
                    keis2_aktionsgruppe.id, keis2_aktionsgruppe.bezeichnung
                    FROM keis2_gruppe_aktionsgruppe
                    LEFT JOIN keis2_aktionsgruppe ON keis2_gruppe_aktionsgruppe.id_aktionsgruppe = keis2_aktionsgruppe.id
                    WHERE keis2_gruppe_aktionsgruppe.id_gruppe = (SELECT id FROM keis2_gruppe WHERE einrichtung =
                    (SELECT einrichtung FROM keis2_benutzer WHERE
                    benutzername = '".$conn->real_escape_string($session->username)."'))";
        }
        $result = $conn->query($sql);
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
						<div class="row mt-3">		
						    <div class="col-sm-12">
								<table id="table6" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>
									    	<?php 
                                            if ($benutzer->isAdmin($session->usergroup)) {
                                            ?>
									    	<th scope="col" style="text-align:center">
									    		<a class="btn btn-success btn-circle custom1" href="aktionsgruppe/add_aktionsgruppe.php"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
								    		</th>
								    		<?php 
                                            }
                                            ?>								    		
										    <th scope="col">Name</th>
										    <th scope="col">Gruppen</th>
								    	</tr>
							  		</thead>
							  		<tbody>			
<?php
if ($result->num_rows > 0) {
    require_once('../utilities/functions.php');
    $customFunction = new CustomFunctions();
    while($row = $result->fetch_assoc()) {
        if ($benutzer->isAdmin($session->usergroup)) {
            $groups = '';
            $gruppen_list = $customFunction->getGruppenByAktionsgruppe($row['id']);
            for ($i = 0; $i < count($gruppen_list); $i++) {
                $groups .= $gruppen_list[$i] . ' | ';
            }
            $groups = rtrim($groups, ' | ');
        } else {
            $groups = $customFunction->getNameGruppe($row['id_gruppe']);
        }
        
?>
										<tr id="<?php echo $row['id']; ?>">
											<td></td>
											<?php 
                                            if ($benutzer->isAdmin($session->usergroup)) {
                                            ?>
									        <td style="text-align:center">
									        	<a href="aktionsgruppe/edit_aktionsgruppe.php?id=<?php echo $row['id'];?>" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a>
									        	<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
									        </td>
									        <?php 
                                            }
                                            ?>
									        <td> <?php echo $row['bezeichnung']; ?> </td>
									        <td> <?php echo $groups; ?> </td>
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