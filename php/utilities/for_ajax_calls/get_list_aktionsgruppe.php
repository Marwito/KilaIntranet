<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$condition = '';
$sql = "SELECT id, bezeichnung FROM keis2_aktionsgruppe";

if(isset($_POST['gruppe']) && $_POST['gruppe'] != -1) {
    $condition .= " WHERE id IN (SELECT id_aktionsgruppe FROM keis2_gruppe_aktionsgruppe WHERE id_gruppe=".$conn->real_escape_string($_POST['gruppe']).")";
}
$sql .= $condition;
$result = $conn->query($sql);
$gruppenArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['bezeichnung'];
    $gruppenArray[] = array("id" => $id, "name" => $name);
}
// encoding array to json format
echo json_encode($gruppenArray);
$database->closeConnection();
?>