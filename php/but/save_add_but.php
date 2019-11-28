<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        
        try {
            if(isset($_POST['aktenzeichen'], $_POST['datetimepicker_bescheid_von'],
             $_POST['datetimepicker_bescheid_bis'], $_POST['anteilsart'],
             $_POST['anteilsbetrag'], $_POST['debitorennummer'],
             $_POST['kind'], $_POST['ansprechpartner'])) {
                 
                require_once('../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                
                $sql = "INSERT INTO keis2_but (kind, eigenanteil, aktenzeichen, von, bis, eigenanteil_proEssen,
                eigenanteil_proMonat, debitorennummer, ansprechpartner_id, insert_date)
                
                VALUES ('".$conn->real_escape_string($_POST['kind'])."',
                '".$conn->real_escape_string($_POST['anteilsbetrag'])."',
                '".$conn->real_escape_string($_POST['aktenzeichen'])."',
                STR_TO_DATE('".$conn->real_escape_string($_POST['datetimepicker_bescheid_von'])."', '%d.%m.%Y'),
                STR_TO_DATE('".$conn->real_escape_string($_POST['datetimepicker_bescheid_bis'])."', '%d.%m.%Y'),";
                
                // Anteilsart 1 = pro Essen, automatisch pro Monat = 0,
                // ansonsten pro Essen = 0 und pro Monat = 1
                if ($_POST['anteilsart'] == 1) {
                    $sql.= "1,0,";
                } else {
                    $sql.= "0,1,";
                }
                
                $sql.= "'".$conn->real_escape_string($_POST['debitorennummer'])."',
                        '".$conn->real_escape_string($_POST['ansprechpartner'])."',
                        NOW())";
    
                if ($conn->query($sql)=== TRUE) {
                    echo "Der BUT wurde erfolgreich gespeichert!";
                } else {
                    throw new Exception($conn->error);
                }
                $database->closeConnection();
            } else {
                throw new Exception('Formularvariablen sind ungültig oder werden nicht empfangen');
            }
        } catch (Exception $ex) {
            echo json_encode(array(
                'error' => array(
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ),
            ));
            exit();
        }
    }
}else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>