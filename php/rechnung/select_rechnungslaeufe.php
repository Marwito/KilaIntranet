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
        $output ='';
        require_once('../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $sql = "SELECT abgeschlossen, MONTH(zeitraum_von) AS month, YEAR(zeitraum_von) AS year, MONTH(zeitraum_bis) AS endmonth, YEAR(zeitraum_bis) AS endyear
                FROM keis2_rechnung 
                WHERE update_date IS NULL
                GROUP BY year, month, endyear, endmonth ORDER BY year DESC, month DESC";
        $result = $conn->query($sql);
        $output .= '
        <table id="table9" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
	    	<thead>
			    <tr>
			    	<th></th>
                    <th></th>
                    <th>Startmonat</th>
                    <th>Startjahr</th>
                    <th>Endmonat</th>
                    <th>Endjahr</th>
		    	</tr>
	  		</thead>
	  		<tbody>
        ';
        
        if ($result->num_rows > 0) {
            setlocale(LC_TIME, 'de');
            while($row = $result->fetch_assoc()) {
                //$monat = DateTime::createFromFormat('Y-m-d', $row['date'])->format('n');
                //$jahr = DateTime::createFromFormat('Y-m-d', $row['date'])->format('Y');
                $monat = $row['month'];
                $jahr = $row['year'];
                $endmonat = $row['endmonth'];
                $endjahr = $row['endyear'];
                $output .= '
                <tr>
			        <td></td>
                    <td style="text-align:center">';
                if ($row['abgeschlossen'] == 0) {
                    $output .= '
                        <a class="btn btn-danger btn-circle custom3" href="#"><span class="fa fa-undo-alt" title="Rechnungslaug zurücksetzen" aria-hidden="true"></span></a>
                        <a class="btn btn-info btn-circle custom3" href="#"><span class="fa fa-dot-circle" title="Rechnungslauf abschließen" aria-hidden="true"></span></a>
                    ';
                }
                $output .= '
                    </td>
                    <td>'.utf8_encode(strftime('%B', mktime(0, 0, 0, $monat, 10))).'</td>
                    <td>'.$jahr.'</td>
                    <td>'.utf8_encode(strftime('%B', mktime(0, 0, 0, $endmonat, 10))).'</td>
                    <td>'.$endjahr.'</td>
                </tr>
                ';
            }
            
        }
        $output .= '
        </tbody>
        </table>
        ';
        $database->closeConnection();
        echo $output;
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>