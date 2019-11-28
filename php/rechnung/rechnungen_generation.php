<?php
try {
    require_once('../login/session.php');
    require_once('../utilities/constants.php');
    require_once('../utilities/functions.php');
    $customFunction = new CustomFunctions();
    $session = Session::getInstance();
    if($session->checkSessionVariables('username', 'usergroup')) {
        require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
        $benutzer = new Benutzer();
        if (!($benutzer->isAdmin($session->usergroup))) {
            header('Location: ' . Constants::getBaseURL());
        } else {
            require_once('../utilities/db_connection.php');
            $database = new DatabaseConnection();
            $conn = $database->getConn();
            
            if (isset($_POST['input_select0'], $_POST['input_text0'], $_POST['input_text1'], $_POST['input_text2'], $_POST['input_text3'])) {
                
                // formatting the zeitraum inputs
                if ($_POST['input_select0'] != '' && $_POST['input_text0'] != '') {
                    $monat = $_POST['input_select0']; // monat
                    $jahr = $_POST['input_text0']; // jahr
                    $daysNumber = cal_days_in_month(CAL_GREGORIAN, $monat, $jahr);
                    $zeit_variable1 = '1' . '.' . $monat . '.' . $jahr;
                    $zeit_variable2 = $daysNumber . '.' . $monat . '.' . $jahr;
                } else {
                    $zeit_variable1 = DateTime::createFromFormat('d.m.Y', $_POST['input_text1'])->format('j.n.Y');
                    $zeit_variable2 = DateTime::createFromFormat('d.m.Y', $_POST['input_text2'])->format('j.n.Y');
                }
                
                // Set timezone to Europe/Berlin
                date_default_timezone_set('Europe/Berlin');
                $rechnungsnummer_part = date("Ym");
                $verwendungszweck = $_POST['input_text3'];
                $i = 1;
                // check if there are previous invoices with the same Zeitraum
                if ($customFunction->check_exist_rechnungen($zeit_variable1, $zeit_variable2)) {
                    // check if there are some changes in Bestellungen
                    if (!empty($customFunction->check_changes_bestellung($zeit_variable1, $zeit_variable2)) || !empty($customFunction->check_changes_abbestellung($zeit_variable1, $zeit_variable2))) {
                        $array_kind = array();
                        
                        // get list of kids whose orders have changed
                        $result = $customFunction->check_changes_bestellung($zeit_variable1, $zeit_variable2);
                        while($row = $result->fetch_assoc()) {
                            if (!in_array($row['kind'], $array_kind)) {
                                $array_kind[] = $row['kind'];
                            }
                        }
                        
                        $result = $customFunction->check_changes_abbestellung($zeit_variable1, $zeit_variable2);
                        while($row = $result->fetch_assoc()) {
                            if (!in_array($row['kind'], $array_kind)) {
                                $array_kind[] = $row['kind'];
                            }
                        }
                        
                        $array_benutzer_but = array();
                        $array_benutzer = array();
                        for ($x = 0; $x < count($array_kind); $x++) {
                            if ($customFunction->check_but($array_kind[$x])) {
                                $query = "SELECT id FROM keis2_rechnung WHERE zeitraum_von =
                                            STR_TO_DATE('".$conn->real_escape_string($zeit_variable1)."', '%e.%c.%Y')
                                            AND zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeit_variable2)."', '%e.%c.%Y')
                                            AND abgeschlossen = 1 AND update_date IS NULL AND but_id = (SELECT id FROM keis2_but WHERE kind =
                                            ".$conn->real_escape_string($array_kind[$x]).")";
                                $result = $conn->query($query);
                                if ($result->num_rows == 1) {
                                    $row = $result->fetch_assoc();
                                    // get the ID of the BUT invoice to be reset later
                                    $old_but_rechnung_id = $row['id'];
                                    // get all necessary information to save Rechnungen and Rechnungspositionen
                                    $berechnung = menge_and_gesamt_berechnung($array_kind[$x], $zeit_variable1, $zeit_variable2);
                                    $menge = $berechnung[0];
                                    $gesamt = $berechnung[1];
                                    $essenkategorie = $berechnung[2];
                                    $preiskategorie = $berechnung[3];
                                    $is_BUT = $berechnung[4];
                                    
                                    if ($menge > 0) {
                                        
                                        // get all necessary information
                                        $einrichtung_id = $customFunction->getIdEinrichtungWithKid($array_kind[$x]);
                                        $result = $customFunction->get_but_info($array_kind[$x]);
                                        $but_id = $result[0];
                                        $aktenzeichen = $result[1];
                                        $eigenanteil = $result[2];
                                        $eigenanteil_art = $result[3];
                                        if ($eigenanteil_art == 1) {
                                            $differenz = $gesamt - ($menge * $eigenanteil);
                                        } else {
                                            $differenz = $gesamt - $eigenanteil;
                                        }
                                        $amt_ansprechpartner = $customFunction->get_amt_ansprechpartner($einrichtung_id);
                                        $amt_id = $amt_ansprechpartner[0];
                                        $amt_ansprechpartner_id = $amt_ansprechpartner[1];
                                        $rechnungsnummer = $rechnungsnummer_part . $i;
                                        $benutzer_id = $customFunction->getBenutzerIdMitKind($array_kind[$x]);
                                        if (!in_array($benutzer_id, $array_benutzer_but)) {
                                            $array_benutzer_but[] = $benutzer_id;
                                        }
                                        
                                        // save the new BUT rechnung
                                        $but_rechnung_id = save_but_rechnung($rechnungsnummer, $amt_id, $amt_ansprechpartner_id, $einrichtung_id,
                                            $aktenzeichen, $but_id, $differenz, $menge, $zeit_variable1, $zeit_variable2, $verwendungszweck, $old_but_rechnung_id, 1);
                                        
                                        // Berechnung und Speicherung der BUT- Rechnungspositionen
                                        berechnung_rechnungsposition($is_BUT, $zeit_variable1, $zeit_variable2, $array_kind[$x], $but_rechnung_id,0, $essenkategorie,
                                            $preiskategorie, $eigenanteil, $eigenanteil_art);
                                        $i++;
                                        
                                        // reset BUT invoice
                                        reset_abgeschlossene_rechnungen($old_but_rechnung_id);
                                    }
                                } else {
                                    echo 'Die alte BUT Rechnung wurde nicht gefunden!';
                                }
                            }
                            $benutzer_id = $customFunction->getBenutzerIdMitKind($array_kind[$x]);
                            if (!in_array($benutzer_id, $array_benutzer)) {
                                $array_benutzer[] = $benutzer_id;
                                $query = "SELECT id FROM keis2_rechnung WHERE zeitraum_von =
                                                STR_TO_DATE('".$conn->real_escape_string($zeit_variable1)."', '%e.%c.%Y')
                                                AND zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeit_variable2)."', '%e.%c.%Y')
                                                AND abgeschlossen = 1 AND update_date IS NULL AND benutzer_id = ".$conn->real_escape_string($benutzer_id)."";
                                $result = $conn->query($query);
                                if ($result->num_rows == 1) {
                                    $row = $result->fetch_assoc();
                                    // get the ID of the normal invoice to be reset later
                                    $old_normal_rechnung_id = $row['id'];
                                    
                                    $rechnungsnummer = $rechnungsnummer_part . $i;
                                    // save the new normal rechnung
                                    $normal_rechnung_id = save_normal_rechnung($rechnungsnummer, $benutzer_id, $zeit_variable1, $zeit_variable2, $old_normal_rechnung_id, 1);
                                    $i++;
                                    
                                    // get list of kids with their siblings
                                    $query = "SELECT id FROM keis2_kind WHERE eltern = ".$conn->real_escape_string($benutzer_id)."";
                                    $result = $conn->query($query);
                                    if ($result->num_rows > 0) {
                                        $array_kids_plus_siblings = array();
                                        while ($row = $result->fetch_assoc()) {
                                            $array_kids_plus_siblings[] = $row['id'];
                                        }
                                        for ($y = 0; $y < count($array_kids_plus_siblings); $y++) {
                                            $berechnung = menge_and_gesamt_berechnung($array_kids_plus_siblings[$y], $zeit_variable1, $zeit_variable2);
                                            $menge = $berechnung[0];
                                            $gesamt = $berechnung[1];
                                            $essenkategorie = $berechnung[2];
                                            $preiskategorie = $berechnung[3];
                                            $is_BUT = $berechnung[4];
                                            if($is_BUT) {
                                                $resultBuT = $customFunction->get_but_info($array_kids_plus_siblings[$y]);
                                                $but_id = $resultBuT[0];
                                                $aktenzeichen = $resultBuT[1];
                                                $eigenanteil = $resultBuT[2];
                                                $eigenanteil_art = $resultBuT[3];
                                            }
                                            if ($menge > 0) {
                                                // Berechnung und Speicherung der BUT- Rechnungspositionen
                                                berechnung_rechnungsposition($is_BUT, $zeit_variable1, $zeit_variable2, $array_kids_plus_siblings[$y], 0,$normal_rechnung_id, $essenkategorie,
                                                    $preiskategorie, $eigenanteil, $eigenanteil_art);
                                            }
                                        }
                                        // update the normal rechnung after counting the total cost
                                        $sqlPreis = "SELECT SUM(gesamtpreis) AS summe FROM keis2_rechnungspositionen WHERE id_rechnung=".$conn->real_escape_string($normal_rechnung_id);
                                        $resultPreis = $conn->query($sqlPreis);
                                        $row = $resultPreis->fetch_assoc();
                                        
                                        update_normal_rechnung($normal_rechnung_id, $row['summe']);
                                        
                                        // reset normal invoice
                                        reset_abgeschlossene_rechnungen($old_normal_rechnung_id);
                                        
                                    } else {
                                        echo 'beim Abrufen der Liste der Kinder mit ihren Geschwistern wurden keine Ergebnisse gefunden';
                                    }
                                } else {
                                    echo 'Die alte normale Rechnung wurde nicht gefunden!';
                                }
                            }
                        }
                    } else {
                        echo 'es wird kein Rechnungslauf benötigt, da bei Bestellungen oder Abbestellungen für diesen Zeitraum keine Änderungen festgestellt wurden!';
                    }
                } else {
                    reset_rechnungen($zeit_variable1, $zeit_variable2);
                    rechnungslauf_ausfuehren($zeit_variable1, $zeit_variable2, $rechnungsnummer_part, $verwendungszweck);
                }
            } else {
                throw new Exception( 'Formularvariablen sind ungültig oder wird nicht empfangen');
            }
            $database->closeConnection();
        }
    } else {
        header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
    }
} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}

function rechnungslauf_ausfuehren($zeit_variable1, $zeit_variable2, $rechnungsnummer_part, $verwendungszweck) {
    global $conn;
    global $customFunction;
    global $i;
    
    // select the users who are parents
    $sql_eltern = "SELECT * FROM keis2_benutzer WHERE position = (SELECT id FROM keis2_position WHERE name = '".$conn->real_escape_string('Eltern')."')";
    $result_eltern = $conn->query($sql_eltern);
    if ($result_eltern->num_rows > 0) {
        // loop over the users list
        while ($row_eltern = $result_eltern->fetch_assoc()) {
            $eltern_id = $row_eltern['id'];
            $normal_rechnung_id = null;
            $end_summe = 0;
            // select the kids of each user
            $sql_kind = "SELECT * FROM keis2_kind WHERE eltern =
                    ".$conn->real_escape_string($eltern_id)."";
            $result_kind = $conn->query($sql_kind);
            if ($result_kind->num_rows > 0) {
                // loop over the kids list
                while ($row_kind = $result_kind->fetch_assoc()) {
                    $kind_id = $row_kind['id'];
                    $einrichtung_id = $row_kind['zuordnung_einrichtung'];
                    $berechnung = menge_and_gesamt_berechnung($kind_id, $zeit_variable1, $zeit_variable2);
                    $menge = $berechnung[0];
                    $gesamt = $berechnung[1];
                    $essenkategorie = $berechnung[2];
                    $preiskategorie = $berechnung[3];
                    $is_BUT = $berechnung[4];
                    $rechnungsnummer = $rechnungsnummer_part . $i;
                    
                    if ($is_BUT == true) {
                        if ($menge > 0) {
                            // there is a BUT support for this kid
                            $result = $customFunction->get_but_info($kind_id);
                            $but_id = $result[0];
                            $aktenzeichen = $result[1];
                            $eigenanteil = $result[2];
                            $eigenanteil_art = $result[3];
                            $von = DateTime::createFromFormat('j.n.Y', $zeit_variable1);
                            $bis = DateTime::createFromFormat('j.n.Y', $zeit_variable2);
                            $months_difference = $customFunction->monthsDifference($von, $bis) + 1;
                            if ($eigenanteil_art == 1) {
                                $differenz = $gesamt - ($menge * $eigenanteil);
                            } else {
                                $differenz = $gesamt - ($eigenanteil * $months_difference);
                            }
                            
                            $amt_ansprechpartner = $customFunction->get_amt_ansprechpartner($einrichtung_id);
                            $amt_id = $amt_ansprechpartner[0];
                            $amt_ansprechpartner_id = $amt_ansprechpartner[1];
                            
                            // create a BUT Rechnung
                            $but_rechnung_id = save_but_rechnung($rechnungsnummer, $amt_id, $amt_ansprechpartner_id, $einrichtung_id,
                                $aktenzeichen, $but_id, $differenz, $menge, $zeit_variable1, $zeit_variable2, $verwendungszweck, 0);
                            
                            // create a normal Rechnung
                            if ($normal_rechnung_id == null) {
                                $normal_rechnung_id = save_normal_rechnung($rechnungsnummer, $eltern_id, $zeit_variable1, $zeit_variable2, 0);
                            }
                            
                            // Berechnung und Speicherung der BUT- Rechnungspositionen
                            $end_summe += berechnung_rechnungsposition($is_BUT, $zeit_variable1, $zeit_variable2, $kind_id, $but_rechnung_id, $normal_rechnung_id, $essenkategorie, $preiskategorie, $eigenanteil, $eigenanteil_art);
                            $i++;
                        }
                    } else {
                        // No BUT support
                        if ($menge > 0) {
                            
                            // create a normal Rechnung
                            if ($normal_rechnung_id == null) {
                                $normal_rechnung_id = save_normal_rechnung($rechnungsnummer, $eltern_id, $zeit_variable1, $zeit_variable2, 0);
                            }
                            
                            // Berechnung und Speicherung der normalen Rechnungspositionen
                            $end_summe += berechnung_rechnungsposition($is_BUT, $zeit_variable1, $zeit_variable2, $kind_id, 0, $normal_rechnung_id, $essenkategorie, $preiskategorie, 0, 0);
                            $i++;
                        }
                    }
                    
                    // update the normal rechnung after counting the total cost
                    if ($normal_rechnung_id != null) {
                        update_normal_rechnung($normal_rechnung_id, $end_summe);
                    }
                }
            } else {
                echo 'Es wurden keine Kinder für diese Eltern gefunden <br><br>';
            }
        }
    }
}

function berechnung_rechnungsposition($is_BUT, $zeit_variable1, $zeit_variable2, $kind_id, $rechnung_id, $normal_rechnung_id, $essenkategorie, $preiskategorie, $eigenanteil, $eigenanteil_art) {
    $summe = 0;
    $start_month = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable1)->format('n'));
    $end_month = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable2)->format('n'));
    $start_jahr = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable1)->format('Y'));
    $end_jahr = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable2)->format('Y'));
    
    // save rechnungsposition rechnung by month and year
    if ($start_jahr == $end_jahr) {
        for($j = $start_month; $j <= $end_month; $j++) {
            
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $start_jahr);
            $von1 = '1' . '.' . $j . '.' . $start_jahr;
            $bis1 = $daysNumber . '.' . $j . '.' . $start_jahr;
            
            $rechnungsposition_berechnung = menge_and_gesamt_berechnung($kind_id, $von1, $bis1);
            $rechnungsposition_menge = $rechnungsposition_berechnung[0];
            if ($rechnungsposition_menge > 0) {
                $rechnungsposition_gesamt = $rechnungsposition_berechnung[1];
                $monat_jahr = DateTime::createFromFormat('n', $j)->format('m') . '/' . $start_jahr;
                
                if ($is_BUT == true) {
                    if ($eigenanteil_art == 1) {
                        $differenz1 = $rechnungsposition_gesamt - ($rechnungsposition_menge * $eigenanteil);
                        $differenz2 = $rechnungsposition_menge * $eigenanteil;
                    } else {
                        $differenz1 = $rechnungsposition_gesamt - $eigenanteil;
                        $differenz2 = $eigenanteil;
                    }
                    // save a Rechnungsposition for a BUT invoice
                    if($rechnung_id != 0) {
                        save_rechnung_position($rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $differenz1, $monat_jahr);
                    }
                } else {
                    $differenz2 = $rechnungsposition_gesamt;
                }
                $summe += $differenz2;
                
                // save a Rechnungsposition for a normal invoice
                if($normal_rechnung_id !=0) {
                    save_rechnung_position($normal_rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $differenz2, $monat_jahr);
                }
            }
        }
    } else {
        for($j = $start_month; $j <= 12; $j++) {
            
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $start_jahr);
            $von1 = '1' . '.' . $j . '.' . $start_jahr;
            $bis1 = $daysNumber . '.' . $j . '.' . $start_jahr;
            
            $rechnungsposition_berechnung = menge_and_gesamt_berechnung($kind_id, $von1, $bis1);
            $rechnungsposition_menge = $rechnungsposition_berechnung[0];
            if ($rechnungsposition_menge > 0) {
                $rechnungsposition_gesamt = $rechnungsposition_berechnung[1];
                $monat_jahr = DateTime::createFromFormat('n', $j)->format('m') . '/' . $start_jahr;
                
                if ($is_BUT == true) {
                    if ($eigenanteil_art == 1) {
                        $differenz1 = $rechnungsposition_gesamt - ($rechnungsposition_menge * $eigenanteil);
                        $differenz2 = $rechnungsposition_menge * $eigenanteil;
                    } else {
                        $differenz1 = $rechnungsposition_gesamt - $eigenanteil;
                        $differenz2 = $eigenanteil;
                    }
                    // save a Rechnungsposition for a BUT invoice
                    if($rechnung_id != 0) {
                        save_rechnung_position(
                            $rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $differenz1, $monat_jahr);
                    }
                } else {
                    $differenz2 = $rechnungsposition_gesamt;
                }
                $summe += $differenz2;
                
                // save a Rechnungsposition for a normal invoice
                if($normal_rechnung_id !=0) {
                    save_rechnung_position($normal_rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $differenz2, $monat_jahr);
                }
            }
        }
        for($j = 1; $j <= $end_month; $j++) {
            
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $end_jahr);
            $von1 = '1' . '.' . $j . '.' . $end_jahr;
            $bis1 = $daysNumber . '.' . $j . '.' . $end_jahr;
            
            $rechnungsposition_berechnung = menge_and_gesamt_berechnung($kind_id, $von1, $bis1);
            $rechnungsposition_menge = $rechnungsposition_berechnung[0];
            if ($rechnungsposition_menge > 0) {
                $rechnungsposition_gesamt = $rechnungsposition_berechnung[1];
                $monat_jahr = DateTime::createFromFormat('n', $j)->format('m') . '/' . $end_jahr;
                
                if ($is_BUT == true) {
                    if ($eigenanteil_art == 1) {
                        $differenz1 = $rechnungsposition_gesamt - ($rechnungsposition_menge * $eigenanteil);
                        $differenz2 = $rechnungsposition_menge * $eigenanteil;
                    } else {
                        $differenz1 = $rechnungsposition_gesamt - $eigenanteil;
                        $differenz2 = $eigenanteil;
                    }
                    // save a Rechnungsposition for a BUT invoice
                    if($rechnung_id != 0) {
                        save_rechnung_position($rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $differenz1, $monat_jahr);
                    }
                } else {
                    $differenz2 = $rechnungsposition_gesamt;
                }
                $summe += $differenz2;
                
                // save a Rechnungsposition for a normal invoice
                if($normal_rechnung_id !=0) {
                    save_rechnung_position($normal_rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $differenz2, $monat_jahr);
                }
            }
        }
    }
    return $summe;
}


function berechnung_rechnungsposition_nachbuchung($is_BUT, $zeit_variable1, $zeit_variable2, $kind_id, $rechnung_id, $essenkategorie, $preiskategorie, $eigenanteil, $eigenanteil_art) {
    $summe = 0;
    $start_month = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable1)->format('n'));
    $end_month = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable2)->format('n'));
    $start_jahr = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable1)->format('Y'));
    $end_jahr = intval(DateTime::createFromFormat('j.n.Y', $zeit_variable2)->format('Y'));
    
    // save rechnungsposition rechnung by month and year
    if ($start_jahr == $end_jahr) {
        for($j = $start_month; $j <= $end_month; $j++) {
            
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $start_jahr);
            $von1 = '1' . '.' . $j . '.' . $start_jahr;
            $bis1 = $daysNumber . '.' . $j . '.' . $start_jahr;
            
            $rechnungsposition_berechnung = menge_and_gesamt_berechnung($kind_id, $von1, $bis1);
            $rechnungsposition_menge = $rechnungsposition_berechnung[0];
            if ($rechnungsposition_menge > 0) {
                $rechnungsposition_gesamt = $rechnungsposition_berechnung[1];
                $monat_jahr = DateTime::createFromFormat('n', $j)->format('m') . '/' . $start_jahr;
                
                if ($is_BUT == true) {
                    if ($eigenanteil_art == 1) {
                        $gesamt = $rechnungsposition_gesamt - ($rechnungsposition_menge * $eigenanteil);
                    } else {
                        $gesamt = $rechnungsposition_gesamt - $eigenanteil;
                    }
                } else {
                    if ($eigenanteil_art == 1) {
                        $gesamt = $rechnungsposition_menge * $eigenanteil;
                    } else {
                        $gesamt = $eigenanteil;
                    }
                }
                $summe += $gesamt;
                // save a Rechnungsposition for a normal invoice
                save_rechnung_position($rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $gesamt, $monat_jahr);
            }
        }
    } else {
        for($j = $start_month; $j <= 12; $j++) {
            
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $start_jahr);
            $von1 = '1' . '.' . $j . '.' . $start_jahr;
            $bis1 = $daysNumber . '.' . $j . '.' . $start_jahr;
            
            $rechnungsposition_berechnung = menge_and_gesamt_berechnung($kind_id, $von1, $bis1);
            $rechnungsposition_menge = $rechnungsposition_berechnung[0];
            if ($rechnungsposition_menge > 0) {
                $rechnungsposition_gesamt = $rechnungsposition_berechnung[1];
                $monat_jahr = DateTime::createFromFormat('n', $j)->format('m') . '/' . $start_jahr;
                save_rechnung_position($rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $rechnungsposition_gesamt, $monat_jahr);
                
                if ($is_BUT == true) {
                    if ($eigenanteil_art == 1) {
                        $gesamt = $rechnungsposition_gesamt - ($rechnungsposition_menge * $eigenanteil);
                    } else {
                        $gesamt = $rechnungsposition_gesamt - $eigenanteil;
                    }
                } else {
                    if ($eigenanteil_art == 1) {
                        $gesamt = $rechnungsposition_menge * $eigenanteil;
                    } else {
                        $gesamt = $eigenanteil;
                    }
                }
                $summe += $gesamt;
                // save a Rechnungsposition for a normal invoice
                //save_rechnung_position($rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $gesamt, $monat_jahr);
            }
        }
        for($j = 1; $j <= $end_month; $j++) {
            
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $end_jahr);
            $von1 = '1' . '.' . $j . '.' . $end_jahr;
            $bis1 = $daysNumber . '.' . $j . '.' . $end_jahr;
            
            $rechnungsposition_berechnung = menge_and_gesamt_berechnung($kind_id, $von1, $bis1);
            $rechnungsposition_menge = $rechnungsposition_berechnung[0];
            if ($rechnungsposition_menge > 0) {
                $rechnungsposition_gesamt = $rechnungsposition_berechnung[1];
                $monat_jahr = DateTime::createFromFormat('n', $j)->format('m') . '/' . $end_jahr;
                
                if ($is_BUT == true) {
                    if ($eigenanteil_art == 1) {
                        $gesamt = $rechnungsposition_gesamt - ($rechnungsposition_menge * $eigenanteil);
                    } else {
                        $gesamt = $rechnungsposition_gesamt - $eigenanteil;
                    }
                } else {
                    if ($eigenanteil_art == 1) {
                        $gesamt = $rechnungsposition_menge * $eigenanteil;
                    } else {
                        $gesamt = $eigenanteil;
                    }
                }
                $summe += $gesamt;
                // save a Rechnungsposition for a normal invoice
                save_rechnung_position($rechnung_id, $essenkategorie, $preiskategorie, $kind_id, $rechnungsposition_menge, $gesamt, $monat_jahr);
            }
        }
    }
    return $summe;
}

function reset_abgeschlossene_rechnungen($rechnung_id) {
    global $conn;
    
    // reset invoices
    $query = "UPDATE keis2_rechnung SET update_date= NOW()
                WHERE id = ".$conn->real_escape_string($rechnung_id)."";
    
    if ($conn->query($query)=== TRUE) {
        echo 'eine normale Rechnung in diesem Zeitraum wurde erfolgreich zurückgesetzt';
    } else {
        echo "Fehler beim Zurücksetzen einer normalen Rechnung in diesem Zeitraum : " .$conn->error;
    }
    
    $query = "UPDATE keis2_rechnungspositionen SET update_date= NOW()
                WHERE id_rechnung = ".$conn->real_escape_string($rechnung_id)."";
    
    if ($conn->query($query)=== TRUE) {
        echo 'die zugehörigen Rechnungspositionen wurden erfolgreichzurückgesetzt';
    } else {
        echo "Fehler beim Zurücksetzen einer normalen Rechnung in diesem Zeitraum : " .$conn->error;
    }
}


function menge_and_gesamt_berechnung($kind_id, $zeit_variable1, $zeit_variable2) {
    global $conn;
    global $customFunction;
    
    $von = DateTime::createFromFormat('j.n.Y', $zeit_variable1);
    $bis = DateTime::createFromFormat('j.n.Y', $zeit_variable2);
    
    // check if BUT support is available
    $is_but = $customFunction->check_but($kind_id);
    
    // check the dauerbestellung status of kids
    $sql = "SELECT * FROM keis2_dauerbestellung WHERE kind=
            ".$conn->real_escape_string($kind_id)."";
    $result_dauerbestellung = $conn->query($sql);
    if ($result_dauerbestellung->num_rows > 0 ) {
        // the kid has dauerbestellung
        $row_dauerbestellung = $result_dauerbestellung->fetch_assoc();
        
        // check if the the combination of month and date is within the essen period
        $essenstart = DateTime::createFromFormat('Y-m-d', $row_dauerbestellung['essenstart']);
        $essenende = DateTime::createFromFormat('Y-m-d', $row_dauerbestellung['essenende']);
        $essenkategorie = $row_dauerbestellung['essenkategorie'];
        $preiskategorie = $customFunction->getIdOfPreisKategorie($essenkategorie, $kind_id);
        
        // $daysNumber = cal_days_in_month(CAL_GREGORIAN, $bis->format('n'), $bis->format('Y'));
        // $lastDay = $daysNumber.".".$bis->format('n').".".$bis->format('Y');
        
        if ($essenende < $von || $essenstart > $bis) {
            $gesamt = 0;
            $menge = 0;
        } else {
            $daysArray = array();
            foreach ($row_dauerbestellung as $key => $value) {
                if ($key == 'montag' || $key == 'dienstag' || $key == 'mittwoch' || $key == 'donnerstag' || $key == 'freitag') {
                    if ($value == 1) {
                        $daysArray[] = ucfirst($key);
                    }
                }
            }
            
            // $firstDay = $von->format('j.n.Y');
            // $lastDay = $bis->format('j.n.Y');
            
            $daysNumber = cal_days_in_month(CAL_GREGORIAN, $bis->format('n'), $bis->format('Y'));
            if ($von->format('n') == $essenstart->format('n') && $bis->format('n') != $essenende->format('n')) {
                $firstDay = $essenstart->format('j.n.Y');
                $lastDay = $daysNumber.".".$bis->format('n').".".$bis->format('Y');
            } elseif ($von->format('n') != $essenstart->format('n') && $bis->format('n') == $essenende->format('n')) {
                $firstDay = "1.".$von->format('n').".".$von->format('Y');
                $lastDay = $essenende->format('j.n.Y');
            } elseif ($von->format('n') != $essenstart->format('n') && $bis->format('n') != $essenende->format('n')) {
                $firstDay = "1.".$von->format('n').".".$von->format('Y');
                $lastDay = $daysNumber.".".$bis->format('n').".".$bis->format('Y');
            } else {
                $firstDay = $essenstart->format('j.n.Y');
                $lastDay = $essenende->format('j.n.Y');
            }
            
            /*
             if ($essenstart >= $von && $essenende <= $bis) {
             $firstDay = $essenstart->format('j.n.Y');
             $lastDay = $essenende->format('j.n.Y');
             }
             
             if ($essenstart < $von && ($essenende >= $von && $essenende <= $bis)) {
             $firstDay = $von->format('j.n.Y');
             $lastDay = $essenende->format('j.n.Y');
             }
             
             if (($essenstart >= $von && $essenstart <= $bis) && $essenende > $bis) {
             $firstDay = $von->format('j.n.Y');
             $lastDay = $essenende->format('j.n.Y');
             }*/
            
            $menge = compute_dauerbestellung_days($kind_id, $firstDay, $lastDay, $daysArray);
            $gesamt = $menge * $customFunction->getPreisOfEssenKategorie($row_dauerbestellung['essenkategorie'], $kind_id);
        }
    } else {
        // no dauerbestellung
        
        $firstDay = $von->format('j.n.Y');
        $lastDay = $bis->format('j.n.Y');
        $gesamt = 0;
        $menge = 0;
        
        $sql = "SELECT * FROM keis2_bestellung WHERE datum between
                STR_TO_DATE('".$conn->real_escape_string($firstDay)."', '%e.%c.%Y') AND
                STR_TO_DATE('".$conn->real_escape_string($lastDay)."', '%e.%c.%Y')
                AND kind=".$conn->real_escape_string($kind_id)." AND update_date
                IS NULL ORDER BY datum";
        $result_bestellungen = $conn->query($sql);
        if ($result_bestellungen->num_rows > 0) {
            $menge = $result_bestellungen->num_rows;
            while($row_bestellungen = $result_bestellungen->fetch_assoc()) {
                $gesamt += $customFunction->getPreisOfEssenKategorie($row_bestellungen['essenkategorie'], $kind_id);
            }
        }
        $essenkategorie = 0;
        $preiskategorie = 0;
    }
    return array($menge, $gesamt, $essenkategorie, $preiskategorie, $is_but);
}

function compute_dauerbestellung_days($kind_id, $firstDay, $lastDay, $daysArray) {
    global $conn;
    $sql = "SELECT datum FROM keis2_abbestellung WHERE datum between
            STR_TO_DATE('".$conn->real_escape_string($firstDay)."', '%e.%c.%Y') AND
            STR_TO_DATE('".$conn->real_escape_string($lastDay)."', '%e.%c.%Y')
            AND kind=".$conn->real_escape_string($kind_id)." AND update_date
            IS NULL ORDER BY datum";
    $result = $conn->query($sql);
    $dauerbestellung_days_by_month = 0;
    setlocale(LC_TIME, 'de');
    require_once('../utilities/feiertage-api-connector.php');
    $connector = LPLib_Feiertage_Connector::getInstance();
    
    // extract the days numbers from the dates
    $start_day = intval(DateTime::createFromFormat('j.n.Y', $firstDay)->format('j'));
    $end_day = intval(DateTime::createFromFormat('j.n.Y', $lastDay)->format('j'));
    
    $start_month = intval(DateTime::createFromFormat('j.n.Y', $firstDay)->format('n'));
    $end_month = intval(DateTime::createFromFormat('j.n.Y', $lastDay)->format('n'));
    
    $start_jahr = intval(DateTime::createFromFormat('j.n.Y', $firstDay)->format('Y'));
    $end_jahr = intval(DateTime::createFromFormat('j.n.Y', $lastDay)->format('Y'));
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        for ($k = $start_jahr; $k <= $end_jahr; $k++) {
            
            $difference_years = $end_jahr - $k;
            if ($difference_years > 0) {
                $for_cnt_month = 12;
            } else {
                $for_cnt_month = $end_month;
            }
            
            for($j = $start_month; $j <= $for_cnt_month; $j++) {
                if ($difference_years > 0) {
                    $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $k);
                } else {
                    $daysNumber = $end_day;
                }
                for($i = $start_day; $i <= $daysNumber; $i++) {
                    if (in_array(strftime('%A', mktime(0, 0, 0, $j, $i, $k)), $daysArray)) {
                        $date = DateTime::createFromFormat('j.n.Y', $i . '.' . $j . '.' . $k)->format('Y-m-d');
                        if (!in_array($date, $row) && !$connector->isFeiertagInLand($date, LPLib_Feiertage_Connector::LAND_BAYERN)) {
                            $dauerbestellung_days_by_month++;
                        }
                    }
                }
            }
            $start_month = 1;
            $start_day = 1;
        }
    } else {
        for ($k = $start_jahr; $k <= $end_jahr; $k++) {
            $difference_years = $end_jahr - $k;
            if ($difference_years > 0) {
                $for_cnt_month = 12;
            } else {
                $for_cnt_month = $end_month;
            }
            for($j = $start_month; $j <= $for_cnt_month; $j++) {
                if ($difference_years > 0) {
                    $daysNumber = cal_days_in_month(CAL_GREGORIAN, $j, $k);
                } else {
                    $daysNumber = $end_day;
                }
                for($i = $start_day; $i <= $daysNumber; $i++) {
                    if (in_array(strftime('%A', mktime(0, 0, 0, $j, $i, $k)), $daysArray)) {
                        $date = DateTime::createFromFormat('j.n.Y', $i . '.' . $j . '.' . $k)->format('Y-m-d');
                        if (!$connector->isFeiertagInLand($date, LPLib_Feiertage_Connector::LAND_BAYERN)) {
                            $dauerbestellung_days_by_month++;
                        }
                    }
                }
            }
            $start_month = 1;
            $start_day = 1;
        }
    }
    return $dauerbestellung_days_by_month;
}

function save_normal_rechnung($rechnungsnummer, $benutzer_id, $zeitraum_von, $zeitraum_bis, $rechnung_id, $abgeschlossen = 0) {
    global $conn;
    if($abgeschlossen) {
        $query = "INSERT INTO keis2_rechnung (rechnungsnummer, benutzer_id,
                    zeitraum_von, zeitraum_bis, rechnungsdatum, alte_rechnung, insert_date, abgeschlossen)
                	VALUES ('".$conn->real_escape_string($rechnungsnummer)."',
                    ".$conn->real_escape_string($benutzer_id).",
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y'),
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y'),
                    CURDATE(),
                    ".$conn->real_escape_string($rechnung_id).",
                    NOW(),
                    1)";
    } else {
        $query = "INSERT INTO keis2_rechnung (rechnungsnummer, benutzer_id,
                    zeitraum_von, zeitraum_bis, rechnungsdatum, alte_rechnung, insert_date)
                	VALUES ('".$conn->real_escape_string($rechnungsnummer)."',
                    ".$conn->real_escape_string($benutzer_id).",
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y'),
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y'),
                    CURDATE(),
                    ".$conn->real_escape_string($rechnung_id).",
                    NOW())";
    }
    if ($conn->query($query)=== TRUE) {
        $last_id = $conn->insert_id;
        echo "Eine neue Rechnung für die Eltern N° " .  $benutzer_id . " wurde erfolgreich erstellt <br><br>";
    } else {
        $last_id = 0;
        echo "Beim Hinzufügen dieser Rechnung ist ein Fehler aufgetreten : " . $conn->error . '<br><br>';
    }
    return $last_id;
}

function update_normal_rechnung($rechnung_id, $gesamtbetrag) {
    global $conn;
    $query = "UPDATE keis2_rechnung SET gesamtbetrag=
                '".$conn->real_escape_string($gesamtbetrag)."'
                WHERE id=".$conn->real_escape_string($rechnung_id)."";
    
    if ($conn->query($query)=== TRUE) {
        echo "Rechnung erfolgreich aktualisiert <br><br>";
    } else {
        echo "Fehler beim Aktualisieren der Rechnung : " . $conn->error . '<br><br>';
    }
}

function save_but_rechnung($rechnungsnummer, $amt_id, $amt_ansprechpartner_id, $einrichtung_id,
    $aktenzeichen, $but_id, $gesamt, $menge, $zeitraum_von, $zeitraum_bis, $verwendungszweck, $rechnung_id, $abgeschlossen = 0) {
        global $conn;
        if($abgeschlossen) {
            $query = "INSERT INTO keis2_rechnung (rechnungsnummer, rechnungsdatum, amt_id, amt_ansprechpartner_id, einrichtung_id,
                aktenzeichen, but_id, gesamtbetrag, anzahlEssen, zeitraum_von, zeitraum_bis, verwendungszweck, alte_rechnung, insert_date, abgeschlossen)
            	VALUES ('".$conn->real_escape_string($rechnungsnummer)."',
                CURDATE(),
                '".$conn->real_escape_string($amt_id)."',
                '".$conn->real_escape_string($amt_ansprechpartner_id)."',
                ".$conn->real_escape_string($einrichtung_id).",
                '".$conn->real_escape_string($aktenzeichen)."',
                ".$conn->real_escape_string($but_id).",
                ".$conn->real_escape_string($gesamt).",
                ".$conn->real_escape_string($menge).",
                STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y'),
                STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y'),
                '".$conn->real_escape_string($verwendungszweck)."',
                ".$conn->real_escape_string($rechnung_id).",
                NOW(),
                1)";
        } else {
            $query = "INSERT INTO keis2_rechnung (rechnungsnummer, rechnungsdatum, amt_id, amt_ansprechpartner_id, einrichtung_id,
                    aktenzeichen, but_id, gesamtbetrag, anzahlEssen, zeitraum_von, zeitraum_bis, verwendungszweck, alte_rechnung, insert_date)
                	VALUES ('".$conn->real_escape_string($rechnungsnummer)."',
                    CURDATE(),
                    '".$conn->real_escape_string($amt_id)."',
                    '".$conn->real_escape_string($amt_ansprechpartner_id)."',
                    ".$conn->real_escape_string($einrichtung_id).",
                    '".$conn->real_escape_string($aktenzeichen)."',
                    ".$conn->real_escape_string($but_id).",
                    ".$conn->real_escape_string($gesamt).",
                    ".$conn->real_escape_string($menge).",
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%e.%c.%Y'),
                    STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%e.%c.%Y'),
                    '".$conn->real_escape_string($verwendungszweck)."',
                    ".$conn->real_escape_string($rechnung_id).",
                    NOW())";
        }
        
        if ($conn->query($query)=== TRUE) {
            $last_id = $conn->insert_id;
            echo "Eine neue BUT Rechnung für die BUT N° " .  $but_id . " wurde erfolgreich erstellt <br><br>";
        } else {
            $last_id = 0;
            echo "Beim Hinzufügen dieser Rechnung ist ein Fehler aufgetreten : " . $conn->error . '<br><br>';
        }
        return $last_id;
}

function save_rechnung_position($id_rechnung, $essenkategorie, $preiskategorie, $kind_id, $menge, $gesamtpreis, $monat_jahr) {
    global $conn;
    $query = "INSERT INTO keis2_rechnungspositionen (id_rechnung, essenskategorie,
                preiskategorie, kind_id, menge, gesamtpreis, monat_jahr, insert_date)
            	VALUES (".$conn->real_escape_string($id_rechnung).",
                ".$conn->real_escape_string($essenkategorie).",
                ".$conn->real_escape_string($preiskategorie).",
                ".$conn->real_escape_string($kind_id).",
                ".$conn->real_escape_string($menge).",
                ".$conn->real_escape_string($gesamtpreis).",
                '".$conn->real_escape_string($monat_jahr)."',
                NOW())";
    
    if ($conn->query($query)=== TRUE) {
        echo "Eine neue Rechnungsposition für das Kind N° " .  $kind_id . " wurde erfolgreich erstellt <br><br>";
        $gesamt = $gesamtpreis;
    } else {
        echo "Beim Hinzufügen dieser Rechnungsposition ist ein Fehler aufgetreten : " . $conn->error . '<br><br>';
        $gesamt = 0;
    }
    return $gesamt;
}

function reset_rechnungen($zeit_variable1, $zeit_variable2) {
    global $conn;
    $sql = "UPDATE keis2_rechnungspositionen SET update_date= NOW()
            WHERE id_rechnung IN (SELECT id FROM keis2_rechnung
            WHERE update_date IS NULL AND zeitraum_von =
            STR_TO_DATE('".$conn->real_escape_string($zeit_variable1)."', '%e.%c.%Y') AND
            zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeit_variable2)."', '%e.%c.%Y'))";
    
    if ($conn->query($sql)=== TRUE) {
        echo 'die Rechnungsposition in diesem Zeitraum wurden erfolgreich zurückgesetzt';
    } else {
        echo "Fehler beim Zurücksetzen der Rechnungsposition : " .$conn->error;
    }
    
    $sql = "UPDATE keis2_rechnung SET update_date= NOW()
            WHERE zeitraum_von = STR_TO_DATE('".$conn->real_escape_string($zeit_variable1)."', '%e.%c.%Y')
            AND zeitraum_bis = STR_TO_DATE('".$conn->real_escape_string($zeit_variable2)."', '%e.%c.%Y')
            AND update_date IS NULL";
    
    if ($conn->query($sql)=== TRUE) {
        echo 'Die vorherigen Rechnungen in diesem Zeitraum wurden erfolgreich zurückgesetzt';
    } else {
        echo "Fehler beim Zurücksetzen der vorherigen Rechnungen : " .$conn->error;
    }
}