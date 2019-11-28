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
        if(isset($_POST['id'])){
            require_once('../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            
            // fill the update_date field in the Rechnung table out
            $sql = "UPDATE keis2_rechnung SET update_date = NOW()
                    WHERE id=".$conn->real_escape_string($_POST['id'])."";
            
            if ($conn->query($sql)=== TRUE) {
                echo "Die Rechnung wurde erfolgreich zurückgesetzt";
            } else {
                echo "Beim Zurücksetzen der Rechnung ist ein Fehler aufgetreten : " . $conn->error;
            }

            // fill the update_date field in the Rechnungspositionen table out
            $sql = "UPDATE keis2_rechnungspositionen SET update_date= NOW()
                    WHERE id_rechnung= ".$conn->real_escape_string($_POST['id'])."";
            
            if ($conn->query($sql)=== TRUE) {
                echo "Die Rechnungspositionen wurden erfolgreich zurückgesetzt";
            } else {
                echo "Beim Zurücksetzen der Rechnungspositionen ist ein Fehler aufgetreten : " . $conn->error;
            }
            $database->closeConnection();
        } else {
            echo 'Formularvariable ist ungültig oder wird nicht empfangen';
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>