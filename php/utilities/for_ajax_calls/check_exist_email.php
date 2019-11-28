<?php
if (isset($_POST['email'])) {
    require_once('../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    $statement = $conn->prepare("SELECT id FROM keis2_benutzer WHERE email = ?");
    $statement->bind_param('s', $_POST['email']);
    $statement->execute();
    $result = $statement->get_result();
    if ($result->num_rows > 0) {
        echo 'exist';
    } else {
        echo 'no success';
    }
    $statement->close();
    $database->closeConnection();
} else {
    echo 'Formularvariable ist ungültig oder wird nicht empfangen';
}
?>