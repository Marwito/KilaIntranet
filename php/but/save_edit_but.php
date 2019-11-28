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
            if(isset($_POST['butID'], $_POST['aktenzeichen'],
                $_POST['datetimepicker_bescheid_von'], $_POST['datetimepicker_bescheid_bis'],
                $_POST['anteilsart'], $_POST['anteilsbetrag'], $_POST['debitorennummer'],
                $_POST['kind'], $_POST['ansprechpartner'])) {
                 
                require_once('../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                 
                $sql = "UPDATE keis2_but SET 
                kind='".$conn->real_escape_string($_POST['kind'])."',
                eigenanteil='".$conn->real_escape_string($_POST['anteilsbetrag'])."',
                aktenzeichen='".$conn->real_escape_string($_POST['aktenzeichen'])."',
                von=STR_TO_DATE('".$conn->real_escape_string($_POST['datetimepicker_bescheid_von'])."', '%d.%m.%Y'),
                bis=STR_TO_DATE('".$conn->real_escape_string($_POST['datetimepicker_bescheid_bis'])."', '%d.%m.%Y'),";
    
                // Anteilsart 1 = pro Essen, automatisch pro Monat = 0,
                // ansonsten pro Essen = 0 und pro Monat = 1
                if ($_POST['anteilsart'] == 1) {
                $sql.= "eigenanteil_proEssen=1,eigenanteil_proMonat=0,";
                } else {
                $sql.= "eigenanteil_proEssen=0,eigenanteil_proMonat=1,";
                }
                
                $sql.= "debitorennummer='".$conn->real_escape_string($_POST['debitorennummer'])."',
                ansprechpartner_id='".$conn->real_escape_string($_POST['ansprechpartner'])."',
                update_date=NOW()
                WHERE id='".$conn->real_escape_string($_POST['butID'])."'";
    
                if ($conn->query($sql)=== TRUE) {
                    echo "Der BUT wurde erfolgreich atkualisiert!";
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