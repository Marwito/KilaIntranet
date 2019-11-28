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
                $sql = "DELETE FROM keis2_kueche WHERE
                id = ".$conn->real_escape_string($_POST['id'])."";
                if ($conn->query($sql)=== TRUE) {

                    // Bereinigen Ansprechpartner
                    $sql_update_ansprechpartner = "UPDATE keis2_ansprechpartner set kueche_id = null where kueche_id = '".$conn->real_escape_string($_POST['id'])."'";
                    if ($conn->query($sql_update_ansprechpartner) === FALSE) {
                        throw new Exception($conn->error);
                    }
                    
                    // Bereinigen Einrichtungen_Amt
                    $sql_delete_einrichtungen_amt = "DELETE FROM keis2_einrichtung_kueche where kueche_id = '".$conn->real_escape_string($_POST['id'])."'";
                    if ($conn->query($sql_delete_einrichtungen_amt) === FALSE) {
                        throw new Exception($conn->error);
                    }
                    
                } else {
                    throw new Exception($conn->error);
                }
                $database->closeConnection();
                echo "Die Küche wurde erfolgreich entfernt!";
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