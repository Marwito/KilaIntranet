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
        try {
            if (isset($_POST['monat'], $_POST['jahr'], $_POST['endmonat'], $_POST['endjahr'])) {
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $firstDay = '1' . '.' . $_POST['monat'] . '.' . $_POST['jahr'];
                $daysNumber = cal_days_in_month(CAL_GREGORIAN, $_POST['endmonat'], $_POST['endjahr']);
                $lastDay = $daysNumber . '.' . $_POST['endmonat'] . '.' . $_POST['endjahr'];
                
                // reset records in the rechnungspositionen table
                $sql = "UPDATE keis2_rechnungspositionen SET update_date= NOW()
                 WHERE id_rechnung IN (SELECT id FROM keis2_rechnung
                 WHERE zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($firstDay)."', '%e.%c.%Y')
                 AND zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($lastDay)."', '%e.%c.%Y')
                 AND update_date IS NULL
                 AND abgeschlossen = 0)";
                
                if ($conn->query($sql)=== TRUE) {
                    echo "Die Rechnungen wurden erfolgreich zurückgesetzt".$firstDay.$lastDay;
                } else {
                    throw new Exception($conn->error);
                }
                // reset records in the rechnung table
                $sql = "UPDATE keis2_rechnung SET update_date = NOW()
                        WHERE zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($firstDay)."', '%e.%c.%Y')
                        AND zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($lastDay)."', '%e.%c.%Y')
                        AND update_date IS NULL
                        AND abgeschlossen = 0";
                
                if ($conn->query($sql)=== TRUE) {
                    echo "Die Rechnungspositionen wurden erfolgreich zurückgesetzt";
                } else {
                    throw new Exception('Formularvariable ist ungültig oder wird nicht empfangen: '. $conn->error);
                }
                $database->closeConnection();
            } else {
                throw new Exception('Formularvariable ist ungültig oder wird nicht empfangen');
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>