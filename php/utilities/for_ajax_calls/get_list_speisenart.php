<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT * FROM keis2_speisenart";
$result = $conn->query($sql);
$speiseartenArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $speiseartenArray[] = array("id" => $id, "name" => $name);
}
// encoding array to json format
echo json_encode($speiseartenArray);
$database->closeConnection();
?>