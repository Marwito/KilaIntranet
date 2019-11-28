<?php
setlocale(LC_TIME, 'de');
require_once('../../login/session.php');
require_once('../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup || $benutzer->isLeiter($session->usergroup)))) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        if(isset($_POST['tablecount'], $_POST['datum'])) {
            $date = array();
            $date['montag'] = new DateTime($_POST['datum']);
            $date['dienstag'] = new DateTime($_POST['datum']);
            $date['dienstag']->modify("+1 day");
            $date['mittwoch'] = new DateTime($_POST['datum']);
            $date['mittwoch']->modify("+2 days");
            $date['donnerstag'] = new DateTime($_POST['datum']);
            $date['donnerstag']->modify("+3 days");
            $date['freitag'] = new DateTime($_POST['datum']);
            $date['freitag']->modify("+4 days");
            $result = ' <table id="table'.$_POST['tablecount'].'" class="table table-striped table-bordered dt-responsive display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th scope="col" id="vorname'.$_POST['tablecount'].'">Vorname</th>
                                    <th scope="col" id="name'.$_POST['tablecount'].'">Name</th>
                                    <th scope="col" id="monday'.$_POST['tablecount'].'">'.strftime("%a, %d.%m.%Y", $date['montag']->getTimestamp()).'</th>
                                    <th scope="col" id="tuesday'.$_POST['tablecount'].'">'.strftime("%a, %d.%m.%Y", $date['dienstag']->getTimestamp()).'</th>
                                    <th scope="col" id="wednesday'.$_POST['tablecount'].'">'.strftime("%a, %d.%m.%Y", $date['mittwoch']->getTimestamp()).'</th>
                                    <th scope="col" id="thursday'.$_POST['tablecount'].'">'.strftime("%a, %d.%m.%Y", $date['donnerstag']->getTimestamp()).'</th>
                                    <th scope="col" id="friday'.$_POST['tablecount'].'">'.strftime("%a, %d.%m.%Y", $date['freitag']->getTimestamp()).'</th>
                                </tr>
                            </thead>
                            <tbody>';
           
           require_once('../../utilities/db_connection.php');
           $database = new DatabaseConnection();
           $conn = $database->getConn();
           $sqlKind = "SELECT * FROM keis2_kind";
           if($benutzer->isLeiter($session->usergroup)) {
           $sqlKind = $sqlKind." WHERE zuordnung_einrichtung=".$session->einrichtung_kueche;
           }
           $resultKind = $conn->query($sqlKind);
           if ($resultKind->num_rows > 0) {
               while($rowKind = $resultKind->fetch_assoc()) {
                   $result .=  '<tr>
        			  			    <td></td>
        			  				<td>'.$rowKind['vorname'].'</td>
        			  				<td>'.$rowKind['name'].'</td>
        			  				<td>'.getBestellungByDay($rowKind['id'], $date['montag']->format("Y-m-d")).'</td>
        			  				<td>'.getBestellungByDay($rowKind['id'], $date['dienstag']->format("Y-m-d")).'</td>
        			  				<td>'.getBestellungByDay($rowKind['id'], $date['mittwoch']->format("Y-m-d")).'</td>
        			  				<td>'.getBestellungByDay($rowKind['id'], $date['donnerstag']->format("Y-m-d")).'</td>
        			  				<td>'.getBestellungByDay($rowKind['id'], $date['freitag']->format("Y-m-d")).'</td>
        			  			</tr>';
                                                            }
                                          }
           $result .=      '</tbody>
				        </table>';
           echo $result;
           $database->closeConnection();
        } else {
            echo "Formularvariablen nicht gesetzt oder nicht empfangen!";
        }
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}

function getBestellungByDay($id, $datum) {
    setlocale(LC_TIME, 'de');
    require_once('../../utilities/constants.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    require_once('../../utilities/feiertage-api-connector.php');
    $connector = LPLib_Feiertage_Connector::getInstance();
    require_once('../../utilities/functions.php');
    $customFunction = new CustomFunctions();
    $time = strtotime($datum);
    $sqlDauerbestellung = "SELECT * FROM keis2_dauerbestellung WHERE kind = '".$conn->real_escape_string($id)."' AND update_date IS NULL;";
    $resultDauerbestellung = $conn->query($sqlDauerbestellung);
    $sqlBestellung = "SELECT * FROM keis2_bestellung WHERE kind = '".$conn->real_escape_string($id)."' AND update_date IS NULL AND datum = '";
    $sqlAbbestellung = "SELECT * FROM keis2_abbestellung WHERE kind = '".$conn->real_escape_string($id)."' AND update_date IS NULL AND datum = '";
    if(!$connector->isFeiertagInLand(date("Y-m-d", $time), LPLib_Feiertage_Connector::LAND_BAYERN)) {
        $weekday = strtolower(strftime("%A", $time));
        $value = "Fehler bei der Abfrage";
        if($weekday != 'samstag' && $weekday != 'sonntag') {
            
            if($resultDauerbestellung->num_rows == 1) {
                $rowDauerbestellung = $resultDauerbestellung->fetch_assoc();
                $essenstart = strtotime($rowDauerbestellung['essenstart']);
                $essenende = strtotime($rowDauerbestellung['essenende']);
                if($rowDauerbestellung[$weekday] && $time >= $essenstart && $time <= $essenende) {
                    $sql = $sqlAbbestellung.$conn->real_escape_string($datum)."';";
                    $result = $conn->query($sql);
                    if(!$result->num_rows) {
                        $value = "bestellt: ".$customFunction->getNameEssenkategorie($rowDauerbestellung['essenkategorie']);
                    } else {
                        $value = "nicht bestellt";
                    }
                } else {
                    $sql = $sqlBestellung.$conn->real_escape_string($datum)."';";
                    $result = $conn->query($sql);
                    if($result->num_rows) {
                        $row = $result->fetch_assoc();
                        $value = "bestellt: ".$customFunction->getNameEssenkategorie($row['essenkategorie']);
                    } else {
                        $value = "nicht bestellt";
                    }
                }
            } else {
                $sql = $sqlBestellung.$conn->real_escape_string($datum)."';";
                $result = $conn->query($sql);
                if($result->num_rows) {
                    $row = $result->fetch_assoc();
                    $value = "bestellt: ".$customFunction->getNameEssenkategorie($row['essenkategorie']);
                } else {
                    $value = "nicht bestellt";
                }
            }
        }
    } else {
        $value = "Feiertag";
    }
    return $value;
    $database->closeConnection();
}
?>