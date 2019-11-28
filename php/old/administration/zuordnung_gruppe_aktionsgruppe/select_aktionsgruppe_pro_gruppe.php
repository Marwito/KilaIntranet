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
        if(isset($_POST['aktionsgruppe'])) {
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "SELECT id, id_gruppe FROM keis2_gruppe_aktionsgruppe
                    WHERE id_aktionsgruppe =
                    ".$conn->real_escape_string($_POST['aktionsgruppe'])."";
            
            $result = $conn->query($sql);
            $output .= '
                <table id="table1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			    	<thead>
    				    <tr>
    				    	<th></th>
    				    	<th style="text-align:center">
                                <a class="btn btn-success btn-circle custom1" href="../zuordnung_gruppe_aktionsgruppe/add_aktionsgruppe_to_gruppe.php?aktionsgruppe='.$_POST['aktionsgruppe'].'"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
                            <th>Gruppe</th>
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
                            <td>'.$customFunction->getNameGruppe($row['id_gruppe']).'</td>
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