<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT * FROM keis2_einheit";
$result = $conn->query($sql);
$einheitArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $einheitArray[] = array("id" => $id, "name" => $name);
}
// encoding array to json format
echo json_encode($einheitArray);
$database->closeConnection();
?>