<?php
require_once('../../login/session.php');
require_once('../../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    
    try {
        require_once('../../benutzerverwaltung/benutzer/benutzer_class.php');
        $benutzer = new Benutzer();
        if (!($benutzer->isAdmin($session->usergroup) || $benutzer->isLeiter($session->usergroup))) {
            header('Location: ' . Constants::getBaseURL());
        }

        if (isset($_FILES['inputFile']) && $_FILES['inputFile']['size'] > 0) {
            if($_FILES['inputFile']) {
                $success = null;
                $target_dir = '../../../doc/';
                $newFileName = "Speiseplan.pdf";
                $fileName = $_FILES["inputFile"]["tmp_name"];

                $target = $target_dir . basename($newFileName);
                if(move_uploaded_file($fileName, $target)) {
                    $success = true;
                } else {
                    $success = false;
                }
                
                if ($success === true) {
                    echo "Speiseplan erfolgreich übertragen!";
                } elseif ($success === false) {
                    throw new Exception("Fehler beim Hochladen des Speiseplans!");
                } else {
                    echo "Es wurde kein Speiseplan hochgeladen!";
                }
            }
        } else {
            throw new Exception("Keine Dateien übergeben! Verarbeitung nicht möglich!");
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
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}
?>