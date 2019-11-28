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
        $sql = "SELECT * FROM keis2_ansprechpartner";
        $result = $conn->query($sql);
        $output .= '
            <div class="col-sm-12 table-responsive">
                <table id="table_select_ansprechpartner" class="table table-bordered dt-responsive hover" width="100%">
    		    	<thead>
    				    <tr>
                            <th scope="col">Vorname</th>
    					    <th scope="col">Name</th>
                            <th scope="col">Rechnung</th>
    					    <th scope="col">Telefonnummer</th>
    					    <th scope="col">Mobil</th>
    					    <th scope="col">Email</th>
    					    <th scope="col">Fax</th>
    					    <th scope="col">Straße/Hsnr</th>
    					    <th scope="col">PLZ</th>
    					    <th scope="col">Ort</th>
    			    	</tr>
    		  		</thead>
    		  		<tbody>
            ';
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if ($row['rechnung'] == 1) {
                        $rechnung = 'Ja';
                    } else {
                        $rechnung = 'Nein';
                    }
                    $output .= '
                        <tr id="'.$row['id'].'">
                            <td>'.$row['vorname'].'</td>
                            <td>'.$row['name'].'</td>
                            <td>'.$rechnung.'</td>
    				        <td>'.$row['telefonnummer'].'</td>
    				        <td>'.$row['mobil'].'</td>
    				        <td>'.$row['email'].'</td>
    				        <td>'.$row['fax'].'</td>
    				        <td>'.$row['strasse'].'</td>
    				        <td>'.$row['plz'].'</td>
    				        <td>'.$row['ort'].'</td>
                        </tr>
                ';
                }
            }
            
            $output .= '
                    </tbody>
                </table>
            </div>';
            
            $database->closeConnection();

        echo $output;
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>