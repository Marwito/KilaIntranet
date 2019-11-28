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
        if(isset($_POST['kueche'])){
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "SELECT * FROM keis2_ansprechpartner_kueche WHERE kueche =
            '".$conn->real_escape_string($_POST['kueche'])."'";
            $result = $conn->query($sql);
            $output .= '
                <table id="table1" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			    	<thead>
    				    <tr>
    				    	<th></th>
    				    	<th style="text-align:center">
                                <a class="btn btn-success btn-circle custom1" href="../ansprechpartner_kueche/add_ansprechpartner.php?kueche='.$_POST['kueche'].'"><span class="fa fa-plus" title="hinzufügen" aria-hidden="true"></span></a>
                            <th>Vorname</th>
    					    <th>Name</th>
    					    <th>Telefon</th>
    					    <th>Mobil</th>
    					    <th>Email</th>
    					    <th>Fax</th>
    					    <th>Straße/Hsnr</th>
    					    <th>PLZ</th>
    					    <th>Ort</th>
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
                                <a href="../ansprechpartner_kueche/edit_ansprechpartner.php?id='.$row['id'].'" class="btn btn-warning btn-circle custom2"><span class="fa fa-pencil-alt" style="color:white" title="bearbeiten" aria-hidden="true"></span></a>
					        	<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-trash-alt" title="löschen" aria-hidden="true"></span></a>
    				        </td>
                            <td>'.$row['vorname'].'</td>
					        <td>'.$row['name'].'</td>
					        <td>'.$row['telefon'].'</td>
					        <td>'.$row['mobil'].'</td>
					        <td>'.$row['email'].'</td>
					        <td>'.$row['fax'].'</td>
					        <td>'.$row['strasse'].'</td>
					        <td>'.$row['plz'].'</td>
					        <td>'.$row['ort'].'</td>
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