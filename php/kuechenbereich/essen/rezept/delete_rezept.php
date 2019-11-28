<?php
if(isset($_POST['id'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "DELETE FROM keis2_rezept WHERE
            id = ".$conn->real_escape_string($_POST['id'])."";
    if ($conn->query($sql)=== TRUE) {
        echo "Das Rezept wurde erfolgreich entfernt";
    } else {
        echo "Beim Löschen dieses Rezeptes ist ein Fehler aufgetreten : " . $conn->error;
    }
    $database->closeConnection();
}else{
    echo 'Rezept nicht gefunden';
}
?>