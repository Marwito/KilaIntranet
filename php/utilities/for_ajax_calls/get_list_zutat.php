<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$sql = "SELECT * FROM keis2_zutat";
$result = $conn->query($sql);
$zutatsArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $zutatsArray[] = array("id" => $id, "name" => $name);
}
// encoding array to json format
echo json_encode($zutatsArray);
$database->closeConnection();
?>