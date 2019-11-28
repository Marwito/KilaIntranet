<?php
if (isset($_POST['input_passwort0'], $_POST['benutzer'])) {
    require_once('../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $passwort = password_hash($_POST['input_passwort0'], PASSWORD_ARGON2I);
    $sql = "UPDATE keis2_benutzer SET passwort=
            '".$conn->real_escape_string($passwort)."',
            update_date= NOW()
            WHERE id=".$conn->real_escape_string($_POST['benutzer'])."";
    
    if ($conn->query($sql)=== TRUE) {
        echo "Passwort erfolgreich aktualisiert";
    } else {
        echo "Fehler beim Aktualisieren dieses Passworts : " .$conn->error;
    }
    $database->closeConnection();
} else {
    echo 'Formularvariablen sind ungültig oder werden nicht empfangen';
}
?>