<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        require_once('../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $sql = "SELECT * FROM keis2_amt";
        $result = $conn->query($sql);
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
						<div class="row mt-3">		
						    <div class="col-sm-12">
								<table id="table4" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>
									    	<th scope="col" style="text-align:center">
									    		<a class="btn btn-success btn-circle custom1" href="amt/add_amt.php"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
								    		</th>								    		
										    <th scope="col">Name</th>
										    <th scope="col">Straße/Hsnr</th>
										    <th scope="col">PLZ</th>
										    <th scope="col">Ort</th>
								    	</tr>
							  		</thead>
							  		<tbody>			
<?php
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
?>
										<tr id="<?php echo $row['id']; ?>">
											<td></td>
									        <td style="text-align:center">
									        	<a href="amt/edit_amt.php?id=<?php echo $row['id'];?>" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a>
									        	<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
									        </td>
									        <td> <?php echo $row['name']; ?> </td>									        
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