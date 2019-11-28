<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$kuechenArray = array();
$sql = "SELECT id, name FROM keis2_kueche";
$result = $conn->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $id = $row['id'];
        $name = $row['name'];
        $kuechenArray[] = array("id" => $id, "name" => $name);
    }
}
$database->closeConnection();
// encoding array to json format
echo json_encode($kuechenArray)
?>