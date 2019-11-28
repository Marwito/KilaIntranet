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
            if(isset($_POST['input_text0'], $_POST['input_select0'], $_POST['gruppe'])){
                require_once('../../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                
                $sql = "UPDATE keis2_gruppe SET name=
            	'".$conn->real_escape_string($_POST['input_text0'])."',
                einrichtung=
                '".$conn->real_escape_string($_POST['input_select0'])."',
                update_date= NOW()
                WHERE id=".$conn->real_escape_string($_POST['gruppe'])."";
                
                if ($conn->query($sql)=== TRUE) {
                    echo "Die Gruppe wurde erfolgreich aktualisiert!";
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