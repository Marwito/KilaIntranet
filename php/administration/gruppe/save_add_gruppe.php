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
            if(isset($_POST['input_text0'], $_POST['input_select0'])){
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $sql = "INSERT INTO keis2_gruppe (name, einrichtung, insert_date)
        		VALUES ('".$conn->real_escape_string($_POST['input_text0'])."',
                '".$conn->real_escape_string($_POST['input_select0'])."',
                NOW())";
                
                if ($conn->query($sql)=== TRUE) {
                    echo "Die Gruppe wurde erfolgreich erstellt!";
                } else {
                    throw new Exception($conn->error);
                }
                
                $database->closeConnection();
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