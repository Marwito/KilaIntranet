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
        if(isset($_POST['input_text0'], $_POST['input_text1'])){
            require_once('../../../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            if (isset($_POST['input_checkbox0'])) {
                $rechnung = 1;
            } else {
                $rechnung = 0;
            }
            $sql = "INSERT INTO keis2_ansprechpartner_amt (vorname, name, telefonnummer,
            mobil, email, fax, strasse, plz, ort, amt, rechnung, insert_date)
    		VALUES ('".$conn->real_escape_string($_POST['input_text0'])."',
    		'".$conn->real_escape_string($_POST['input_text1'])."',
            '".$conn->real_escape_string($_POST['input_text2'])."',
            '".$conn->real_escape_string($_POST['input_text3'])."',
            '".$conn->real_escape_string($_POST['input_text4'])."',
            '".$conn->real_escape_string($_POST['input_text5'])."',
            '".$conn->real_escape_string($_POST['input_text6'])."',
            '".$conn->real_escape_string($_POST['input_text7'])."',
            '".$conn->real_escape_string($_POST['input_text8'])."',
            '".$conn->real_escape_string($_POST['amt'])."',
            ".$conn->real_escape_string($rechnung).",
            NOW())";
            
            if ($conn->query($sql)=== TRUE) {
                echo "Der Ansprechpartner wurde erfolgreich erstellt";
            } else {
                echo "Beim Hinzufügen dieses Ansprechpartners ist ein Fehler aufgetreten : " . $conn->error;
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