<?php
if (isset($_POST['gastKategorie'], $_POST['essenKategorie'])) {
    require_once('../db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "SELECT * FROM keis2_preiskategorie WHERE gastkategorie =
            '".$conn->real_escape_string($_POST['gastKategorie'])."' AND 
            essenkategorie = 
            '".$conn->real_escape_string($_POST['essenKategorie'])."'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo 'true';
    } else {
        echo 'false';
    }
    $database->closeConnection();
} else {
    
}