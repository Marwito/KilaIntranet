<?php
require_once('../../../login/session.php');
require_once('../../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../../../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        if(isset($_POST['input_text0'], $_POST['input_text1'], $_POST['ansprechpartner'])){
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            $sql = "UPDATE keis2_ansprechpartner_amt SET vorname=
        	'".$conn->real_escape_string($_POST['input_text0'])."', name=
        	'".$conn->real_escape_string($_POST['input_text1'])."', telefon=
            '".$conn->real_escape_string($_POST['input_text2'])."', mobil=
            '".$conn->real_escape_string($_POST['input_text3'])."', email=
            '".$conn->real_escape_string($_POST['input_text4'])."', fax=
            '".$conn->real_escape_string($_POST['input_text5'])."', strasse=
            '".$conn->real_escape_string($_POST['input_text6'])."', plz=
            '".$conn->real_escape_string($_POST['input_text7'])."', ort=
            '".$conn->real_escape_string($_POST['input_text8'])."',
            update_date= NOW()
            WHERE id=".$conn->real_escape_string($_POST['ansprechpartner'])."";
            
            if ($conn->query($sql)=== TRUE) {
                echo "Datensatz erfolgreich aktualisiert";
            } else {
                echo "Fehler beim Aktualisieren dieses Datensatzes : " .$conn->error;
            }
            $database->closeConnection();
        } else {
            echo 'Formularvariablen sind ungültig oder werden nicht empfangen';
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>