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
        if(isset($_POST['id'])){
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "DELETE FROM keis2_ansprechpartner_amt WHERE
            id = ".$conn->real_escape_string($_POST['id'])."";
            if ($conn->query($sql)=== TRUE) {
                echo "Der Ansprechpartner wurde erfolgreich entfernt";
            } else {
                echo "Beim Löschen dieses Ansprechpartners ist ein Fehler aufgetreten : " . $conn->error;
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