<?php
date_default_timezone_set('Europe/Berlin');
setlocale(LC_TIME, 'de');
if(isset($_POST['start'], $_POST['ende'], $_POST['einrichtung'], $_POST['gruppe'], $_POST['aktionsgruppe'], $_POST['grund'])) {
    require_once('../db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $date = strtotime($_POST['start']);
    $enddate = strtotime($_POST['ende']) + 1;
    $sqlKinder = "SELECT id FROM keis2_kind WHERE zuordnung_einrichtung=".$conn->real_escape_string($_POST['einrichtung']);
    require_once('../feiertage-api-connector.php');
    $connector = LPLib_Feiertage_Connector::getInstance();
    if($_POST['gruppe'] != -1) {
        $sqlKinder .= " AND zuordnung_gruppe=".$conn->real_escape_string($_POST['gruppe']);
    }
    if($_POST['aktionsgruppe'] != -1) {
        $sqlKinder .= " AND zuordnung_aktionsgruppe=".$conn->real_escape_string($_POST['aktionsgruppe']);
    }
    $resultKinder = $conn->query($sqlKinder);
    if($resultKinder->num_rows > 0) {
        while($rowKind = $resultKinder->fetch_assoc()) {
            $sqlCheckBestellung = "SELECT * FROM keis2_bestellung WHERE kind = '".$rowKind['id']."' AND update_date IS NULL AND datum = '";
            $sqlCheckAbbestellung = "SELECT * FROM keis2_abbestellung WHERE kind = '".$rowKind['id']."' AND update_date IS NULL AND datum = '";
            $date = strtotime($_POST['start']);
                while($date < $enddate) {
                    $day = date("D", $date);
                    $weekday = strftime("%A", $date);
                    if($day != 'Sat' && $day != 'Sun' && !$connector->isFeiertagInLand(date('Y-m-d',$date), LPLib_Feiertage_Connector::LAND_BAYERN)) {
                        $sqlDauerbestellung = "SELECT * FROM keis2_dauerbestellung WHERE kind = '".$conn->real_escape_string($rowKind['id'])."' AND DATEDIFF(essenende,'".$conn->real_escape_string(date('Y-m-d',$date))."')>=0
                                                                                                                                                AND DATEDIFF(essenstart,'".$conn->real_escape_string(date('Y-m-d',$date))."')<=0
                                                                                                                                                AND (DATEDIFF(insert_date,'".$conn->real_escape_string(date('Y-m-d',$date))."')<=0
                                                                                                                                                     OR insert_date IS NULL)";
                        $resultDauerbestellung = $conn->query($sqlDauerbestellung);
                        $abbestellung = array();
                        if($resultDauerbestellung->num_rows > 1) {
                            throw new Exception("Dauerbestellung ist nicht eindeutig!");
                        } else if($resultDauerbestellung->num_rows == 1) {
                            $rowDauerbestellung = $resultDauerbestellung->fetch_assoc();
                            $abbestellung['Montag'] = $rowDauerbestellung['montag'];
                            $abbestellung['Dienstag'] = $rowDauerbestellung['dienstag'];
                            $abbestellung['Mittwoch'] = $rowDauerbestellung['mittwoch'];
                            $abbestellung['Donnerstag'] = $rowDauerbestellung['donnerstag'];
                            $abbestellung['Freitag'] = $rowDauerbestellung['freitag'];
                            $essenstart = strtotime($rowDauerbestellung['essenstart']);
                            $essenende = strtotime($rowDauerbestellung['essenende']);
                        } else {
                            $abbestellung['Montag'] = 0;
                            $abbestellung['Dienstag'] = 0;
                            $abbestellung['Mittwoch'] = 0;
                            $abbestellung['Donnerstag'] = 0;
                            $abbestellung['Freitag'] = 0;
                            $essenstart = 0;
                            $essenende = PHP_INT_MAX;
                        }
                        if($abbestellung[$weekday] && $date >= $essenstart && $date <= $essenende+1) {
                            $resultCheck = $conn->query($sqlCheckAbbestellung.date("Y-m-d", $date)."';");
                            if($resultCheck->num_rows == 0) {
                                $sqlUpdate = "INSERT INTO keis2_abbestellung (kind, datum, essenkategorie, grund, insert_date)
                                              VALUES ('".$conn->real_escape_string($rowKind['id'])."',
                                                      '".$conn->real_escape_string(date("Y-m-d", $date))."',
                                                      '".$conn->real_escape_string($rowDauerbestellung['essenkategorie'])."',
                                                      '".$conn->real_escape_string($_POST['grund'])."',
                                                      NOW())";
                                if ($conn->query($sqlUpdate)=== FALSE) {
                                    throw new Exception("Die Essen konnten aufgrund eines Datenbankfehlers nicht abbestellt werden: ".$conn->error);
                                }
                            }
                        } else {
                            $resultCheck = $conn->query($sqlCheckBestellung.date("Y-m-d", $date)."';");
                            if($resultCheck->num_rows > 0) {
                                $sqlInsert = "UPDATE keis2_bestellung SET grund=
                                              '".$conn->real_escape_string($_POST['grund'])."', update_date=
                                              NOW()
                                              WHERE kind = '".$conn->real_escape_string($rowKind['id'])."' AND
                                              update_date IS NULL AND
                                              datum = '".$conn->real_escape_string(date("Y-m-d", $date))."'";
                                if ($conn->query($sqlInsert)=== FALSE) {
                                    throw new Exception("Die Essen konnten aufgrune eines Datenbankfehlers nicht abbestellt werden: ".$conn->error);
                                }
                            }
                        }
                    }
                $date = $date + 86400;   //86400 sekunden = 1 tag
                }
            }
        }
    $database->closeConnection();
    echo "Die Essen wurden erfolgreich abbestellt.";
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}
?>