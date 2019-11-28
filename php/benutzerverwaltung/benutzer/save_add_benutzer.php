<?php
if(isset($_POST['input_text4'], $_POST['input_passwort0'], $_POST['input_text0'], $_POST['input_text1'], $_POST['input_text2'], $_POST['input_select0'])){
    require_once('../../utilities/db_connection.php');
    $passwort = password_hash($_POST['input_passwort0'], PASSWORD_ARGON2I);
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    if (isset($_POST['input_checkbox0'])) {
        $aktiv = 1;
    } else {
        $aktiv = 0;
    }
    
    if ($_POST['input_select1'] != '') {
        // Leiter or Mitarbeiter or Eltern or Caterer
        if ($_POST['input_select0'] == 4 || $_POST['input_select0'] == 5 || $_POST['input_select0'] == 3) {
            if ($_POST['input_select0'] == 3) {
                $einrichtung = 0;
                $kueche = $_POST['input_select1'];
            } else {
                $einrichtung = $_POST['input_select1'];
                $kueche = 0;
            } 
        } 
    } else {
        // Admin or Eltern
        $einrichtung = 0;
        $kueche = 0;
    }
    
    $sql = "INSERT INTO keis2_benutzer (benutzername, passwort, vorname, name,
            position, einrichtung, kueche, aktiv, telefon, mobil, email, strasse, 
            plz, ort, insert_date)
    		VALUES ('".$conn->real_escape_string($_POST['input_text0'])."',
            '".$conn->real_escape_string($passwort)."',
            '".$conn->real_escape_string($_POST['input_text1'])."',
            '".$conn->real_escape_string($_POST['input_text2'])."',
            '".$conn->real_escape_string($_POST['input_select0'])."',
            '".$conn->real_escape_string($einrichtung)."',
            '".$conn->real_escape_string($kueche)."',
            ".$conn->real_escape_string($aktiv).",
            '".$conn->real_escape_string($_POST['input_text4'])."',
            '".$conn->real_escape_string($_POST['input_text5'])."',
            '".$conn->real_escape_string($_POST['input_text6'])."',
            '".$conn->real_escape_string($_POST['input_text7'])."',
            '".$conn->real_escape_string($_POST['input_text8'])."',
            '".$conn->real_escape_string($_POST['input_text9'])."',
            NOW())";
    
    if ($conn->query($sql)=== TRUE) {
        echo "Eine neuer Benutzer wurde erfolgreich erstellt";
    } else {
        echo "Beim Hinzufügen dieses Benutzers ist ein Fehler aufgetreten : " . $conn->error;
    }
    $database->closeConnection();
} else {
    echo 'Formularvariablen sind ungültig oder werden nicht empfangen';
}
?>