<?php
try {
    if (isset($_POST['monat'], $_POST['jahr'], $_POST['endmonat'], $_POST['endjahr'])) {
        require_once('../../utilities/db_connection.php');
        $database = new DatabaseConnection();
        $conn = $database->getConn();
        $firstDay = '1' . '.' . $_POST['monat'] . '.' . $_POST['jahr'];
        $daysNumber = cal_days_in_month(CAL_GREGORIAN, $_POST['endmonat'], $_POST['endjahr']);
        $lastDay = $daysNumber . '.' . $_POST['endmonat'] . '.' . $_POST['endjahr'];
        
        $sql = "UPDATE keis2_rechnung SET abgeschlossen = 1
                WHERE zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($firstDay)."', '%e.%c.%Y')
                AND zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($lastDay)."', '%e.%c.%Y')
                AND update_date IS NULL";
        
        if ($conn->query($sql)=== TRUE) {
            echo "Rechnungslauf abgeschlossen !";
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
?>