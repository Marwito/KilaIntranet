<?php
require_once('../../utilities/db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
if (isset($_POST['amt'])) {
    $statement = $conn->prepare("SELECT id FROM keis2_ansprechpartner_amt
            WHERE rechnung = ? AND amt = ?");
    $rechnung = 1;
    $statement->bind_param('ii', $rechnung, $_POST['amt']);
    $statement->execute();
    $result = $statement->get_result();
    if ($result->num_rows > 0) {
        echo 'exist';
    } else {
        echo 'no';
    }
    $statement->close();
    $database->closeConnection();
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}
?>