<?php
date_default_timezone_set('Europe/Berlin');
setlocale(LC_TIME, 'de');
if(isset($_POST['datetimepicker1'], $_POST['datetimepicker2'], $_POST['kind'], $_POST['grund'])){
    require_once('../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $date = strtotime($_POST['datetimepicker1']);
    $enddate = strtotime($_POST['datetimepicker2']) + 1;
    $sqlCheckBestellung = "SELECT * FROM keis2_bestellung WHERE kind = '".$_POST['kind']."' AND update_date IS NULL AND datum = '";
    $sqlCheckAbbestellung = "SELECT * FROM keis2_abbestellung WHERE kind = '".$_POST['kind']."' AND update_date IS NULL AND datum = '";
    require_once('../../utilities/feiertage-api-connector.php');
    $connector = LPLib_Feiertage_Connector::getInstance();
    while($date < $enddate) {
        $day = date("D", $date);
        $weekday = strftime("%A", $date);
        if($day != 'Sat' && $day != 'Sun' && !$connector->isFeiertagInLand(date("Y-m-d", $date), LPLib_Feiertage_Connector::LAND_BAYERN)) {
            $sqlDauerbestellung = "SELECT * FROM keis2_dauerbestellung WHERE kind = '".$conn->real_escape_string($_POST['kind'])."'";
            $resultDauerbestellung = $conn->query($sqlDauerbestellung);
            $abbestellung = array();
            if($resultDauerbestellung->num_rows > 0) {
                $counter = 0;
                $rowDauerbestellung = $resultDauerbestellung->fetch_assoc();
                $counter++;
                while(strtotime($rowDauerbestellung['essenende']) < $date && $counter < $resultDauerbestellung->num_rows) {
                    $rowDauerbestellung = $resultDauerbestellung->fetch_assoc();
                    $counter++;
                }
                $abbestellung['Montag'] = $rowDauerbestellung['montag'];
                $abbestellung['Dienstag'] = $rowDauerbestellung['dienstag'];
                $abbestellung['Mittwoch'] = $rowDauerbestellung['mittwoch'];
                $abbestellung['Donnerstag'] = $rowDauerbestellung['donnerstag'];
                $abbestellung['Freitag'] = $rowDauerbestellung['freitag'];
                $essenstart = strtotime($rowDauerbestellung['essenstart']);
                $essenende = strtotime($rowDauerbestellung['essenende']);
                $dauerbesteller = 1;
            } else {
                $abbestellung['Montag'] = 0;
                $abbestellung['Dienstag'] = 0;
                $abbestellung['Mittwoch'] = 0;
                $abbestellung['Donnerstag'] = 0;
                $abbestellung['Freitag'] = 0;
                $essenstart = 0;
                $essenende = PHP_INT_MAX;
                $dauerbesteller = 0;
            }
            if($dauerbesteller && $abbestellung[$weekday] && $date >= $essenstart && $date <= $essenende+1) {
                $resultCheck = $conn->query($sqlCheckAbbestellung.date("Y-m-d", $date)."';");
                if($resultCheck->num_rows == 0) {
                    $sqlUpdate = "INSERT INTO keis2_abbestellung (kind, datum, essenkategorie, grund, insert_date)
                                 VALUES ('".$conn->real_escape_string($_POST['kind'])."',
                                         '".$conn->real_escape_string(date("Y-m-d", $date))."',
                                         '".$conn->real_escape_string($rowDauerbestellung['essenkategorie'])."',
                                         '".$conn->real_escape_string($_POST['grund'])."',
                                         NOW());";
                    if (!$conn->query($sqlUpdate)=== TRUE) {
                        throw new Exception("Beim Abbestellen der Essen ist ein Datenbankfehler aufgetreten!");
                    }
                }
            } else if(!$dauerbesteller){
                $resultCheck = $conn->query($sqlCheckBestellung.date("Y-m-d", $date)."';");
                if($resultCheck->num_rows > 0) {
                    $sqlInsert = "UPDATE keis2_bestellung SET grund=
                                '".$conn->real_escape_string($_POST['grund'])."', update_date=
                                NOW()
                                WHERE kind = '".$conn->real_escape_string($_POST['kind'])."' AND
                                update_date IS NULL AND
                                datum = '".$conn->real_escape_string(date("Y-m-d", $date))."'";
                    if (!$conn->query($sqlInsert)=== TRUE) {
                        throw new Exception("Beim Abbestellen der Essen ist ein Datenbankfehler aufgetreten!");
                    }
                }
            }
        }
        $date = $date + 86400;   //86400 sekunden = 1 tag
    }
    $database->closeConnection();
    echo "Die Essen wurden erfolgreich abbestellt.";
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}
?>