<?php
if (isset($_POST['id'])){
    require_once('../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "UPDATE keis2_benutzer SET aktiv = 0
            WHERE id=".$conn->real_escape_string($_POST['id'])."";
    
    if ($conn->query($sql)=== TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error while updating this record <br>" .$conn->error;
    }
    $database->closeConnection();
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}
?>