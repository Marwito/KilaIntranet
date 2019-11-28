<?php

require_once('../utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT keis2_but.id as butid, keis2_but.debitorennummer, keis2_kind.vorname as kind_vorname, keis2_kind.name as kind_name
        FROM keis2_but, keis2_kind
        WHERE keis2_but.kind = keis2_kind.id";
$result = $conn->query($sql);
?>
						<div class="row mt-3">		
						    <div class="col-sm-12">
								<table id="table_but" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>
									    	<th scope="col" style="text-align:center">
									    		<a class="btn btn-success btn-circle custom1" href="add_but.php"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
								    		</th>								    		
										    <th scope="col">Debitorennummer</th>
										    <th scope="col">Vorname</th>
										    <th scope="col">Name</th>
										    <th scope="col">ID</th>
								    	</tr>
							  		</thead>
							  		<tbody>			
<?php
if ($result->num_rows > 0) {
    require_once('../utilities/functions.php');
    $customFunction = new CustomFunctions();
	while($row = $result->fetch_assoc()) {
?>
										<tr id="<?php echo $row['butid']; ?>">
											<td></td>
									        <td style="text-align:center">
									        	<a href="edit_but.php?id=<?php echo $row['butid'];?>" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a>
									        	<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
									        </td>
									        <td> <?php echo $row['debitorennummer']; ?> </td>
									        <td> <?php echo $row['kind_vorname']; ?> </td>	
									        <td> <?php echo $row['kind_name']; ?> </td>
									        <td> <?php echo $row['butid']; ?> </td>								        
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