<?php
/*
$start_jahr = DateTime::createFromFormat('j.n.Y', '10.2.2019');
$end_jahr = DateTime::createFromFormat('d.m.Y', '11.03.2019');
if ($end_jahr > $start_jahr) {
    echo 'higher';
} else {
    echo 'lower';
}

$interval = $start_jahr->diff($end_jahr);
echo $interval->format('%m');*/

require_once('utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();

$zeitraum_von = '01.02.2019';
$zeitraum_bis = '01.03.2019';

$query = "SELECT id FROM keis2_rechnung WHERE update_date IS NULL AND abgeschlossen = 1 AND (zeitraum_von <=
            STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y') AND
            zeitraum_bis >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') OR (zeitraum_bis
            >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND zeitraum_von
            <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y'))) ";

$result = $conn->query($query);
if ($result->num_rows > 0) {
    echo 'Überschneidung';
} else {
    echo 'NO';
}