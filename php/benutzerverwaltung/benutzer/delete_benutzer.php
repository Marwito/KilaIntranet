<?php
if(isset($_POST['id'])){
    require_once('../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "DELETE FROM keis2_benutzer WHERE
            id = ".$conn->real_escape_string($_POST['id'])."";
    if ($conn->query($sql)=== TRUE) {
        echo "Der Benutzer wurde erfolgreich entfernt";
    } else {
        echo "Beim Löschen dieses Benutzers ist ein Fehler aufgetreten : " . $conn->error;
    }
    $database->closeConnection();
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}
?>