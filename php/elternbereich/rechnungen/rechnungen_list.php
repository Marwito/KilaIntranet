<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if ($session->checkSessionVariables('username', 'usergroup')) {
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isEltern($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        require_once('../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $sqlLetzte = "SELECT id, zeitraum_von, zeitraum_bis FROM keis2_rechnung WHERE 
                        update_date IS NULL AND benutzer_id= 
                        (SELECT id FROM keis2_benutzer WHERE benutzername = 
                        '".$conn->real_escape_string($_SESSION['username'])."')
                        ORDER BY zeitraum_bis DESC, zeitraum_von DESC LIMIT 1";
        $resultLetzte = $conn->query($sqlLetzte);
        $rowLetzte = $resultLetzte->fetch_assoc();
        $sql = "SELECT id, zeitraum_von, zeitraum_bis FROM keis2_rechnung
                WHERE update_date IS NULL AND benutzer_id=
                (SELECT id FROM keis2_benutzer WHERE benutzername =
                '".$conn->real_escape_string($_SESSION['username'])."') 
                ORDER BY zeitraum_bis DESC, zeitraum_von DESC";
        $result = $conn->query($sql);
        setlocale(LC_TIME, 'de');
        $rechnungsLink = "<a href='../rechnung/view_rechnung_by_id.php?id=". $rowLetzte['id']."' target='_blank'>hier</a>";
?>
						<div><h5>Ihre aktuelle Rechnung für den Zeitraum : <?php echo DateTime::createFromFormat('Y-m-d', $rowLetzte['zeitraum_von'])->format('d.m.Y')." - ".DateTime::createFromFormat('Y-m-d', $rowLetzte['zeitraum_bis'])->format('d.m.Y').": ".$rechnungsLink;?></h5></div>
						<div class="row mt-3">
						    <div class="col-sm-12">
						    	<table id="tableRechnung" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
							    	<thead>
									    <tr>
									    	<th></th>
										    <th scope="col">Zeitraum_von</th>
										    <th scope="col">Zeitraum_bis</th>
										    <th scope="col">Rechnung</th>
								    	</tr>
							  		</thead>
							  		<tbody>			
<?php
        if ($result->num_rows > 0) {
        	while($row = $result->fetch_assoc()) {
?>
										<tr id="<?php echo $row['id']; ?>">
											<td></td>
									        <td> <?php echo DateTime::createFromFormat('Y-m-d', $row['zeitraum_von'])->format('d.m.Y'); ?> </td>
									        <td> <?php echo DateTime::createFromFormat('Y-m-d', $row['zeitraum_bis'])->format('d.m.Y'); ?> </td>
									        <td style="text-align:center"><a href="../rechnung/view_rechnung_by_id.php?id=<?php echo $row['id'];?>" target="_blank"><span class="fa fa-file-pdf" style="font-size:22px;color:red" title="als PDF herunterladen" aria-hidden="true"></span></a></td>
			      						</tr>
<?php 
            }
        }
        $database->closeConnection();
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>
									</tbody>
								</table>
							</div>
						</div>