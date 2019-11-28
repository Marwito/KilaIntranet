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
        if(isset($_POST['aktionsgruppe'])){
            require_once('../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "SELECT * FROM keis2_gruppe WHERE id IN (SELECT id_gruppe FROM 
                    keis2_gruppe_aktionsgruppe WHERE id_aktionsgruppe =
                    '".$conn->real_escape_string($_POST['aktionsgruppe'])."')";
            $result = $conn->query($sql);
            $output .= '
                <table id="table_gruppen" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			    	<thead>
    				    <tr>
    				    	<th></th>
    				    	<th style="text-align:center">
                                <a class="btn btn-success btn-circle custom1" id="add_gruppe" data-target="#choice_gruppen_dialog" data-toggle="modal" href="#"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
                            </th>
    					    <th>Name</th>
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
					        	<a class="btn btn-danger btn-circle custom3" href="#" id="btn_remove_gruppe"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
    				        </td>
                            <td>'.$row['name'].'</td>
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