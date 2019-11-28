<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        if (isset($_POST['datetimepicker1'], $_POST['datetimepicker2'])) {
            $output ='';
            $condition = '';
            $zeitraum_von = $_POST['datetimepicker1'];
            $zeitraum_bis = $_POST['datetimepicker2'];
            
            require_once('../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "SELECT * FROM keis2_rechnung WHERE update_date IS NULL";
            
            if ($zeitraum_von == '' && $zeitraum_bis != '') {
                $condition = " AND zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')";
            } else if ($zeitraum_von != '' && $zeitraum_bis != '') {
                $condition = " AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') AND 
                                zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')";
            } else if ($zeitraum_von != '' && $zeitraum_bis == '') {
                $condition = " AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y')";
            } else {
                $condition = '';
            }
            
            $sql .= $condition;
            $sql .= ' ORDER BY zeitraum_bis DESC, zeitraum_von DESC';
            $result = $conn->query($sql);
            $output .= '
            <table id="table7" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
		    	<thead>
				    <tr>
				    	<th></th>
				    	<th style="text-align:center">
                            <a class="btn btn-success btn-circle custom1" href="../rechnung/generate_rechnungen.php"><span class="fa fa-plus" title="Rechnungen erstellen" aria-hidden="true"></span></a>
                        </th>
                        <th>Beitragszahler</th>
                        <th>Rechnungsart</th>
                        <th>Zeitraum_von</th>
                        <th>Zeitraum_bis</th>
                        <th>Download</th>
			    	</tr>
		  		</thead>
		  		<tbody>
            ';
            
            if ($result->num_rows > 0) {
                require_once('../utilities/functions.php');
                setlocale(LC_TIME, 'de');
                $customFunction = new CustomFunctions();
                while($row = $result->fetch_assoc()) {
                    if ($row['but_id'] == null) {
                        $rechnungsart = 'Normal';
                    } else {
                        $rechnungsart = 'BUT';
                    }
                    if ($row['benutzer_id'] == null) {
                        $beitragszahler = $customFunction->getNameAmt($row['amt_id']);
                    } else {
                        $beitragszahler = $customFunction->getBeitragszahlerName($row['benutzer_id']);
                    }
                    $output .= '
                    <tr id="'.$row['id'].'">
				        <td></td>
				        <td style="text-align:center">';
                    if ($row['abgeschlossen'] == 0) {
                        $output .= '<a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-undo-alt" title="zurücksetzen" aria-hidden="true"></span></a>';
                    }
				        $output .= '
                        </td>
                        <td>'.$beitragszahler.'</td>
                        <td>'.$rechnungsart.'</td>
                        <td>'.DateTime::createFromFormat('Y-m-d', $row['zeitraum_von'])->format('d.m.Y').'</td>
                        <td>'.DateTime::createFromFormat('Y-m-d', $row['zeitraum_bis'])->format('d.m.Y').'</td>
                        <td style="text-align:center"><a href="../rechnung/view_rechnung_by_id.php?id='.$row['id'].'" target="_blank"><span class="fa fa-file-pdf" style="font-size:22px;color:red" title="als PDF herunterladen" aria-hidden="true"></span></a></td>
                    </tr>
                    ';
                }
                
                $output .= '
                </tbody>
            </table>';
            }
            $database->closeConnection();
        } else {
            $output .= 'Formularvariablen sind ungültig oder werden nicht empfangen';
        }
        echo $output;
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>