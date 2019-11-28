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
                
                // Überprüfung, ob Ansprechpartner in einem Amt verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_ansprechpartner WHERE id = '".$conn->real_escape_string($_POST['id'])."' and amt_id is not NULL";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Der Ansprechpartner kann nicht gelöscht werden, weil dieser mit einem Amt verknüpft ist!");
                    }
                } else {
                    throw new Exception("Der Ansprechpartner konnte nicht geladen werden!");
                }
                
                // Überprüfung, ob Ansprechpartner in einer Einrichtung  verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_ansprechpartner WHERE id = '".$conn->real_escape_string($_POST['id'])."' and einrichtung_id is not NULL";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Der Ansprechpartner kann nicht gelöscht werden, weil dieser mit einer Einrichtung verknüpft ist!");
                    }
                } else {
                    throw new Exception("Der Ansprechpartner konnte nicht geladen werden!");
                }
                
                // Überprüfung, ob Ansprechpartner in einer Küche verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_ansprechpartner WHERE id = '".$conn->real_escape_string($_POST['id'])."' and kueche_id is not NULL";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Der Ansprechpartner kann nicht gelöscht werden, weil dieser mit einer Küche verknüpft ist!");
                    }
                } else {
                    throw new Exception("Der Ansprechpartner konnte nicht geladen werden!");
                }
                //*****************************************************************************************************************************************
                
                $sql = "DELETE FROM keis2_ansprechpartner WHERE
                id = ".$conn->real_escape_string($_POST['id'])."";
                
                if ($conn->query($sql)=== FALSE) {
                    throw new Exception($conn->error);
                }
                
                $database->closeConnection();
                echo "Der Ansprechpartner wurde erfolgreich entfernt!";
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