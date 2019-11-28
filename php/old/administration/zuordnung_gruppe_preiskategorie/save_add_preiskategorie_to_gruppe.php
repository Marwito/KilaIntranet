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
        if(isset($_POST['input_select0'])){
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "INSERT INTO keis2_gruppe_preiskategorie (gruppe_id,
                    preiskategorie_id, insert_date)
            		VALUES (".$conn->real_escape_string($_POST['input_select0']).",
            		".$conn->real_escape_string($_POST['preiskategorie']).",
                    NOW())";
            if ($conn->query($sql)=== TRUE) {
                echo "Die Zuordnung Gruppe/Preiskategorie wurde erfolgreich erstellt";
            } else {
                echo "Beim Hinzufügen der Zuordnung Gruppe/Preiskategorie ist ein Fehler aufgetreten : " . $conn->error;
            }
            $database->closeConnection();
        } else {
            echo 'Formularvariablen sind ungültig oder werden nicht empfangen';
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>