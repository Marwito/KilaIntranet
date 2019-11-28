<?php
if(isset($_POST['input_benutzername'], $_POST['input_vorname'], $_POST['input_name'], $_POST['input_position'], $_POST['input_telefon'], $_POST['input_mobil'], 
            $_POST['input_email'], $_POST['input_strasse'], $_POST['input_plz'], $_POST['input_ort'], $_POST['input_benutzerId'])){
    require_once('../../utilities/db_connection.php');
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    if (isset($_POST['input_aktiv'])) {
        $aktiv = 1;
    } else {
        $aktiv = 0;
    }
    
    if ($_POST['input_einrichtung_kueche'] != '') {
        // Leiter or Mitarbeiter or Caterer
        if ($_POST['input_position'] == 4 || $_POST['input_position'] == 5 || $_POST['input_position'] == 3) {
            if ($_POST['input_position'] == 3) {
                $einrichtung = 0;
                $kueche = $_POST['input_einrichtung_kueche'];
            } else {
                $einrichtung = $_POST['input_einrichtung_kueche'];
                $kueche = 0;
            }
        }
    } else {
        // Admin or Eltern
        $einrichtung = 0;
        $kueche = 0;
    }
    
    $sql = "UPDATE keis2_benutzer SET benutzername=
            '".$conn->real_escape_string($_POST['input_benutzername'])."', vorname=
        	'".$conn->real_escape_string($_POST['input_vorname'])."', name=
            '".$conn->real_escape_string($_POST['input_name'])."', position=
            '".$conn->real_escape_string($_POST['input_position'])."', einrichtung=
            '".$conn->real_escape_string($einrichtung)."', kueche = 
            '".$conn->real_escape_string($kueche)."', aktiv=
            ".$conn->real_escape_string($aktiv).", telefon=
            '".$conn->real_escape_string($_POST['input_telefon'])."', mobil=
            '".$conn->real_escape_string($_POST['input_mobil'])."', email=
            '".$conn->real_escape_string($_POST['input_email'])."', strasse=
            '".$conn->real_escape_string($_POST['input_strasse'])."', plz=
            '".$conn->real_escape_string($_POST['input_plz'])."', ort=
            '".$conn->real_escape_string($_POST['input_ort'])."'
            WHERE id=".$conn->real_escape_string($_POST['input_benutzerId']);
    
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