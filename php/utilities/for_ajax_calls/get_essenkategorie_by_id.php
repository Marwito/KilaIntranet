<?php
if (isset($_POST['id'])) {
    require_once('../db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "SELECT kategorie FROM keis2_essenkategorie WHERE id = ".$_POST['id'];
    $result = $conn->query($sql);
    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    }
    echo $row['kategorie'];
    $database->closeConnection();
}
?>