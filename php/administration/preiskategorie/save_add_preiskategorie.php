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
            if (isset($_POST['input_text0'], $_POST['input_select0'], $_POST['input_text1'])) {
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $preis = str_replace(',', '.', $_POST['input_text0']);
                
                $sql = "INSERT INTO keis2_preiskategorie (gastkategorie, essenkategorie,
                preis, insert_date) VALUES (
                '".$conn->real_escape_string($_POST['input_select0'])."',
                '".$conn->real_escape_string($_POST['input_select1'])."',
                '".$conn->real_escape_string($preis)."',
                STR_TO_DATE('".$conn->real_escape_string($_POST['input_text1'])."', '%d.%m.%Y'),
                NOW())";
                
                if ($conn->query($sql)=== TRUE) {

                    // Preiskategorie-ID
                    $preiskategorie_id = $conn->insert_id;
                    
                    // Verarbeitung Zuordnung Gruppe - Preiskategorie
                    if (isset($_POST['gruppen'])) {
                        $gruppen_array = explode(",", $_POST['gruppen']);
                        
                        foreach ($gruppen_array as &$gruppe_id) {
                            $sql_update_gruppe_preisgruppe = "INSERT INTO keis2_gruppe_preiskategorie (gruppe_id, preiskategorie_id, insert_date) VALUES (
                                                            ".$gruppe_id.",
                                                            ".$preiskategorie_id.",
                                                            NOW())";
                            if ($conn->query($sql_update_gruppe_preisgruppe) === FALSE) {
                                throw new Exception($conn->error);
                            }
                        }
                    }
                } else {
                    throw new Exception($conn->error);
                }
    
                $database->closeConnection();
                echo "Die Preiskategorie wurde erfolgreich erstellt!";
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