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
                
                // Überprüfung, ob die Aktionsgruppe mit einem Kind verknüpft ist
                $sql = "SELECT COUNT(*) as cnt from keis2_kind WHERE zuordnung_aktionsgruppe = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Aktionsgruppe kann nicht gelöscht werden, da diese mit einem Kind verknüpft ist!");
                    }
                } else {
                    throw new Exception("Die Aktionsgruppe konnte nicht geladen werden!");
                }
                //*********************************************************************************************************************
                
                $sql = "DELETE FROM keis2_aktionsgruppe WHERE
                id = ".$conn->real_escape_string($_POST['id'])."";
                
                if ($conn->query($sql)=== TRUE) {
                    
                    // Delete any related records in the table keis2_gruppe_aktionsgruppe
                    $sql_delete_zuordnung_gruppe_aktionsgruppe = "DELETE FROM keis2_gruppe_aktionsgruppe where aktionsgruppe_id =
                                                                    ".$conn->real_escape_string($_POST['id'])."";
                    if ($conn->query($sql_delete_zuordnung_gruppe_aktionsgruppe) === FALSE) {
                        throw new Exception($conn->error);
                    }
                } else {
                    throw new Exception($conn->error);
                }
                
                $database->closeConnection();
                echo "Die Aktionsgruppe wurde erfolgreich entfernt";
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