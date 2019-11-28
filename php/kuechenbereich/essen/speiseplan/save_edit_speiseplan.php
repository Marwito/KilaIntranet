<?php
if(isset($_POST['input_text0'], $_POST['input_text1'], $_POST['input_text2'], $_POST['input_text3'], $_POST['input_select0'], $_POST['speiseplan'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "UPDATE keis2_speiseplan SET datum=
        	'".$conn->real_escape_string($_POST['input_text0'])."', hauptgericht=
            '".$conn->real_escape_string($_POST['input_text1'])."', beilage=
            '".$conn->real_escape_string($_POST['input_text2'])."', nachspeise=
            '".$conn->real_escape_string($_POST['input_text3'])."', einrichtung=
            '".$conn->real_escape_string($_POST['input_select0'])."', update_date=
            NOW()
            WHERE id=".$conn->real_escape_string($_POST['speiseplan'])."";
    
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