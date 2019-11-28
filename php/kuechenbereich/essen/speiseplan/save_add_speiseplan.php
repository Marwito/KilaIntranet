<?php
if(isset($_POST['input_text0'], $_POST['input_text1'], $_POST['input_text2'], $_POST['input_text3'], $_POST['input_select0'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "INSERT INTO keis2_speiseplan (datum, hauptgericht, beilage, nachspeise, einrichtung, insert_date)
    		VALUES ('".$conn->real_escape_string($_POST['input_text0'])."',
            '".$conn->real_escape_string($_POST['input_text1'])."',
            '".$conn->real_escape_string($_POST['input_text2'])."',
            '".$conn->real_escape_string($_POST['input_text3'])."',
            '".$conn->real_escape_string($_POST['input_select0'])."',
            NOW())";
    
    if ($conn->query($sql)=== TRUE) {
        echo "Eine neuer Speiseplan wurde erfolgreich erstellt";
    } else {
        echo "Beim Hinzufügen dieses Speiseplans ist ein Fehler aufgetreten : " . $conn->error;
    }
    $database->closeConnection();
}
?>