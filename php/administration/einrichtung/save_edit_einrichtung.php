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
            if(isset($_POST['input_text0'], $_POST['input_text1'], $_POST['input_text2'], $_POST['input_text3'], $_POST['input_text4'], $_POST['input_text5'], $_POST['einrichtung'])){
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                
                $sql = "UPDATE keis2_einrichtung SET name=
            	'".$conn->real_escape_string($_POST['input_text0'])."', strasse=
            	'".$conn->real_escape_string($_POST['input_text1'])."', plz=
                '".$conn->real_escape_string($_POST['input_text2'])."', ort=
                '".$conn->real_escape_string($_POST['input_text3'])."', abmeldefrist=
                STR_TO_DATE('".$conn->real_escape_string($_POST['input_text4'])."', '%H:%i'), vortag=
                '".$conn->real_escape_string($_POST['input_text5'])."', update_date=
                NOW()
                WHERE id=".$conn->real_escape_string($_POST['einrichtung'])."";
                
                if ($conn->query($sql)=== TRUE) {

                    // Verarbeitung Ansprechpartner
                    if (isset($_POST['ansprechpartner'])) {
                        
                        // 1. Bereinigung
                        $sql_remove_key_einrichtung_ansprechpartner = "UPDATE keis2_ansprechpartner set einrichtung_id = NULL WHERE einrichtung_id = '".$_POST['einrichtung']."'";
                        if ($conn->query($sql_remove_key_einrichtung_ansprechpartner) === FALSE) {
                            throw new Exception($conn->error);
                        }
                        
                        // 2. Update
                        $ansprechpartner_array = explode(",", $_POST['ansprechpartner']);
                        
                        foreach ($ansprechpartner_array as &$ansprechpartner_id) {
                            $sql_update_ansprechpartner = "UPDATE keis2_ansprechpartner set einrichtung_id = '".$_POST['einrichtung']."' WHERE id = '".$ansprechpartner_id."'";
                            if ($conn->query($sql_update_ansprechpartner) === FALSE) {
                                throw new Exception($conn->error);
                            }
                        }
                    }
                } else {
                    throw new Exception($conn->error);
                }
                $database->closeConnection();
                echo "Die Einrichtung wurde erfolgreich aktualisiert!";
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