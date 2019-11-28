<?php
require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')) {
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!($benutzer->isAdmin($session->usergroup) || $benutzer->isEltern($session->usergroup))) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        if (isset($_GET['id'])) {
            require_once('../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            require_once('../utilities/functions.php');
            $customFunction = new CustomFunctions();
            $rechnung_id = $_GET['id'];
            // retrieve the rechnung information
            $sql = "SELECT * FROM keis2_rechnung WHERE id = 
                    ".$conn->real_escape_string($rechnung_id)."";
            $result = $conn->query($sql);
            if ($result->num_rows == 1) {
                // get the common information for a normal or BUT rechnung
                $row_rechnung = $result->fetch_assoc();
                setlocale(LC_TIME, 'de');

                $von = DateTime::createFromFormat('Y-m-d', $row_rechnung['zeitraum_von']);
                $bis = DateTime::createFromFormat('Y-m-d', $row_rechnung['zeitraum_bis']);
                $start_monat = $von->format('m');
                $end_monat = $bis->format('m');
                $start_jahr = $von->format('Y');
                $end_jahr = $von->format('Y');
                
                if ($start_monat == $end_monat) {
                    if ($start_jahr == $end_jahr) {
                        $monat_jahr = utf8_encode(strftime('%B', mktime(0, 0, 0, $start_monat, 10))) . ' ' . $start_jahr;
                    } else {
                        $monat_jahr = $von->format('d.m.Y') . ' - ' . $bis->format('d.m.Y');
                    }
                } else {
                    $monat_jahr = $von->format('d.m.Y') . ' - ' . $bis->format('d.m.Y');
                }
                
                $zeit_variable1 = $von->format('j.n.Y');
                $zeit_variable2 = $bis->format('j.n.Y');
                
                $months_difference = $customFunction->monthsDifference($von, $bis) + 1;
                
                $rechnungsnummer = $row_rechnung['rechnungsnummer'];
                $gesamtbetrag= $row_rechnung['gesamtbetrag'];
                $datum = DateTime::createFromFormat('Y-m-d', $row_rechnung['rechnungsdatum'])->format('d.m.Y');
                $but_id = $row_rechnung['but_id'];
                // retrieve the rechnungspositionen of the rechnung
                $sql = "SELECT * FROM keis2_rechnungspositionen WHERE id_rechnung =
                        ".$conn->real_escape_string($rechnung_id)."";
                $result_rechnungspositionen = $conn->query($sql);
                
                // start displaying the invoice information : Kinderland
                echo '<strong>Kinderland PLUS Gmbh</strong> - Margeritenstraße 9 - 85586 Poing';
                
                if ($but_id == null) {
                    // normal Rechnung
                    $benutzer_id = $row_rechnung['benutzer_id'];
                    // retrieve information from the kind table
                    $sql = "SELECT * FROM keis2_kind WHERE eltern =
                            ".$conn->real_escape_string($benutzer_id)."";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $row_kind = $result->fetch_assoc();
                        // get information about the beitragszahler
                        $anrede = $row_kind['beitragszahler_anrede'];
                        $vorname = $row_kind['beitragszahler_vorname'];
                        $name = $row_kind['beitragszahler_name'];
                        $strasse = $row_kind['strasse'];
                        $plz = $row_kind['plz'];
                        $ort = $row_kind['ort'];

                        // displaying the rest of the invoice information
                        echo '<br><br>'. $anrede . ' ' . $vorname . ' ' . $name;
                        echo '<br>'. $strasse;
                        echo '<br>' . $plz . ' ' . $ort;
                        echo '<h3> Rechnung - Essenbestellung ' . $monat_jahr . '</h3>';
                        echo 'Datum : ' . $datum;
                        echo '<br>Rech. Nr. : ' . $rechnungsnummer;
                        echo '<br><br>';
                        echo "<table><tr><td width='50'>Pos.</td><td width='150'>Kind</td><td width='150'>Essenkategorie</td><td width='150'>Menge</td><td width='150'>Gesamt</td></tr>";
                        $i = 1;
                        while($row_rechnungsposition = $result_rechnungspositionen->fetch_assoc()) {
                            $is_dauerbesteller = $customFunction->is_dauerbesteller($row_rechnungsposition['kind_id']);
                            if ($is_dauerbesteller[0] == true) {
                                $essenkategorie = $customFunction->getNameEssenkategorie($row_rechnungsposition['essenskategorie']);
                                if ($customFunction->check_but($row_rechnungsposition['kind_id'])) {
                                    $but_info = $customFunction->get_but_info($row_rechnungsposition['kind_id']);
                                    $eigenanteil_art = $but_info[3];
                                    $but = 1;
                                } else {
                                    $but = 0;
                                }
                                echo '<tr>';
                                echo '<td>'. $i .'</td>';
                                echo '<td>'. $customFunction->getNameKind($row_rechnungsposition['kind_id']) .'</td>';
                                if($but && $eigenanteil_art == 0) {
                                    echo '<td>Eigenbeitrag pro Monat</td>';
                                    echo '<td>1</td>';
                                    echo '<td>' . number_format($but_info[2], 2, ',', '') . ' €</td>';
                                } else {
                                    echo '<td>'. $essenkategorie . '</td>';
                                    echo '<td>' . $row_rechnungsposition['menge']. '</td>';
                                    echo '<td>' . number_format($row_rechnungsposition['gesamtpreis'], 2, ',', '') . ' €</td>';
                                }
                                echo '</tr>';
                            } else {
                                $array = compute_menge_preis_essen_kategorien($row_rechnungsposition['kind_id'], $zeit_variable1, $zeit_variable2);
                                foreach ($array as $value) {
                                    if ($customFunction->check_but($row_rechnungsposition['kind_id'])) {
                                        $but_info = $customFunction->get_but_info($row_rechnungsposition['kind_id']);
                                        $eigenanteil_art = $but_info[3];
                                        $preis = $but_info[2] * $value[0];
                                        $but = 1;
                                    } else {
                                        $preis = $customFunction->getPreisOfEssenKategorie($value[1], $row_rechnungsposition['kind_id']) * $value[0];
                                        $but = 0;
                                    }
                                    if($but && !$eigenanteil_art) {
                                        echo '<tr>';
                                        echo '<td>'. $i .'</td>';
                                        echo '<td>'. $customFunction->getNameKind($row_rechnungsposition['kind_id']) .'</td>';
                                        echo '<td>Eigenbeitrag pro Monat</td>';
                                        echo '<td>1</td>';
                                        echo '<td>' . number_format($preis, 2, ',', '') . ' €</td>';
                                        echo '</tr>';
                                        break;
                                    } else {
                                        echo '<tr>';
                                        echo '<td>'. $i .'</td>';
                                        echo '<td>'. $customFunction->getNameKind($row_rechnungsposition['kind_id']) .'</td>';
                                        echo '<td>'. $customFunction->getNameEssenkategorie($value[1]) . '</td>';
                                        echo '<td>' . $value[0]. '</td>';
                                        echo '<td>' . number_format($preis, 2, ',', '') . ' €</td>';
                                        echo '</tr>';
                                    }
                                    
                                }
                            }
                            $i++;
                        }
                        echo '</table>';
                        echo '<br>';
                        echo '<table>';
                        echo '<tr>';
                        echo "<td width='50'></td><td width='150'></td><td width='150'></td><td width='150' style='text-align:right'><strong>Endsumme:</strong></td>";
                        echo "<td width='150'><strong>" . number_format($gesamtbetrag, 2, ',', '') . " €</strong></td>";
                        echo '</tr>';
                        echo '</table>';
                    } else {
                        echo 'Für diese Eltern wurden keine Kinder gefunden';
                    }
                } else {
                    // BUT Rechnung
                    $anrede = '';
                    $ansprechpartner_name = $customFunction->getNameAnsprechpartner($row_rechnung['amt_ansprechpartner_id']);
                    $address = $customFunction->getAdresseLRA($row_rechnung['amt_id']);
                    $einrichtung = $customFunction->getAllInfoEinrichtung($row_rechnung['einrichtung_id']);
                    $zeitraum_von = $von->format('d.m.Y');
                    $zeitraum_bis = $bis->format('d.m.Y');
                    $aktenzeichen = $row_rechnung['aktenzeichen'];
                    $verwendungszweck = $row_rechnung['verwendungszweck'];
                    $anzahlEssen = $row_rechnung['anzahlEssen'];
                    
                    // get the kind_id
                    $row_rechnungsposition = $result_rechnungspositionen->fetch_assoc();
                    
                    $kind_id_elternTeil_isproMonat = $customFunction->getKindIdAndElternEigenanteil($but_id);
                    $kind_id = $kind_id_elternTeil_isproMonat[2];
                    if ($kind_id_elternTeil_isproMonat[1] != null) {
                        $eltern_teil = $kind_id_elternTeil_isproMonat[0];
                        if ($kind_id_elternTeil_isproMonat[1] == 0) {
                            $info = 'Pro Essen';
                            $abzuziehender_elternanteil = $anzahlEssen * $eltern_teil;
                            $teil = $abzuziehender_elternanteil;
                        } else {
                            $info = 'Pro Kind';
                            $abzuziehender_elternanteil = $eltern_teil * $months_difference;
                            $teil = $eltern_teil;
                        }
                    } else {
                        $eltern_teil = 0;
                        $abzuziehender_elternanteil = 0;
                    }
                    
                    // displaying the rest of the invoice information
                    echo '<br><br>'. $anrede . ' ' . $ansprechpartner_name;
                    echo '<br>'. $address;
                    echo '<h3> Rechnung Nr. ' . $rechnungsnummer . '</h3>';
                    echo 'Datum : ' . $datum;
                    echo '<br>Für Mittagessen in der Einrichtung :';
                    echo '<br><br><strong>' . $einrichtung . '</strong><br>';
                    echo '<br>Zeitraum vom <strong>' . $zeitraum_von . '</strong> bis <strong>' . $zeitraum_bis . '</strong>';
                    echo '<br>Kind : <strong> ' . $customFunction->getNameBirthdayKind($kind_id) . '</strong>';
                    echo '<br>Aktenzeichen : <strong> ' . $aktenzeichen . '</strong>';
                    echo '<br>';
                    show_essen_und_preis_kategorien($kind_id, $zeit_variable1, $zeit_variable2, $anzahlEssen);
                    echo '<br>';
                    echo "<table>";
                    echo "<tr><td width='200'>Monat/Jahr</td><td width='200'>Anzahl der Essen</td><td width='200'>Gesamtbetrag</td></tr>";
                    $gesamtAnzahlEssen_zwischensumme = compute_gesamtAnzahlEssen_zwischensumme($rechnung_id, $teil);
                    $gesamtAnzahlEssen = $gesamtAnzahlEssen_zwischensumme[0];
                    $zwischensumme = $gesamtAnzahlEssen_zwischensumme[1];
                    echo '</table>';
                    echo '<br>';
                    echo '<table>';
                    echo '<tr>';
                    echo "<td width='200'></td><td width='200' style='text-align:right'>Zwischensumme:</td>";
                    echo "<td width='200'>" . number_format($zwischensumme, 2, ',', '') . " €</td>";
                    echo '</tr>';
                    echo '</table>';
                    echo '<br>';
                    echo '<table>';
                    echo "<tr><td width='200'>Eigenanteil Eltern(" .$info.")</td><td width='200'>Gesamtanzahl der Essen</td><td width='200'>abzuziehender Elternanteil</td></tr>";
                    echo "<tr><td>" . number_format($eltern_teil, 2, ',', '') . " €</td><td>" . $gesamtAnzahlEssen . "</td><td>" . number_format($abzuziehender_elternanteil, 2, ',', '') . " €</td></tr>";
                    echo '<tr>';
                    echo '</table>';
                    echo '<br>';
                    echo '<table>';
                    echo "<tr><td width='200'></td><td width='200' style='text-align:right'><strong>Rechnungsbetrag:</strong></td>";
                    echo "<td width='200'><strong>" . number_format($zwischensumme - $abzuziehender_elternanteil, 2, ',', '') . " €</strong></td></tr>";
                    echo '</table>';
                    echo '<br>Verwendungszweck : ' . $verwendungszweck . '</strong>';
                }
            } else {
                echo 'Rechnung nicht gefunden';
            }
            $database->closeConnection();
        } else {
            echo '$_GET-variable ist ungültig oder wird nicht empfangen';
        }
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}

function compute_gesamtAnzahlEssen_zwischensumme($rechnung_id, $abzuziehender_elternanteil) {
    global $conn;
    $sql = "SELECT * FROM keis2_rechnungspositionen WHERE id_rechnung = 
            ".$conn->real_escape_string($rechnung_id)."";
    $result = $conn->query($sql);
    $gesamtAnzahlEssen = 0;
    $zwischensumme = 0;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $gesamtAnzahlEssen += $row['menge'];
            $monat_jahr = $row['monat_jahr'];
            $menge = $row['menge'];
            if ($menge > 0) {
                $gesamtpreis = $row['gesamtpreis'] + $abzuziehender_elternanteil;
            } else {
                $gesamtpreis = 0;
            }
            echo "<tr><td>" . $monat_jahr . "</td><td>" . $menge . "</td><td>" . number_format($gesamtpreis, 2, ',', '') . " €</td></tr>";
            $zwischensumme += $gesamtpreis;
        }
    } else {
        echo "<tr><td> -- </td><td> -- </td><td> 0 €</td></tr>"; 
    } 
    return array($gesamtAnzahlEssen, $zwischensumme);
}

function compute_menge_preis_essen_kategorien($kind_id, $zeit_variable1, $zeit_variable2) {
    global $conn;
    
    $sql = "SELECT count(*) as total, essenkategorie FROM keis2_bestellung WHERE datum between
            STR_TO_DATE('".$conn->real_escape_string($zeit_variable1)."', '%e.%c.%Y') AND
            STR_TO_DATE('".$conn->real_escape_string($zeit_variable2)."', '%e.%c.%Y')
            AND kind=".$conn->real_escape_string($kind_id)." AND update_date
            IS NULL GROUP BY essenkategorie";
    $result_essenkategorien = $conn->query($sql);
    if ($result_essenkategorien->num_rows > 0) {
        $row = $result_essenkategorien->fetch_all(MYSQLI_NUM);
        return $row;
    } else {
        return array();
    }
}

function show_essen_und_preis_kategorien($kind_id, $zeit_variable1, $zeit_variable2, $anzahlEssen) {
    global $conn;
    global $customFunction;
    
    $first_day = DateTime::createFromFormat('j.n.Y', $zeit_variable1)->format('d.m.Y');
    $last_day = DateTime::createFromFormat('j.n.Y', $zeit_variable2)->format('d.m.Y');
    
    $check_dauerbestellung = $customFunction->is_dauerbesteller($kind_id);
    echo '<br>von ' . $first_day . ' bis ' . $last_day;
    echo "<table><tr><td>Essenkategorie</td><td>Einzelpreis pro Essen</td></tr>";
    
    if ($check_dauerbestellung[0] == true) {
        $essen_kategorie = $anzahlEssen . 'x ' .$customFunction->getNameEssenkategorie($check_dauerbestellung[1]);
        $preis_kategorie = $customFunction->getPreisOfEssenKategorie($check_dauerbestellung[1], $kind_id);
        echo "<tr><td>" . $essen_kategorie . "</td><td>" . number_format($preis_kategorie, 2, ',', '') ." €</td></tr>";
    } else {
        $array = compute_menge_preis_essen_kategorien($kind_id, $zeit_variable1, $zeit_variable2);
        foreach ($array as $value) {
            $essen_kategorie = $value[0] . 'x ' . $customFunction->getNameEssenkategorie($value[1]);
            $preis_kategorie = $customFunction->getPreisOfEssenKategorie($value[1], $kind_id);
            echo "<tr><td>" . $essen_kategorie . "</td><td>" . number_format($preis_kategorie, 2, ',', '') ." €</td></tr>";
        }
    }
    echo "</table>";
}