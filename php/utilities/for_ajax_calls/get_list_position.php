<?php
    require_once('../db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "SELECT * FROM keis2_position";
    $result = $conn->query($sql);
    $positionsArray = array();
    while($row = $result->fetch_assoc()){
        $id = $row['id'];
        $name = $row['name'];
        $positionsArray[] = array("id" => $id, "name" => $name);
    }
    // encoding array to json format
    echo json_encode($positionsArray);
    $database->closeConnection();
?>