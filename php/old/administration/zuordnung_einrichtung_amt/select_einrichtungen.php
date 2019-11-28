<?php
require_once('../../../login/session.php');
require_once('../../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../../../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        $output ='';
        if(isset($_POST['amt']) || isset($_POST['kueche'])) {
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            if (isset($_POST['amt'])) {
                $sql = "SELECT id, einrichtung_id FROM keis2_einrichtung_amt WHERE amt_id =
                        ".$conn->real_escape_string($_POST['amt'])."";
                $add_path = '../zuordnung_einrichtung_amt/add_einrichtung_to_amt.php?amt='.$_POST['amt'];
            } else {
                $sql = "SELECT id, einrichtung_id FROM keis2_einrichtung_kueche WHERE kueche_id =
                        ".$conn->real_escape_string($_POST['kueche'])."";
                $add_path = '../zuordnung_einrichtung_kueche/add_einrichtung_to_kueche.php?kueche='.$_POST['kueche'];
            }
            
            $result = $conn->query($sql);
            $output .= '
                <table id="table2" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			    	<thead>
    				    <tr>
    				    	<th></th>
    				    	<th style="text-align:center">
                                <a class="btn btn-success btn-circle custom1" href="'.$add_path.'"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
                            <th>Einrichtung</th>
    			    	</tr>
			  		</thead>
			  		<tbody>
        ';
            
            if ($result->num_rows > 0) {
                require_once('../../../utilities/functions.php');
                $customFunction = new CustomFunctions();
                while($row = $result->fetch_assoc()) {
                    $output .= '
                        <tr id="'.$row['id'].'">
    				        <td></td>
    				        <td style="text-align:center">
					        	<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
    				        </td>
                            <td>'.$customFunction->getNameEinrichtung($row['einrichtung_id']).'</td>
                        </tr>
                ';
                }
                
                $output .= '
                    </tbody>
                </table>';
            }
            $database->closeConnection();
        } else {
            $output = 'Formularvariable ist ungültig oder wird nicht empfangen';
        }
        echo $output;
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>