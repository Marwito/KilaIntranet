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
        require_once('../../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $sql = "SELECT * FROM keis2_einrichtung";
        $result = $conn->query($sql);
        $output .= '
            <div class="col-sm-12">
                <table id="table_select_einrichtungen" class="table table-bordered dt-responsive hover" style="width:100%">
    		    	<thead>
    				    <tr>
						    <th scope="col">Name</th>
						    <th scope="col">Straße/Hsnr</th>
						    <th scope="col">PLZ</th>
						    <th scope="col">Ort</th>
    			    	</tr>
    		  		</thead>
    		  		<tbody>
            ';
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $output .= '
                        <tr id="'.$row['id'].'">
                            <td>'.$row['name'].'</td>
                            <td>'.$row['strasse'].'</td>
    				        <td>'.$row['plz'].'</td>
    				        <td>'.$row['ort'].'</td>
                        </tr>
                ';
            }
        }
        $database->closeConnection();
        
        $output .= '
                    </tbody>
                </table>
            </div>';
        
        echo $output;
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>