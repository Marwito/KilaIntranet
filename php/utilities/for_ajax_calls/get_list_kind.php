<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT id, name, vorname FROM keis2_kind";
if($_POST['einrichtung'] != -1) {
    $sql = $sql." WHERE zuordnung_einrichtung=".$_POST['einrichtung'];
}
$result = $conn->query($sql);
$kindArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $vorname = $row['vorname'];
    $kindArray[] = array("id" => $id, "name" => $name, "vorname" => $vorname);
}
// encoding array to json format
echo json_encode($kindArray);
$database->closeConnection();
?>