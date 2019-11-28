<?php
if(isset($_POST['input_text0'], $_POST['input_select0'], $_POST['zutat'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "UPDATE keis2_zutat SET name=
        	'".$conn->real_escape_string($_POST['input_text0'])."', einheit=
            '".$conn->real_escape_string($_POST['input_select0'])."'
            WHERE id=".$conn->real_escape_string($_POST['zutat'])."";
    
    if ($conn->query($sql)=== TRUE) {
        echo "Datensatz erfolgreich aktualisiert";
    } else {
        echo "Fehler beim Aktualisieren dieses Datensatzes : " .$conn->error;
    }
    $database->closeConnection();
} else {
    echo 'Formularvariablen sind ungültig oder werden nicht empfangen';
}
?>