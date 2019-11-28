<?php
require_once('../../login/session.php');
$session = Session::getInstance();
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$einrichtungArray = array();
$condition = '';
$sql = "SELECT id, name FROM keis2_einrichtung";
if (isset($_POST['amt'])) {
    $condition .= " WHERE id NOT IN (SELECT einrichtung_id FROM keis2_einrichtung_amt 
                    WHERE amt_id = ".$conn->real_escape_string($_POST['amt']).")";
} elseif (isset($_POST['kueche'])) {
    $condition .= " WHERE id NOT IN (SELECT einrichtung_id FROM keis2_einrichtung_kueche 
                    WHERE kueche_id = ".$conn->real_escape_string($_POST['kueche']).")";
} elseif (isset($_POST['value'])) {
    if ($_POST['value'] == '0') {
        $condition .= " WHERE id = (SELECT einrichtung FROM keis2_benutzer 
                        WHERE benutzername = '".$conn->real_escape_string($session->username)."')";
    }
} elseif (isset($_POST['kuechenId'])) {
    $condition .= " WHERE id IN (SELECT einrichtung_id FROM keis2_einrichtung_kueche
                    WHERE kueche_id = ".$conn->real_escape_string($_POST['kuechenId']).")";
} else {
    $condition = '';
}
$sql .= $condition;
$result = $conn->query($sql);
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
        $id = $row['id'];
        $name = $row['name'];
        $einrichtungArray[] = array("id" => $id, "name" => $name);
    }
}
$database->closeConnection();
// encoding array to json format
echo json_encode($einrichtungArray)
?>