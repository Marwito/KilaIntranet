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
            if (isset($_POST['aktionsgruppe'], $_POST['input_text1'])) {
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $sql = "UPDATE keis2_aktionsgruppe SET bezeichnung=
            	'".$conn->real_escape_string($_POST['input_text1'])."', 
                update_date= NOW()
                WHERE id=".$conn->real_escape_string($_POST['aktionsgruppe'])."";

                if ($conn->query($sql) === FALSE) {
                    throw new Exception($conn->error);
                }

                // Verarbeitung der Gruppen
                if (isset($_POST['gruppen'])) {
                    
                    // 1. Bereinigung
                    $sql_remove_gruppe_aktionsgruppe = "DELETE FROM keis2_gruppe_aktionsgruppe WHERE id_aktionsgruppe = '".$_POST['aktionsgruppe']."'";
                    if ($conn->query($sql_remove_gruppe_aktionsgruppe) === FALSE) {
                        throw new Exception($conn->error);
                    }
                    
                    // 2. Update
                    $gruppen_array = explode(",", $_POST['gruppen']);
                    
                    foreach ($gruppen_array as &$gruppen_id) {
                        $sql_insert_gruppe_aktionsgruppe = "INSERT INTO keis2_gruppe_aktionsgruppe (id_aktionsgruppe, id_gruppe, insert_date) VALUES (
                                                                                              '".$conn->real_escape_string($_POST['aktionsgruppe'])."',
                                                                                              '".$conn->real_escape_string($gruppen_id)."',
                                                                                                NOW())";
                        if ($conn->query($sql_insert_gruppe_aktionsgruppe) === FALSE) {
                            throw new Exception($conn->error);
                        }
                    }
                }

                $database->closeConnection();
                echo "Aktionsgruppe erfolgreich aktualisiert";
            } else {
                throw new Exception('Formularvariablen sind ungültig oder werden nicht empfangen');
            }
        } catch (Exception $ex) {
            /*echo json_encode(array(
                'error' => array(
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ),
            ));*/
            echo $ex->getMessage();
            exit();
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>