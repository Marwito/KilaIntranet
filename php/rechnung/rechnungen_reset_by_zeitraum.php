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
        require_once('../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $output = '';
        $resultArray = array();
        
        if (isset($_POST['zeitraum_von'], $_POST['zeitraum_bis'])) {
            $zeitraum_von = $_POST['zeitraum_von'];
            $zeitraum_bis = $_POST['zeitraum_bis'];
            
            // fill the update_date field in the Rechnungspositionen table out
            $sql = "UPDATE keis2_rechnungspositionen SET update_date= NOW()
                    WHERE id_rechnung IN (SELECT id FROM keis2_rechnung
                    WHERE update_date IS NULL 
                    AND abgeschlossen = 0
                    AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') 
                    AND zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')
                    )";
            
            if ($conn->query($sql)=== TRUE) {
                $output .= "Die Rechnungspositionen wurden erfolgreich zurückgesetzt";
            } else {
                $output .= "Beim Zurücksetzen der Rechnungspositionen ist ein Fehler aufgetreten : " . $conn->error;
            }
            
            // fill the update_date field in the Rechnung table out
            $sql = "SELECT id FROM keis2_rechnung 
                    WHERE update_date IS NULL 
                    AND abgeschlossen = 0
                    AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') 
                    AND zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')";
            
            $result = $conn->query($sql);
            $rechnungenArray = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rechnungenArray[] = $row['id'];
                }
            }
            
            // fill the update_date field in the Rechnung table out
            $sql = "UPDATE keis2_rechnung SET update_date = NOW()
                    WHERE update_date IS NULL 
                    AND abgeschlossen = 0
                    AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') 
                    AND zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')";
            
            if ($conn->query($sql)=== TRUE) {
                $output .= " Alle Rechnungen wurden erfolgreich zurückgesetzt";
            } else {
                $output .= " Beim Zurücksetzen der Rechnungen ist ein Fehler aufgetreten : " . $conn->error;
            }
            $database->closeConnection();  
        } else {
            $output .= 'Formularvariablen sind ungültig oder werden nicht empfangen';
        }
        $resultArray[] = $output;
        $resultArray[] = $rechnungenArray;
        echo json_encode($resultArray);
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>