<?php
date_default_timezone_set('Europe/Berlin');
if(isset($_POST['id'], $_POST['datum'], $_POST['frist'], $_POST['essenkategorie'])){
    require_once('../../utilities/feiertage-api-connector.php');
    $connector = LPLib_Feiertage_Connector::getInstance();
    if(!$connector->isFeiertagInLand($_POST['datum'], LPLib_Feiertage_Connector::LAND_BAYERN)) {
        require_once('../../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $sqlDauerbestellung = "SELECT * FROM keis2_dauerbestellung WHERE kind = '".$conn->real_escape_string($_POST['id'])."'";
        $resultDauerbestellung = $conn->query($sqlDauerbestellung);
        if($resultDauerbestellung->num_rows == 0) {
            if(strtotime("now") < $_POST['frist']) {
                $sql = "INSERT INTO keis2_bestellung (kind, datum, essenkategorie, insert_date)
                        VALUES ('".$conn->real_escape_string($_POST['id'])."', 
                        '".$conn->real_escape_string($_POST['datum'])."', 
                        '".$conn->real_escape_string($_POST['essenkategorie'])."', 
                        NOW());";
                if ($conn->query($sql)=== TRUE) {
                    echo "Das Essen wurde erfolgreich bestellt.";
                } else {
                    echo "Beim Bestellen des Essens ist ein Fehler aufgetreten: ".$conn->error;
                }
                $database->closeConnection();
            } else {
                echo "Bestellfrist ist abgelaufen!";
            }
        }
    }
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen.';
}
?>