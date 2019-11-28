<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT id, name FROM keis2_amt";
$result = $conn->query($sql);
$amtArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $amtArray[] = array("id" => $id, "name" => $name);
}
// encoding array to json format
echo json_encode($amtArray);
$database->closeConnection();
?>