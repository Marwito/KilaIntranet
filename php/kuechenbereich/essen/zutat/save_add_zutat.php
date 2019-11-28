<?php
if(isset($_POST['input_text0'], $_POST['input_select0'])){
    require_once('../../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $sql = "INSERT INTO keis2_zutat (name, einheit)
    		VALUES ('".$conn->real_escape_string($_POST['input_text0'])."',
            '".$conn->real_escape_string($_POST['input_select0'])."')";
    
    if ($conn->query($sql)=== TRUE) {
        echo "Eine neue Zutat wurde erfolgreich erstellt";
    } else {
        echo "Beim Hinzufügen dieser Zutat ist ein Fehler aufgetreten : " . $conn->error;
    }
    $database->closeConnection();
}
?>