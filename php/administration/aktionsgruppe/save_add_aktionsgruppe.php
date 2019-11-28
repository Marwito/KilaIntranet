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
            if (isset($_POST['input_text1'])) {
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $sql = "INSERT INTO keis2_aktionsgruppe (bezeichnung, insert_date)
                        VALUES (
                        '".$conn->real_escape_string($_POST['input_text1'])."',
                        NOW())";
                
                // Speichern
                if ($conn->query($sql) === FALSE) {
                    throw new Exception($conn->error);
                }

                // Preiskategorie-ID
                $aktionsgruppe_id = $conn->insert_id;

                // Verarbeitung Zuordnung Gruppe - Preiskategorie
                if (isset($_POST['gruppen'])) {
                    $gruppen_array = explode(",", $_POST['gruppen']);

                    foreach ($gruppen_array as &$gruppe_id) {
                        $sql_update_gruppe_aktionsgruppe = "INSERT INTO keis2_gruppe_aktionsgruppe (id_gruppe, id_aktionsgruppe, insert_date)
                                                            VALUES (
                                                            '".$conn->real_escape_string($gruppe_id)."',
                                                            '".$conn->real_escape_string($aktionsgruppe_id)."',
                                                            NOW())";

                        if ($conn->query($sql_update_gruppe_aktionsgruppe) === FALSE) {
                            throw new Exception($conn->error);
                        }
                    }
                }
                $database->closeConnection();
                
                echo ("Die Aktionsgruppe wurde erfolgreich erstellt!");
            } else {
                throw new Exception('Formularvariablen sind ungültig oder werden nicht empfangen');
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