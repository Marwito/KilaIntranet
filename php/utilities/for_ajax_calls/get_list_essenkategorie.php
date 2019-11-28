<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$condition = '';
$sql = "SELECT * FROM keis2_essenkategorie";
if (isset($_POST['gastKategorie'])) {
    if ($_POST['gastKategorie'] == 'Kinder') {
        $condition .= ' WHERE preis_kind = 0';
    } else {
        $condition .= ' WHERE preis_mitarbeiter = 0';
    }
}
$sql .= $condition;
$result = $conn->query($sql);
$essenskategorienArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['kategorie'];
    $essenskategorienArray[] = array("id" => $id, "name" => $name);
}
// encoding array to json format
echo json_encode($essenskategorienArray);
$database->closeConnection();
?>