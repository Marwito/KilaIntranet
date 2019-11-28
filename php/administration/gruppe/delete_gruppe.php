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
               
                // Überprüfung, ob die Gruppe mit einem Kind verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_kind WHERE zuordnung_gruppe = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Gruppe kann nicht gelöscht werden, da diese mit einem Kind verknüpft!");
                    }
                } else {
                    throw new Exception("Die Gruppe konnte nicht geladen werden!");
                }
                
                // Überprüfung, ob die Gruppe in einer Aktionsgruppe verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_gruppe_aktionsgruppe WHERE id_gruppe = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Gruppe kann nicht gelöscht werden, da diese in einer Aktionsgruppe verwendet wird!");
                    }
                } else {
                    throw new Exception("Die Gruppe konnte nicht geladen werden!");
                }
                
                // Überprüfung, ob die Gruppe in einer Preiskategorie verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_gruppe_preiskategorie WHERE gruppe_id = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Gruppe kann nicht gelöscht werden, da diese in einer Preisgruppe verwendet wird!");
                    }
                } else {
                    throw new Exception("Die Gruppe konnte nicht geladen werden!");
                }
                //*********************************************************************************************************************
                
                $sql = "DELETE FROM keis2_gruppe WHERE
                id = ".$conn->real_escape_string($_POST['id'])."";
                
                if ($conn->query($sql)=== TRUE) {
                    echo "Die Gruppe wurde erfolgreich entfernt!";
                } else {
                    throw new Exception($conn->error);
                }
                
                $database->closeConnection();
            } else {
                throw new Exception('Formularvariable ist ungültig oder wird nicht empfangen');
            }
        } catch(Exception $ex) {
            echo $ex->getMessage();
            exit();
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>