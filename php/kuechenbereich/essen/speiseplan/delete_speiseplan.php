<?php
if(isset($_POST['id'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "DELETE FROM keis2_speiseplan WHERE
            id = ".$conn->real_escape_string($_POST['id'])."";
    if ($conn->query($sql)=== TRUE) {
        echo "Der Speiseplan wurde erfolgreich entfernt";
    } else {
        echo "Beim Löschen dieses Speiseplans ist ein Fehler aufgetreten : " . $conn->error;
    }
    $database->closeConnection();
}else{
    echo 'Speiseplan nicht gefunden';
}
?>