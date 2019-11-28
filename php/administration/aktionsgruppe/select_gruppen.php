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
        $sql = "SELECT * FROM keis2_gruppe";
        $result = $conn->query($sql);
        $output .= '
                <table id="table_select_gruppen" class="table table-bordered hover nowrap" style="width:100%">
    		    	<thead>
    				    <tr>
						    <th scope="col">Name</th>
    			    	</tr>
    		  		</thead>
    		  		<tbody>
            ';
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $output .= '
                        <tr id="'.$row['id'].'">
                            <td>'.$row['name'].'</td>
                        </tr>
                ';
            }
            
            $output .= '
                    </tbody>
                </table>';
        }
        $database->closeConnection();
        
        echo $output;
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>