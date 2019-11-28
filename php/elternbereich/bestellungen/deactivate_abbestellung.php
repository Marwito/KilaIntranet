<?php
date_default_timezone_set('Europe/Berlin');
setlocale(LC_TIME, 'de');
if(isset($_POST['id'], $_POST['datum'], $_POST['frist'])){
    require_once('../../utilities/feiertage-api-connector.php');
    $connector = LPLib_Feiertage_Connector::getInstance();
    if(!$connector->isFeiertagInLand($_POST['datum'], LPLib_Feiertage_Connector::LAND_BAYERN)) {
        require_once('../../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $sqlDauerbestellung = "SELECT * FROM keis2_dauerbestellung WHERE kind = '".$conn->real_escape_string($_POST['id'])."'";
        $resultDauerbestellung = $conn->query($sqlDauerbestellung);
        if($resultDauerbestellung->num_rows > 0) {
            if(strtotime("now") < $_POST['frist']) {
                $sql = "UPDATE keis2_abbestellung SET update_date=
                        NOW()
                        WHERE kind = '".$conn->real_escape_string($_POST['id'])."' AND
                        update_date IS NULL AND
                        datum = '".$conn->real_escape_string($_POST['datum'])."'";
                if ($conn->query($sql)=== TRUE) {
                    echo "Das Essen wurde erfolgreich bestellt.";
                } else {
                    echo "Beim Bestellen des Essens ist ein Fehler aufgetreten: ".$conn->error;
                }
                $database->closeConnection();
            } else {
                echo "Bestellfrist ist abgelaufen";
            }
        }
    }
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}
?>