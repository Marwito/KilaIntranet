<?php
require_once('../db_connection.php');
$database = new DatabaseConnection();
$conn = $database->getConn();
$condition = '';
$sql = "SELECT id, name, einrichtung FROM keis2_gruppe"; //Einrichtung wird in edit_kind.php gebraucht!

if (isset($_POST['preiskategorie'])) {
    $condition .= " WHERE id NOT IN (SELECT gruppe_id FROM keis2_gruppe_preiskategorie 
                    WHERE preiskategorie_id = 
                    ".$conn->real_escape_string($_POST['preiskategorie']).")";
}
if(isset($_POST['einrichtung']) && $_POST['einrichtung'] != -1) {
    $condition .= " WHERE einrichtung='".$conn->real_escape_string($_POST['einrichtung'])."'";
}
$sql .= $condition;
$result = $conn->query($sql);
$gruppenArray = array();
while($row = $result->fetch_assoc()){
    $id = $row['id'];
    $name = $row['name'];
    $einrichtung = $row['einrichtung'];
    $gruppenArray[] = array("id" => $id, "name" => $name, "einrichtung" => $einrichtung);
}
// encoding array to json format
echo json_encode($gruppenArray);
$database->closeConnection();
?>