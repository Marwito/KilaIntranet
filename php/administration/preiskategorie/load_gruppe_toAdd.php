<?php
require_once('../../login/session.php');
require_once('../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')) {
    require_once('../../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        try {
            if(isset($_POST['id'])) {
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $sql = "SELECT * FROM keis2_gruppe WHERE id ='".$conn->real_escape_string($_POST['id'])."'";
    
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
    
                    $row = $result->fetch_assoc();
                    $database->closeConnection();
    
                    echo json_encode($row);
                } else {
                    throw new Exception("Die Gruppe konnte nicht geladen werden!");
                }
            } else {
                throw new Exception('Formularvariable ist ungültig oder wird nicht empfangen');
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
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>