<?php
if(isset($_POST['input_text0'], $_POST['input_select0'], $_POST['input_text1'], $_POST['input_select1'], $_POST['input_select2'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "INSERT INTO keis2_rezept (name, zutat, menge, essenkategorie, speisenart)
    		VALUES ('".$conn->real_escape_string($_POST['input_text0'])."',
            '".$conn->real_escape_string($_POST['input_select0'])."',
            '".$conn->real_escape_string($_POST['input_text1'])."',
            '".$conn->real_escape_string($_POST['input_select1'])."',
            '".$conn->real_escape_string($_POST['input_select2'])."')";
    
    if ($conn->query($sql)=== TRUE) {
        echo "Ein neues Rezept wurde erfolgreich erstellt";
    } else {
        echo "Beim Hinzufügen dieses Rezeptes ist ein Fehler aufgetreten : " . $conn->error;
    }
    $database->closeConnection();
}
?>