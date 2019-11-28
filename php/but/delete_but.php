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
        try {
            if(isset($_POST['id'])){
                require_once('../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                
                // Überprüfung, ob der BUT in einer Rechnung verwendet wird
                $sql = "SELECT COUNT(*) as cnt from keis2_rechnung WHERE but_id = '".$conn->real_escape_string($_POST['id'])."'";
                
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    
                    $row = $result->fetch_assoc();
                    if ($row['cnt'] > 0) {
                        throw new Exception("Der BUT kann nicht gelöscht werden, da dieser in einer Rechnung verwendet wird!");
                    }
                } else {
                    throw new Exception("Der BUT konnte nicht geladen werden!");
                }
                //*********************************************************************************************************************
                
                $sql = "DELETE FROM keis2_but WHERE
                id = ".$conn->real_escape_string($_POST['id'])."";
                
                if ($conn->query($sql)=== TRUE) {
                    echo "Der BUT wurde erfolgreich entfernt";
                } else {
                    throw new Exception($conn->error);
                }
                
                $database->closeConnection();
            } else {
                echo 'Formularvariable ist ungültig oder wird nicht empfangen';
            }
        } catch (Exception $ex) {
            echo json_encode(array(
                'error' => array(
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ),
            ));
            exit();
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>