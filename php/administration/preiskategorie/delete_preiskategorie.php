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
            if(isset($_POST['id'])){
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                
                // Überprüfung, ob die Preiskategorie mit einer Rechnung verknüpft ist
                $sql = "SELECT COUNT(*) as cnt from keis2_rechnungspositionen WHERE preiskategorie = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Preiskategorie kann nicht gelöscht werden, da diese mit einer oder mehreren Rechnungen verknüpft ist!");
                    }
                } else {
                    throw new Exception("Die Preisgruppe konnte nicht geladen werden!");
                }
                //*********************************************************************************************************************
                
                $sql = "DELETE FROM keis2_preiskategorie WHERE
                id = ".$conn->real_escape_string($_POST['id'])."";
                if ($conn->query($sql)=== TRUE) {
                    
                    // Delete any related records in the table keis2_gruppe_preiskategorie
                    $sql_delete_zuordnung_gruppe_preiskategorie = "DELETE FROM keis2_gruppe_preiskategorie where preiskategorie_id =
                                                                    ".$conn->real_escape_string($_POST['id'])."";
                    if ($conn->query($sql_delete_zuordnung_gruppe_preiskategorie) === FALSE) {
                        throw new Exception($conn->error);
                    }
                } else {
                    throw new Exception($conn->error);
                }
                $database->closeConnection();
                echo "Die Preiskategorie wurde erfolgreich entfernt!";
            } else {
                throw new Exception('Formularvariable ist ungültig oder wird nicht empfangen');
            }
        } catch (Exception $ex) {
            $ex->getMessage();
            exit();
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>