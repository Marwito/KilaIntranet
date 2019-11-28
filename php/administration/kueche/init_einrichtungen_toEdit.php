<?php
require_once('../../login/session.php');
require_once('../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        $output ='';
        if(isset($_POST['kueche'])){
            require_once('../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();

            $sql = "SELECT keis2_einrichtung.id, keis2_einrichtung.name, keis2_einrichtung.strasse, keis2_einrichtung.plz, keis2_einrichtung.ort 
            FROM keis2_einrichtung, keis2_einrichtung_kueche 
            WHERE keis2_einrichtung.id = keis2_einrichtung_kueche.einrichtung_id
            and keis2_einrichtung_kueche.kueche_id = '".$conn->real_escape_string($_POST['kueche'])."'";

            $result = $conn->query($sql);
            $output .= '
                <table id="table_einrichtungen" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			    	<thead>
    				    <tr>
    				    	<th></th>
    				    	<th style="text-align:center">
                                <a class="btn btn-success btn-circle custom1" id="add_einrichtung" data-target="#choice_einrichtungen_dialog" data-toggle="modal" href="#"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
                            </th>
    					    <th>Name</th>
    					    <th>Straße/Hsnr</th>
    					    <th>PLZ</th>
    					    <th>Ort</th>
                            <th>ID</th>
    			    	</tr>
			  		</thead>
			  		<tbody>
        ';
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $output .= '
                        <tr id="'.$row['id'].'">
    				        <td></td>
    				        <td style="text-align:center">
					        	<a class="btn btn-danger btn-circle custom3" href="#" id="btn_remove_einrichtung"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
    				        </td>
                            <td>'.$row['name'].'</td>
					        <td>'.$row['strasse'].'</td>
					        <td>'.$row['plz'].'</td>
					        <td>'.$row['ort'].'</td>
                            <td>'.$row['id'].'</td>
                        </tr>
                ';
                }
                
                $output .= '
                    </tbody>
                </table>';
            }
            $database->closeConnection();
        } else {
            $output = 'Formularvariablen sind ungültig oder werden nicht empfangen';
        }
        echo $output;
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>