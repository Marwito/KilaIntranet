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
        if(isset($_POST['input_select1'], $_POST['aktionsgruppe'])){
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "INSERT INTO keis2_gruppe_aktionsgruppe (id_gruppe,
                    id_aktionsgruppe, insert_date)
            		VALUES (".$conn->real_escape_string($_POST['input_select1']).",
            		".$conn->real_escape_string($_POST['aktionsgruppe']).",
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