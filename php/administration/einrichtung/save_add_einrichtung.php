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
            if(isset($_POST['input_text0'], $_POST['input_text1'], $_POST['input_text2'], $_POST['input_text3'], $_POST['input_text4'], $_POST['input_text5'])){
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                
                $sql = "INSERT INTO keis2_einrichtung (name, strasse, plz, ort,
                abmeldefrist, vortag, insert_date)
        		VALUES ('".$conn->real_escape_string($_POST['input_text0'])."',
        		'".$conn->real_escape_string($_POST['input_text1'])."',
                '".$conn->real_escape_string($_POST['input_text2'])."',
                '".$conn->real_escape_string($_POST['input_text3'])."',
                STR_TO_DATE('".$conn->real_escape_string($_POST['input_text4'])."', '%H:%i'),
                '".$conn->real_escape_string($_POST['input_text5'])."',
                NOW())";
                
                if ($conn->query($sql)=== TRUE) {

                    // Einrichtung-ID
                    $einrichtung_id = mysqli_insert_id($conn);
                    
                    // Verarbeitung Ansprechpartner
                    if (isset($_POST['ansprechpartner'])) {
                        $ansprechpartner_array = explode(",", $_POST['ansprechpartner']);
                        
                        foreach ($ansprechpartner_array as &$ansprechpartner_id) {
                            $sql_update_ansprechpartner = "UPDATE keis2_ansprechpartner set einrichtung_id = '".$einrichtung_id."' WHERE id = '".$ansprechpartner_id."'";
                            if ($conn->query($sql_update_ansprechpartner) === FALSE) {
                                throw new Exception($conn->error);
                            }
                        }
                    }
                } else {
                    throw new Exception($conn->error);
                }
                $database->closeConnection();
                echo "Die Enrichtung wurde erfolgreich erstellt";
            } else {
                throw new Exception('Formularvariablen sind ungültig oder werden nicht empfangen');
            }
        } catch (Exception $ex) {
            $ex->getMessage();
            exit();
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>