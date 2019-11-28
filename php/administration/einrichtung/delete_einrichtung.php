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
                
                // Überprüfung, ob die Einrichtung mit einem Kind verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_kind WHERE zuordnung_einrichtung = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Einrichtung kann nicht gelöscht werden, da diese mit einem Kind verknüpft ist!");
                    }
                } else {
                    throw new Exception("Die Gruppe konnte nicht geladen werden!");
                }
                
                // Überprüfung, ob die Gruppe in einem Amt verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_einrichtung_amt WHERE einrichtung_id = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Einrichtung kann nicht gelöscht werden, da diese in einem Amt verwendet wird!");
                    }
                } else {
                    throw new Exception("Die Gruppe konnte nicht geladen werden!");
                }
                
                // Überprüfung, ob die Gruppe in einer Küche verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_einrichtung_kueche WHERE einrichtung_id = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Die Einrichtung kann nicht gelöscht werden, da diese in einer Küche verwendet wird!");
                    }
                } else {
                    throw new Exception("Die Gruppe konnte nicht geladen werden!");
                }
                //*********************************************************************************************************************
                
                $sql = "DELETE FROM keis2_einrichtung WHERE
                id = ".$conn->real_escape_string($_POST['id'])."";
                
                if ($conn->query($sql)=== TRUE) {

                    // Bereinigen Ansprechpartner
                    $sql_update_ansprechpartner = "UPDATE keis2_ansprechpartner set einrichtung_id = null WHERE einrichtung_id = '".$conn->real_escape_string($_POST['id'])."'";
                    if ($conn->query($sql_update_ansprechpartner) === FALSE) {
                        throw new Exception($conn->error);
                    }
                    
                } else {
                    throw new Exception($conn->error);
                }
                
                $database->closeConnection();
                echo "Die Einrichtung wurde erfolgreich entfernt!";
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