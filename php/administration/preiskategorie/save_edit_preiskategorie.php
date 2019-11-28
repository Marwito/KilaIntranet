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
            if (isset($_POST['input_text0'], $_POST['preiskategorie'], $_POST['input_text1'])) {
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $preis = str_replace(',', '.', $_POST['input_text2']);
                $sql = "UPDATE keis2_preiskategorie SET preis=
                    	'".$conn->real_escape_string($preis)."', update_date= NOW()
                        WHERE id=".$conn->real_escape_string($_POST['preiskategorie'])."";
                
                if ($conn->query($sql)=== TRUE) {
                    
                    // Verarbeitung der Gruppen
                    if (isset($_POST['gruppen'])) {
                        
                        // 1. Bereinigung
                        $sql_remove_gruppe_preiskategorie = "DELETE FROM keis2_gruppe_preiskategorie WHERE preiskategorie_id = '".$_POST['preiskategorie']."'";
                        if ($conn->query($sql_remove_gruppe_preiskategorie) === FALSE) {
                            throw new Exception($conn->error);
                        }
                        
                        // 2. Update
                        $gruppen_array = explode(",", $_POST['gruppen']);
                        
                        foreach ($gruppen_array as &$gruppen_id) {
                            $sql_insert_gruppe_preiskategorie = "INSERT INTO keis2_gruppe_preiskategorie (preiskategorie_id, gruppe_id, insert_date) VALUES (
                                                                                              '".$conn->real_escape_string($_POST['preiskategorie'])."',
                                                                                              '".$conn->real_escape_string($gruppen_id)."',
                                                                                                NOW())";
                            if ($conn->query($sql_insert_gruppe_preiskategorie) === FALSE) {
                                throw new Exception($conn->error);
                            }
                        }
                    }
                } else {
                    throw new Exception($conn->error);
                }
                $database->closeConnection();
                echo "Die Preisgruppe wurde erfolgreich aktualisiert!";
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