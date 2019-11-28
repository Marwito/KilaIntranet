<?php
if(isset($_POST['input_text0'], $_POST['input_select0'], $_POST['input_text1'], $_POST['input_select1'], $_POST['input_select2'], $_POST['rezept'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "UPDATE keis2_rezept SET name=
        	'".$conn->real_escape_string($_POST['input_text0'])."', zutat=
            '".$conn->real_escape_string($_POST['input_select0'])."', menge=
            '".$conn->real_escape_string($_POST['input_text1'])."', essenkategorie=
            '".$conn->real_escape_string($_POST['input_select1'])."', speisenart=
            '".$conn->real_escape_string($_POST['input_select2'])."'
            WHERE id=".$conn->real_escape_string($_POST['rezept'])."";
    
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