<?php
require_once('../../utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT * FROM keis2_rezept";    //@TODO nur speiseplan der eigenen einrichtung sehen, benutzer braucht einrichtungs attribut
$result = $conn->query($sql);
?>
						<div class="row mt-3">		
						    <div class="col-sm-12">
								<table id="table2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>
									    	<th scope="col" style="text-align:center">
									    		<a class="btn btn-success btn-circle custom1" href="rezept/add_rezept.php"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
								    		</th>
								    		<th scope="col">Name</th>
										    <th scope="col">Zutat</th>
										    <th scope="col">Menge</th>
										    <th scope="col">Essenskategorie</th>
										    <th scope="col">Speisenart</th>
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
									        	<a href="rezept/edit_rezept.php?id=<?php echo $row['id'];?>" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a>
									        	<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
									        </td>
									        <td> <?php echo $row['name']; ?> </td>
									        <td> <?php echo $row['zutat']; ?> </td>
									        <td> <?php echo $row['menge']?> </td>
									        <td> <?php echo $row['essenkategorie']?> </td>
									        <td> <?php echo $row['speisenart']?> </td>
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