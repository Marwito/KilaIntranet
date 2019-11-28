<?php 
class CustomFunctions {
    
    public function __construct() {
        // nothing
    }
    
    public function getNamePosition($id) {
        global $conn;
        $sql = "Select name FROM keis2_position WHERE id=
                ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    
    public function getNameEinrichtung($id) {
        global $conn;
        $sql = "Select name FROM keis2_einrichtung WHERE id=
                ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    
    public function getNameGruppe($id) {
        global $conn;
        $sql = "Select name FROM keis2_gruppe WHERE id=
                ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    
    public function getNameAktionsgruppe($id) {
        global $conn;
        $sql = "Select name FROM keis2_aktionsgruppe WHERE id=
                ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    
    public function getNameAmt($id) {
        global $conn;
        $sql = "Select name FROM keis2_amt WHERE id=
                ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    
    public function getNameKueche($id) {
        global $conn;
        $sql = "Select name FROM keis2_kueche WHERE id=
                ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    
    public function getNameEssenkategorie($id) {
        global $conn;
        $sql = "Select kategorie FROM keis2_essenkategorie WHERE id=
                ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['kategorie'];
    }
    
    public function getGruppenByPreiskategorie($preiskategorie) {
        global $conn;
        $groupArray = array();
        $sql = "Select name FROM keis2_gruppe WHERE id IN (SELECT gruppe_id
                FROM keis2_gruppe_preiskategorie WHERE preiskategorie_id = 
                ".$conn->real_escape_string($preiskategorie).")";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $name = $row['name'];
                $groupArray[] = $name;
            }
        }
        return $groupArray;
    }
    
    public function getGruppenByAktionsgruppe($aktionsgruppe) {
        global $conn;
        $groupArray = array();
        $sql = "Select name FROM keis2_gruppe WHERE id IN (SELECT id_gruppe
                FROM keis2_gruppe_aktionsgruppe WHERE id_aktionsgruppe =
                ".$conn->real_escape_string($aktionsgruppe).")";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $name = $row['name'];
                $groupArray[] = $name;
            }
        }
        return $groupArray;
    }
    
    public function getPreisOfEssenKategorie($essenkategorie, $kind_id) {
        global $conn;
        $sql = "Select preis FROM keis2_preiskategorie WHERE essenkategorie=
                '".$conn->real_escape_string($essenkategorie)."' AND
                gastkategorie = '".$conn->real_escape_string('Kinder')."' AND id
                IN (SELECT preiskategorie_id FROM keis2_gruppe_preiskategorie 
                WHERE gruppe_id = (SELECT zuordnung_gruppe
                FROM keis2_kind WHERE id = ".$conn->real_escape_string($kind_id)."))";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['preis'];
    }
    
    public function getIdOfPreisKategorie($essenkategorie, $kind_id) {
        global $conn;
        $sql = "Select id FROM keis2_preiskategorie WHERE essenkategorie=
                '".$conn->real_escape_string($essenkategorie)."' AND
                gastkategorie = '".$conn->real_escape_string('Kinder')."' AND id
                IN (SELECT preiskategorie_id FROM keis2_gruppe_preiskategorie
                WHERE gruppe_id = (SELECT zuordnung_gruppe
                FROM keis2_kind WHERE id = ".$conn->real_escape_string($kind_id)."))";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return 0;
        }
    }
    
    public function getBeitragszahlerName($id) {
        global $conn;
        $sql = "Select beitragszahler_vorname, beitragszahler_name 
                FROM keis2_kind WHERE eltern= ".$conn->real_escape_string($id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return ($row['beitragszahler_vorname'] . ' ' . $row['beitragszahler_name']);
    }
    
    public function check_but($kind_id) {
        global $conn;
        $query = "SELECT id FROM keis2_but WHERE kind =
                    ".$conn->real_escape_string($kind_id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function is_dauerbesteller($kind_id) {
        global $conn;
        $query = "SELECT essenkategorie FROM keis2_dauerbestellung WHERE kind =
                    ".$conn->real_escape_string($kind_id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return array(true, $row['essenkategorie']);
        } else {
            return array(false, 0);
        }
    }
    
    public function getNameKind($kind_id) {
        global $conn;
        $query = "SELECT vorname, name FROM keis2_kind WHERE id =
                    ".$conn->real_escape_string($kind_id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['name'] . ',' . ' ' . $row['vorname'];
        } else {
            return '';
        }
    }
    
    public function getNameBirthdayKind($kind_id) {
        global $conn;
        $query = "SELECT vorname, name, geburtsdatum FROM keis2_kind WHERE id =
                    ".$conn->real_escape_string($kind_id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return ($row['vorname'] . ' ' . $row['name'] . ', geb. ' . DateTime::createFromFormat('Y-m-d', $row['geburtsdatum'])->format('d.m.Y'));
        } else {
            return '';
        }
    }
    
    public function getNameAnsprechpartner($id) {
        global $conn;
        $query = "SELECT vorname, name FROM keis2_ansprechpartner WHERE id =
                    ".$conn->real_escape_string($id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['name'] . ',' . ' ' . $row['vorname'];
        } else {
            return '';
        }
    }
    
    public function getAdresseLRA($id) {
        global $conn;
        $query = "SELECT strasse, plz, ort FROM keis2_amt WHERE id =
                    ".$conn->real_escape_string($id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['strasse'] . '<br>' . $row['plz'] . ' ' . $row['ort'];
        } else {
            return '';
        }
    }
    
    public function getAllInfoEinrichtung($id) {
        global $conn;
        $query = "SELECT name, strasse, plz, ort FROM keis2_einrichtung WHERE id =
                    ".$conn->real_escape_string($id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['name'] . ', ' . $row['strasse'] . ', ' . $row['plz'] . ', ' . $row['ort'];
        } else {
            return '';
        }
    }
    
    public function getKindIdAndElternEigenanteil($but_id) {
        global $conn;
        $query = "SELECT kind, eigenanteil, eigenanteil_proMonat FROM keis2_but WHERE id =
                    ".$conn->real_escape_string($but_id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return array($row['eigenanteil'], $row['eigenanteil_proMonat'], $row['kind']);
        } else {
            return array(0, null, 0);
        }
    }
    
    public function getBenutzerIdMitKind($kind_id) {
        global $conn;
        $query = "SELECT eltern FROM keis2_kind WHERE id =
                    ".$conn->real_escape_string($kind_id)."";
        $result = $conn->query($query);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['eltern'];
        } else {
            return '';
        }
    }
    
    function check_exist_rechnungen($zeitraum_von, $zeitraum_bis) {
        global $conn;
        $query = "SELECT id FROM keis2_rechnung WHERE update_date IS NULL AND zeitraum_von =
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y') AND abgeschlossen = 1";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function check_exist_zeitraum($zeitraum_von, $zeitraum_bis) {
        global $conn;
        $query = "SELECT id FROM keis2_rechnung WHERE update_date IS NULL AND zeitraum_von =
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y')";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function check_overlapping_rechnungen($zeitraum_von, $zeitraum_bis) {
        global $conn;
        $query = "SELECT id FROM keis2_rechnung WHERE update_date IS NULL AND (zeitraum_von <=
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y') AND
                    zeitraum_bis >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') OR (zeitraum_bis
                    >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND zeitraum_von
                    <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y'))) ";

        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function IsZeitraumNeu($zeit_variable) {
        global $conn;
        $query = "SELECT id FROM keis2_rechnung WHERE
                    zeitraum_bis > STR_TO_DATE('".$conn->real_escape_string($zeit_variable)."', '%e.%c.%Y')
                    AND abgeschlossen = 0 AND update_date IS NULL";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    function check_unabgeschlossene_rechnungen($zeit_variable) {
        global $conn;
        $query = "SELECT id FROM keis2_rechnung WHERE
                    zeitraum_bis < STR_TO_DATE('".$conn->real_escape_string($zeit_variable)."', '%e.%c.%Y')
                    AND abgeschlossen = 0 AND update_date IS NULL";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function check_changes_bestellung($zeitraum_von, $zeitraum_bis) {
        global $conn;
        $query = "SELECT DISTINCT kind from keis2_bestellung WHERE datum between
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y') AND ((update_date IS NULL AND
                    insert_date > (SELECT insert_date FROM keis2_rechnung WHERE abgeschlossen = 1 AND
                    update_date IS NULL AND zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y')
                    ORDER BY insert_date DESC LIMIT 1))
                    OR (update_date > (SELECT insert_date FROM keis2_rechnung WHERE abgeschlossen = 1 AND
                    update_date IS NULL AND zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y')
                    ORDER BY insert_date DESC LIMIT 1)))";
        $result = $conn->query($query);
        return $result;
    }
    
    function check_changes_abbestellung($zeitraum_von, $zeitraum_bis) {
        global $conn;
        $query = "SELECT DISTINCT kind from keis2_abbestellung WHERE datum between
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y') AND ((update_date IS NULL AND
                    insert_date > (SELECT insert_date FROM keis2_rechnung WHERE abgeschlossen = 1 AND
                    update_date IS NULL AND zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y')
                    ORDER BY insert_date DESC LIMIT 1))
                    OR (update_date > (SELECT insert_date FROM keis2_rechnung WHERE abgeschlossen = 1 AND
                    update_date IS NULL AND zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y') AND
                    zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y')
                    ORDER BY insert_date DESC LIMIT 1)))";
        $result = $conn->query($query);
        return $result;
    }
    
    function get_but_info($kind_id) {
        global $conn;
        $query = "SELECT id, eigenanteil, aktenzeichen, eigenanteil_proEssen FROM keis2_but WHERE kind =
                ".$conn->real_escape_string($kind_id)."";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $but_id = $row['id'];
            $aktenzeichen = $row['aktenzeichen'];
            $eigenanteil = $row['eigenanteil'];
            
            if ($row['eigenanteil_proEssen'] == 1) {
                $eigenanteil_art = 1;
            } else {
                $eigenanteil_art = 0;
            }
            return array($but_id, $aktenzeichen, $eigenanteil, $eigenanteil_art);
        } else {
            return array(0, '');
        }
    }
    
    function get_amt_ansprechpartner($einrichtung_id) {
        global $conn;
        $query = "SELECT amt_id FROM keis2_einrichtung_amt WHERE
                    einrichtung_id = ".$conn->real_escape_string($einrichtung_id)."";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $amt_id = $row['amt_id'];
            $query = "SELECT id FROM keis2_ansprechpartner WHERE
                        amt_id = ".$conn->real_escape_string($amt_id)." AND rechnung = 1";
            $result = $conn->query($query);
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $amt_ansprechpartner_id = $row['id'];
                $result = array($amt_id, $amt_ansprechpartner_id);
            } else {
                $result = array($amt_id, 0);
            }
        } else {
            $result = array(0, 0);
        }
        return $result;
    }
    
    function getIdEinrichtungWithKid($kind_id) {
        global $conn;
        $sql = "Select zuordnung_einrichtung FROM keis2_kind WHERE id=
                ".$conn->real_escape_string($kind_id)."";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['zuordnung_einrichtung'];
    }
    
    function monthsDifference($date1, $date2) {
        $interval = $date1->diff($date2);
        return $interval->format('%m');
    }
}
?>