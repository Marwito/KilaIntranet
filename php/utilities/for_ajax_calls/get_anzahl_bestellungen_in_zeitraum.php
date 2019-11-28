<?php
if(isset($_POST['essenkategorie'], $_POST['einrichtung'], $_POST['start'], $_POST['ende'])) {
    require_once('../db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "SELECT keis2_bestellung.id
            FROM keis2_bestellung
            INNER JOIN keis2_kind ON keis2_bestellung.kind=keis2_kind.id
                                  AND keis2_bestellung.update_date IS NULL
                                  AND keis2_bestellung.essenkategorie=".$conn->real_escape_string($_POST['essenkategorie'])."
                                  AND keis2_kind.zuordnung_einrichtung=".$conn->real_escape_string($_POST['einrichtung'])."
                                  AND keis2_bestellung.datum BETWEEN '".$conn->real_escape_string(date("Y-m-d", strtotime($_POST['start'])))."' 
                                      AND '".$conn->real_escape_string(date('Y-m-d', strtotime($_POST['ende'])))."'";
    if($_POST['aktionsgruppe'] != null) {
        $sql = $sql." AND keis2_kind.zuordnung_aktionsgruppe=".$conn->real_escape_string($_POST['aktionsgruppe']);
    }
    $result = $conn->query($sql);
    echo $result->num_rows;
    $database->closeConnection();
}
?>