<?php
setlocale(LC_TIME, 'de');
if(isset($_POST['essenkategorie'], $_POST['einrichtung'], $_POST['start'], $_POST['ende'])) {
    require_once('../db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $anzahlDauerbestellungen = 0;
    $anzahlAbbestellungen = 0;
    require_once('../feiertage-api-connector.php');
    $connector = LPLib_Feiertage_Connector::getInstance();
    for($i = strtotime($_POST['start']); $i <= strtotime($_POST['ende']); $i = $i+86400) {
        if(strftime("%A", $i) != "Samstag" && strftime("%A", $i) != "Sonntag" && !$connector->isFeiertagInLand(date('Y-m-d',$i), LPLib_Feiertage_Connector::LAND_BAYERN)) {
            $sqlDauerbestellungAnzahl = "SELECT *
                                    FROM keis2_dauerbestellung
                                    INNER JOIN keis2_kind ON keis2_dauerbestellung.kind=keis2_kind.id
                                                          WHERE keis2_kind.zuordnung_einrichtung=".$conn->real_escape_string($_POST['einrichtung'])."
                                                          AND keis2_dauerbestellung.essenkategorie=".$conn->real_escape_string($_POST['essenkategorie'])."
                                                          AND DATEDIFF(keis2_dauerbestellung.essenstart,'".$conn->real_escape_string(date('Y-m-d',$i))."')<=0
                                                          AND DATEDIFF(keis2_dauerbestellung.essenende,'".$conn->real_escape_string(date('Y-m-d',$i))."')>=0
                                                          AND (DATEDIFF(keis2_dauerbestellung.insert_date,'".$conn->real_escape_string(date('Y-m-d',$i))."')<=0
                                                               OR keis2_dauerbestellung.insert_date IS NULL)
                                                          AND keis2_dauerbestellung.".$conn->real_escape_string(strtolower(strftime('%A', $i)))."=1";
            if($_POST['aktionsgruppe'] != null) {
                $sqlDauerbestellungAnzahl = $sqlDauerbestellungAnzahl." AND keis2_kind.zuordnung_aktionsgruppe=".$conn->real_escape_string($_POST['aktionsgruppe']);
            }
            $resultDauerbestellungAnzahl = $conn->query($sqlDauerbestellungAnzahl);
            $anzahlDauerbestellungen = $anzahlDauerbestellungen + $resultDauerbestellungAnzahl->num_rows;
        }
    }
    $sqlAbbestellungAnzahl = "SELECT * FROM keis2_abbestellung
                                  INNER JOIN keis2_kind ON keis2_abbestellung.kind=keis2_kind.id
                                  WHERE keis2_abbestellung.datum BETWEEN '".$conn->real_escape_string(date("Y-m-d", strtotime($_POST['start'])))."' AND '".$conn->real_escape_string(date("Y-m-d", strtotime($_POST['ende'])))."'
                                  AND keis2_abbestellung.update_date IS NULL
                                  AND keis2_kind.zuordnung_einrichtung=".$conn->real_escape_string($_POST['einrichtung'])."
                                  AND keis2_abbestellung.essenkategorie=".$conn->real_escape_string($_POST['essenkategorie']);
    if($_POST['aktionsgruppe'] != null) {
        $sqlAbbestellungAnzahl = $sqlAbbestellungAnzahl." AND keis2_kind.zuordnung_aktionsgruppe=".$conn->real_escape_string($_POST['aktionsgruppe']);
    }
    $resultAbbestellungenAnzahl = $conn->query($sqlAbbestellungAnzahl);
    $anzahlAbbestellungen = $anzahlAbbestellungen + $resultAbbestellungenAnzahl->num_rows;
    echo $anzahlDauerbestellungen - $anzahlAbbestellungen;
    $database->closeConnection();
}
?>