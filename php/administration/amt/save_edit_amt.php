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
            if(isset($_POST['input_text0'], $_POST['input_text1'], $_POST['input_text2'], $_POST['input_text3'], $_POST['amt'])){
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                
                $sql = "UPDATE keis2_amt SET name=
            	'".$conn->real_escape_string($_POST['input_text0'])."', strasse=
            	'".$conn->real_escape_string($_POST['input_text1'])."', plz=
                '".$conn->real_escape_string($_POST['input_text2'])."', ort=
                '".$conn->real_escape_string($_POST['input_text3'])."',
                update_date= NOW()
                WHERE id=".$conn->real_escape_string($_POST['amt'])."";
    
                if ($conn->query($sql)=== TRUE) {
    
                    // Verarbeitung Ansprechpartner
                    if (isset($_POST['ansprechpartner'])) {
                        
                        // 1. Bereinigung
                        $sql_remove_key_amt_ansprechpartner = "UPDATE keis2_ansprechpartner set amt_id = NULL WHERE amt_id = '".$_POST['amt']."'";
                        if ($conn->query($sql_remove_key_amt_ansprechpartner) === FALSE) {
                            throw new Exception($conn->error);
                        }
                        
                        // 2. Update
                        $ansprechpartner_array = explode(",", $_POST['ansprechpartner']);
                        
                        foreach ($ansprechpartner_array as &$ansprechpartner_id) {
                            $sql_update_ansprechpartner = "UPDATE keis2_ansprechpartner set amt_id = '".$_POST['amt']."' WHERE id = '".$ansprechpartner_id."'";
                            if ($conn->query($sql_update_ansprechpartner) === FALSE) {
                                throw new Exception($conn->error);
                            }
                        }
                    }
                    
                    // Verarbeitung der Einrichtungen
                    if (isset($_POST['einrichtungen'])) {
    
                        // 1. Bereinigung
                        $sql_remove_einrichtung_amt = "DELETE FROM keis2_einrichtung_amt WHERE amt_id = '".$_POST['amt']."'";
                        if ($conn->query($sql_remove_einrichtung_amt) === FALSE) {
                            throw new Exception($conn->error);
                        }
                        
                        // 2. Update
                        $einrichtungen_array = explode(",", $_POST['einrichtungen']);
                        
                        foreach ($einrichtungen_array as &$einrichtung_id) {
                            $sql_insert_einrichtung_amt = "INSERT INTO keis2_einrichtung_amt (amt_id, einrichtung_id, insert_date) VALUES (
                                                                                              '".$conn->real_escape_string($_POST['amt'])."',
                                                                                              '".$conn->real_escape_string($einrichtung_id)."',
                                                                                                NOW())";
                            if ($conn->query($sql_insert_einrichtung_amt) === FALSE) {
                                throw new Exception($conn->error);
                            }
                        }
                    }
                } else {
                    throw new Exception($conn->error);
                }
                $database->closeConnection();
                echo "Das Amt wurde erfolgreich aktualisiert!";
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