<?php

require_once('../login/session.php');
require_once('../utilities/constants.php');
$session = Session::getInstance();
if($session->checkSessionVariables('username', 'usergroup')){
    require_once('../benutzerverwaltung/benutzer/benutzer_class.php');
    $benutzer = new Benutzer();
    if (!$benutzer->isAdmin($session->usergroup)) {
        header('Location: ' . Constants::getBaseURL());
    } else {
        $database = null;
        
        try {
            if (isset($_POST['datetimepicker1'], $_POST['datetimepicker2'])) {
                
                $zeitraum_von = $_POST['datetimepicker1'];
                $zeitraum_bis = $_POST['datetimepicker2'];
                
                require_once('../utilities/db_connection.php');
                $database = new DatabaseConnection();
                $conn = $database->getConn();
                $sql = "SELECT * FROM keis2_rechnung WHERE update_date IS NULL";

                if ($zeitraum_von == '' && $zeitraum_bis != '') {
                    $sql .= " AND zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')";
                    $zeitraumVon = NULL;
                    $zeitraumBis = DateTime::createFromFormat("d.m.Y", $zeitraum_bis);
                } else if ($zeitraum_von != '' && $zeitraum_bis != '') {
                    $sql .= " AND ((STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') between zeitraum_von and zeitraum_bis) OR (STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y') between zeitraum_von and zeitraum_bis)
                    OR (zeitraum_von <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') AND zeitraum_bis BETWEEN STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') AND STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y'))
                    OR (zeitraum_bis >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y') AND zeitraum_von BETWEEN STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') AND STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')))";
                    $zeitraumVon = DateTime::createFromFormat("d.m.Y", $zeitraum_von);
                    $zeitraumBis = DateTime::createFromFormat("d.m.Y", $zeitraum_bis);
                } else if ($zeitraum_von != '' && $zeitraum_bis == '') {
                    $sql .= " AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y')";
                    $zeitraumVon = DateTime::createFromFormat("d.m.Y", $zeitraum_von);
                    $zeitraumBis = NULL;
                } else {
                    $zeitraumVon = NULL;
                    $zeitraumBis = NULL;
                }
                
                $result = $conn->query($sql);
                
                // Data Array für die CSV-Datei
                $csvData = array();

                //$csvArray = array_map("utf8_decode", array("EXTF", "300", "1079", "Buchungsstapel", "1", "2018300000000000", "", "", "Verwaltung", "", "365311", "10003", "20180101", "3", "2018301", "2018331", "Buchungen", "", "1", "0", "EUR", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", ""));
                $csvArray = array_map("utf8_decode", explode(';',"EXTF;300;1079;Buchungsstapel;1;2,0183E+15;;;Verwaltung;;365311;10003;20180101;3;2018301;2018331;Buchungen;;1;0;;EUR;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;"));
                array_push($csvData, $csvArray);

                //$csvArray = array_map("utf8_decode", array("Umsatz (ohne Soll/Haben-Kz)", "Soll/Haben-Kennzeichen", "WKZ Umsatz", "Kurs", "Basis-Umsatz", "WKZ Basis-Umsatz", "Konto", "Gegenkonto (ohne BU-Schlüssel)", "BU-Schlüssel", "Belegdatum", "Belegfeld 1", "Belegfeld 2", "Skonto", "Buchungstext", "Postensperre", "Diverse Adressnummer", "Geschäftspartnerbank", "Sachverhalt", "Zinssperre", "Beleglink", "Beleginfo - Art 1", "Beleginfo - Inhalt 1", "Beleginfo - Art 2", "Beleginfo - Inhalt 2", "Beleginfo - Art 3", "Beleginfo - Inhalt 3", "Beleginfo - Art 4", "Beleginfo - Inhalt 4", "Beleginfo - Art 5", "Beleginfo - Inhalt 5", "Beleginfo - Art 6", "Beleginfo - Inhalt 6", "Beleginfo - Art 7", "Beleginfo - Inhalt 7", "Beleginfo - Art 8", "Beleginfo - Inhalt 8", "KOST1 - Kostenstelle", "KOST2 - Kostenstelle", "Kost-Menge", "EU-Land u. UStID", "EU-Steuersatz", "Abw. Versteuerungsart", "Sachverhalt L+L", "Funktionsergänzung L+L", "BU 49 Hauptfunktionstyp", "BU 49 Hauptfunktionsnummer", "BU 49 Funktionsergänzung", "Zusatzinformation - Art 1", "Zusatzinformation- Inhalt 1", "Zusatzinformation - Art 2", "Zusatzinformation- Inhalt 2", "Zusatzinformation - Art 3", "Zusatzinformation- Inhalt 3", "Zusatzinformation - Art 4", "Zusatzinformation- Inhalt 4", "Zusatzinformation - Art 5", "Zusatzinformation- Inhalt 5", "Zusatzinformation - Art 6", "Zusatzinformation- Inhalt 6", "Zusatzinformation - Art 7", "Zusatzinformation- Inhalt 7", "Zusatzinformation - Art 8", "Zusatzinformation- Inhalt 8", "Zusatzinformation - Art 9", "Zusatzinformation- Inhalt 9", "Zusatzinformation - Art 10", "Zusatzinformation- Inhalt 10", "Zusatzinformation - Art 11", "Zusatzinformation- Inhalt 11", "Zusatzinformation - Art 12", "Zusatzinformation- Inhalt 12", "Zusatzinformation - Art 13", "Zusatzinformation- Inhalt 13", "Zusatzinformation - Art 14", "Zusatzinformation- Inhalt 14", "Zusatzinformation - Art 15", "Zusatzinformation- Inhalt 15", "Zusatzinformation - Art 16", "Zusatzinformation- Inhalt 16", "Zusatzinformation - Art 17", "Zusatzinformation- Inhalt 17", "Zusatzinformation - Art 18", "Zusatzinformation- Inhalt 18", "Zusatzinformation - Art 19", "Zusatzinformation- Inhalt 19", "Zusatzinformation - Art 20", "Zusatzinformation- Inhalt 20", "Stück", "Gewicht", "Zahlweise", "Forderungsart", "Veranlagungsjahr", "Zugeordnete Fälligkeit", "Skontotyp", "Auftragsnummer", "Buchungstyp", "Ust-Schlüssel (Anzahlungen)", "EU-Land (Anzahlungen)", "Sachverhalt L+L (Anzahlungen)", "EU-Steuersatz (Anzahlungen)", "Erlöskonto (Anzahlungen)", "Herkunft-Kz;Buchungs GUID", "KO", "T-Datum", "Mandatsreferenz"));
                $csvArray = array_map("utf8_decode", explode(';',"Umsatz (ohne Soll/Haben-Kz);Soll/Haben-Kennzeichen;WKZ Umsatz;Kurs;Basis-Umsatz;WKZ Basis-Umsatz;Konto;Gegenkonto (ohne BU-Schlüssel);BU-Schlüssel;Belegdatum;Belegfeld 1;Belegfeld 2;Skonto;Buchungstext;Postensperre;Diverse Adressnummer;Geschäftspartnerbank;Sachverhalt;Zinssperre;Beleglink;Beleginfo - Art 1;Beleginfo - Inhalt 1;Beleginfo - Art 2;Beleginfo - Inhalt 2;Beleginfo - Art 3;Beleginfo - Inhalt 3;Beleginfo - Art 4;Beleginfo - Inhalt 4;Beleginfo - Art 5;Beleginfo - Inhalt 5;Beleginfo - Art 6;Beleginfo - Inhalt 6;Beleginfo - Art 7;Beleginfo - Inhalt 7;Beleginfo - Art 8;Beleginfo - Inhalt 8;KOST1 - Kostenstelle;KOST2 - Kostenstelle;Kost-Menge;EU-Land u. UStID;EU-Steuersatz;Abw. Versteuerungsart;Sachverhalt L+L;Funktionsergänzung L+L;BU 49 Hauptfunktionstyp;BU 49 Hauptfunktionsnummer;BU 49 Funktionsergänzung;Zusatzinformation - Art 1;Zusatzinformation- Inhalt 1;Zusatzinformation - Art 2;Zusatzinformation- Inhalt 2;Zusatzinformation - Art 3;Zusatzinformation- Inhalt 3;Zusatzinformation - Art 4;Zusatzinformation- Inhalt 4;Zusatzinformation - Art 5;Zusatzinformation- Inhalt 5;Zusatzinformation - Art 6;Zusatzinformation- Inhalt 6;Zusatzinformation - Art 7;Zusatzinformation- Inhalt 7;Zusatzinformation - Art 8;Zusatzinformation- Inhalt 8;Zusatzinformation - Art 9;Zusatzinformation- Inhalt 9;Zusatzinformation - Art 10;Zusatzinformation- Inhalt 10;Zusatzinformation - Art 11;Zusatzinformation- Inhalt 11;Zusatzinformation - Art 12;Zusatzinformation- Inhalt 12;Zusatzinformation - Art 13;Zusatzinformation- Inhalt 13;Zusatzinformation - Art 14;Zusatzinformation- Inhalt 14;Zusatzinformation - Art 15;Zusatzinformation- Inhalt 15;Zusatzinformation - Art 16;Zusatzinformation- Inhalt 16;Zusatzinformation - Art 17;Zusatzinformation- Inhalt 17;Zusatzinformation - Art 18;Zusatzinformation- Inhalt 18;Zusatzinformation - Art 19;Zusatzinformation- Inhalt 19;Zusatzinformation - Art 20;Zusatzinformation- Inhalt 20;Stück;Gewicht;Zahlweise;Forderungsart;Veranlagungsjahr;Zugeordnete Fälligkeit;Skontotyp;Auftragsnummer;Buchungstyp;Ust-Schlüssel (Anzahlungen);EU-Land (Anzahlungen);Sachverhalt L+L (Anzahlungen);EU-Steuersatz (Anzahlungen);Erlöskonto (Anzahlungen);Herkunft-Kz;Buchungs GUID;KOST-Datum;Mandatsreferenz"));
                array_push($csvData, $csvArray);

                if ($result->num_rows > 0) {

                    while($row = $result->fetch_assoc()) {
                        
                        $debitorennummer = getDebitorenNummer($row['benutzer_id'], $row['but_id']);
                        $nameDerKinder = getNameKinder($row['id'], $row['but_id']);
                        
                        $rechnungVon = DateTime::createFromFormat("Y-m-d", $row['zeitraum_von']);
                        $rechnungBis = DateTime::createFromFormat("Y-m-d", $row['zeitraum_bis']);
                        if($zeitraumVon != NULL && $zeitraumVon > $rechnungVon) {
                            $rechnungVon = DateTime::createFromFormat("Y-m-d", $zeitraumVon->format("Y-m-d"));
                        }
                        if($zeitraumBis != NULL && $zeitraumBis < $rechnungBis) {
                            $rechnungBis = DateTime::createFromFormat("Y-m-d", $zeitraumBis->format("Y-m-d"));
                        }
                        
                        for($i = $rechnungVon; $i <= $rechnungBis; $i->add(DateInterval::createFromDateString("1 month"))) {
                            $monat = $i->format('m');
                            $jahr = $i->format('Y');
                            $monatTag = $i->format('tm');
                            $monatJahr = $i->format('mY');
                            $debitorennummerUndMonatJahr = $monatJahr.$debitorennummer;
                            $preisGesamt = 0;
                            $sqlPreis = "SELECT gesamtpreis FROM keis2_rechnungspositionen WHERE id_rechnung = ".$conn->real_escape_string($row['id'])."
                                                                                            AND monat_jahr = '".$conn->real_escape_string($monat."/".$jahr)."'";
                            $resultPreis = $conn->query($sqlPreis);
                            if($resultPreis->num_rows > 0) {
                                while($rowPreis = $resultPreis->fetch_assoc()) {
                                    $preisGesamt += $rowPreis['gesamtpreis'];
                                }
                            }
                            // Create Buchungstext
                            $buchungstext = createBuchungstext($row['but_id'], $monat, $jahr, $debitorennummer, $nameDerKinder, $preisGesamt);
                            // Add CSV data
                            //addCSVData($csvData, $preisGesamt, "S", $debitorennummer, "801035",
                            //    $monatTag, $debitorennummerUndMonatJahr, $buchungstext, "3000", "AK");
                            $csvArray = array_map("utf8_decode", array($preisGesamt, "S", "", "", "", "", $debitorennummer, "801035", "",
                                $monatTag, $debitorennummerUndMonatJahr, "", "", $buchungstext, "", "", "", "", "", "", "", "", "", "", "", "",
                                "", "", "", "", "", "", "", "", "", "", "3000", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "AK", "", "", ""));
                                
                                array_push($csvData, $csvArray);
                        }

                        // Gegenbuchung?
                        if ($row['alte_rechnung'] != null && !empty($row['alte_rechnung'])) {
                            $sql_nachbuchung = "SELECT * FROM keis2_rechnung WHERE id = ".$conn->real_escape_string($row['alte_rechnung']);
                            $result_nachbuchung = $conn->query($sql_nachbuchung);

                            if ($result_nachbuchung->num_rows > 0) {
                                $row_nachbuchung = $result_nachbuchung->fetch_assoc();
                                $nachbuchungVon = DateTime::createFromFormat("Y-m-d", $row_nachbuchung['zeitraum_von']);
                                $nachbuchungBis = DateTime::createFromFormat("Y-m-d", $row_nachbuchung['zeitraum_bis']);
                                if($zeitraumVon != NULL && $zeitraumVon > $rechnungVon) {
                                    $nachbuchungVon = DateTime::createFromFormat("Y-m-d", $zeitraumVon->format("Y-m-d"));
                                }
                                if($zeitraumBis != NULL && $zeitraumBis < $rechnungBis) {
                                    $nachbuchungBis = DateTime::createFromFormat("Y-m-d", $zeitraumBis->format("Y-m-d"));
                                }
                                for($i = $nachbuchungVon; $i <= $nachbuchungBis; $i->add(DateInterval::createFromDateString("1 month"))) {
                                    $monat = $i->format('m');
                                    $jahr = $i->format('Y');
                                    $monatTag = $i->format('tm');
                                    $monatJahr = $i->format('mY');
                                    $debitorennummerUndMonatJahr = $monatJahr.$debitorennummer;
                                    $preisNachbuchung = 0;
                                    $sqlPreis = "SELECT gesamtpreis FROM keis2_rechnungspositionen WHERE id_rechnung = ".$conn->real_escape_string($row_nachbuchung['id'])."
                                                                                            AND monat_jahr = '".$conn->real_escape_string($monat."/".$jahr)."'";
                                    $resultPreis = $conn->query($sqlPreis);
                                    if($resultPreis->num_rows > 0) {
                                        while($rowPreis = $resultPreis->fetch_assoc()) {
                                            $preisNachbuchung += $rowPreis['gesamtpreis'];
                                        }
                                    }
                                    // Create Buchungstext
                                    $buchungstext = createBuchungstext($row_nachbuchung['but_id'], $monat, $jahr, $debitorennummer, $nameDerKinder, $preisNachbuchung);
                                    
                                    // Add CSV data
                                    //addCSVData($csvData, $preisNachbuchung, "S", $debitorennummer, "20801035",
                                     //   $monatTag, $debitorennummerUndMonatJahr, $buchungstext, "3000", "AK");
                                    $csvArray = array_map("utf8_decode", array($preisNachbuchung, "S", "", "", "", "", $debitorennummer, "20801035", "",
                                        $monatTag, $debitorennummerUndMonatJahr, "", "", $buchungstext, "", "", "", "", "", "", "", "", "", "", "", "",
                                        "", "", "", "", "", "", "", "", "", "", "3000", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                        "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "AK", "", "", ""));
                                        
                                        array_push($csvData, $csvArray);
                                }
                            }
                        }
                        
                        // DATEV Rückwirkend
                        // Check BUT
                        if ($row['but_id'] != null && !empty($row['but_id'])) {
                            $sql_but = "SELECT * FROM keis2_but WHERE id = ".$conn->real_escape_string($row['but_id']);
                            if ($zeitraum_von == '' && $zeitraum_bis != '') {
                                $sql .= " AND zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')";
                            } else if ($zeitraum_von != '' && $zeitraum_bis != '') {
                                $sql .= " AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y') OR zeitraum_bis <= STR_TO_DATE('".$conn->real_escape_string($zeitraum_bis)."', '%d.%m.%Y')";
                            } else if ($zeitraum_von != '' && $zeitraum_bis == '') {
                                $sql .= " AND zeitraum_von >= STR_TO_DATE('".$conn->real_escape_string($zeitraum_von)."', '%d.%m.%Y')";
                            }
                            $result_but = $conn->query($sql_but);
                            if ($result_but->num_rows > 0) {
                                $rowBut = $result_but->fetch_assoc();
                                // Check Datev rückwirkend
                                $butVon = DateTime::createFromFormat("Y-m-d", $rowBut['von']);
                                $butBis = DateTime::createFromFormat("Y-m-d", $rowBut['bis']);
                                $now = new DateTime;
                                if($now < $butBis) {
                                    $butBis = DateTime::createFromFormat("Y-m-d", $now->format("Y-m-d"));
                                }
                                //geh von but_anfang monatsweise bis but_ende
                                for($i = $butVon; $i <= $butBis; $i->add(DateInterval::createFromDateString("1 month"))) {
                                    $sql_datev_rueckwirkend = "SELECT * FROM keis2_datev_rueckwirkend WHERE rechnungsdatum = STR_TO_DATE('".$conn->real_escape_string($i->format("Y-m-01"))."','%Y-%m-%d') LIMIT 1";
                                    $result_datev_rueckwirkend = $conn->query($sql_datev_rueckwirkend);
                                    
                                    // Check BUT-Rechnung für Zeitraum
                                    $sql_but_rechnung_rueckwirkend = "SELECT * FROM keis2_rechnung WHERE but_id = ".$conn->real_escape_string($row['but_id'])." 
                                                                                                    AND STR_TO_DATE('".$conn->real_escape_string($i->format("Y-m-d"))."','%Y-%m-%d') BETWEEN zeitraum_von AND zeitraum_bis";
                                    $result_but_rechnung_rueckwirkend = $conn->query($sql_but_rechnung_rueckwirkend);
                                    
                                    if ($result_datev_rueckwirkend->num_rows == 0 && $result_but_rechnung_rueckwirkend->num_rows == 0) {
                                        
                                        $debitorennummer = getDebitorenNummer(0, $rowBut['id']);
                                        // Gegenbuchung für das Datum erstellen
                                        $monat = $i->format("m");
                                        $jahr = $i->format("Y");
                                        $monatTag = $i->format("tm");
                                        $monatJahr = $i->format("my");
                                        $debitorennummerUndMonatJahr = $monatJahr.$debitorennummer;
                                        //Get menge und preis from rechnungspositionen with kind_id, monat_jahr
                                        $sqlMengePreis = "SELECT * FROM keis2_rechnungspositionen WHERE kind_id = ".$conn->real_escape_string($rowBut['kind'])." 
                                                                                                            AND monat_jahr = '".$conn->real_escape_string($monat."/".$jahr)."'
                                                                                                            AND id_rechnung IN (SELECT id FROM keis2_rechnung WHERE but_id IS NULL)
                                                                                                            AND update_date IS NULL";
                                        $resultMengePreis = $conn->query($sqlMengePreis);
                                        $preis = 0;
                                        $menge = 0;
                                        if($resultMengePreis->num_rows > 0) {
                                            while($rowMengePreis = $resultMengePreis->fetch_assoc()) {
                                                $preis += $rowMengePreis['gesamtpreis'];
                                                $menge += $rowMengePreis['menge'];
                                            }
                                        }
                                        if($rowBut['eigenanteil_proEssen'] == 1) {
                                            $gesamtpreis = $preis - ($menge * $rowBut['eigenanteil']);
                                        } else if($rowBut['eigenanteil_proEssen'] == 0){
                                            $gesamtpreis = $preis - $rowBut['eigenanteil'];
                                        } else {
                                            $gesamtpreis = 0;
                                        }
                                        
                                        // Create Buchungstext
                                        $buchungstext = createBuchungstext($row['but_id'], $monat, $jahr, $debitorennummer, getNameKinder(0, $rowBut['id']), $gesamtpreis);
    
                                        // Add CSV data
                                        //addCSVData($csvData, $gesamtpreis, "S", $debitorennummer, "20801035",
                                        //        $monatTag, $debitorennummerUndMonatJahr, $buchungstext, "3000", "AK");
                                        $csvArray = array_map("utf8_decode", array($gesamtpreis, "S", "", "", "", "", $debitorennummer, "20801035", "",
                                            $monatTag, $debitorennummerUndMonatJahr, "", "", $buchungstext, "", "", "", "", "", "", "", "", "", "", "", "",
                                            "", "", "", "", "", "", "", "", "", "", "3000", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                            "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                                            "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "AK", "", "", ""));
                                            
                                            array_push($csvData, $csvArray);
                                        
                                        $sql_insert_datev_rueckwirkend = "INSERT INTO keis2_datev_rueckwirkend (but_id, rechnungsdatum) 
                                                                            VALUES (".$conn->real_escape_string($row['but_id']).", STR_TO_DATE('".$conn->real_escape_string($i->format("Y-m-01"))."','%Y-%m-%d'))";
                                        if($conn->query($sql_insert_datev_rueckwirkend)===FALSE) {
                                            throw new Exception("Fehler beim einfügen in die Datenbank: ".$conn->error);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // set the header informations
                    /*header("Content-type: text/csv");
                    header("Content-disposition: attachment; filename=".$filename);
                    header("Content-Length:".filesize($filepath));
                    
                    // Create download dialog
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");
                    
                    // Disable cache
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
                    
                    // read the file
                    readfile(addslashes($filepath));*/
                    
                    //The name of the CSV file that will be downloaded by the user.
                    $fileName = "datev_export.csv";
                    $filePath = '../../temp/'.$fileName;
                    
                    //Set the Content-Type and Content-Disposition headers.
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="'.$fileName.'"');

                    //Open up a PHP output stream using the function fopen.
                    $fp = fopen($filePath, 'w');
                    
                    //Loop through the array containing our CSV data.
                    foreach ($csvData as $row) {
                        //fputcsv formats the array into a CSV format.
                        //It then writes the result to our output stream.
                        fputcsv($fp, $row, ';');
                    }
                    
                    //Close the file handle.
                    fclose($fp);

                    header("Content-Length:".filesize($filePath));
                    
                    // Create download dialog
                    header("Content-Type: application/force-download");
                    header("Content-Type: application/octet-stream");
                    header("Content-Type: application/download");
                    
                    // Disable cache
                    header("Cache-Control: no-cache, must-revalidate");
                    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
                    
                    // read the file
                    readfile(addslashes($filePath));
                    
                } else {
                    echo "Für den Zeitraum wurden keine Rechnungen gefunden!";
                }

            } else {
                throw new Exception('Formularvariablen sind ungültig oder werden nicht empfangen');
            }
        } catch (Exception $ex) {
            echo json_encode(array(
                'error' => array(
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ),
            ));
            exit();
        } finally {
            if ($database != null) {
                $database->closeConnection();
            }
        }
    }
} else {
    header('Location: ' . Constants::getBaseURL() . '/index.php?no_login=set');
}

function addCSVData($csvData, $gesamtbetrag, $buchungskuerzel, $debitorennummer, $gegenkonto,
                    $monatTag, $debitorennummerUndMonatJahr, $buchungstext, $kostenstelle, $herkunftkz) {
    
    $csvArray = array_map("utf8_decode", array($gesamtbetrag, $buchungskuerzel, "", "", "", "", $debitorennummer, $gegenkonto, "",
    $monatTag, $debitorennummerUndMonatJahr, "", "", $buchungstext, "", "", "", "", "", "", "", "", "", "", "", "",
    "", "", "", "", "", "", "", "", "", "", $kostenstelle, "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
    "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "",
                        "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", $herkunftkz, "", "", ""));
    
    array_push($csvData, $csvArray);
}

function createBuchungstext($but_id, $monat, $jahr, $debitorennummer, $nameDerKinder, $gesamtbetrag) {
    
    $buchungstext = "";
    if ($but_id != null && !empty($but_id)) {
        $buchungstext = $monat."-".$jahr." ".$debitorennummer." Fo. LRA BuT Essen ".$nameDerKinder." ".$gesamtbetrag." EUR";
    } else {
        $buchungstext = $monat."-".$jahr." ".$debitorennummer." Essensbeitrag ".$nameDerKinder." ".$gesamtbetrag." EUR";
    }
    
    return $buchungstext;
}

function getDebitorenNummer($benutzer_id, $but_id) {
    
    global $conn;
    $debitorennummer = "";
    
    if ($but_id != null && !empty($but_id)) {
        $sql = "SELECT debitorennummer FROM keis2_but WHERE id = '".$conn->real_escape_string($but_id)."' LIMIT 1";
    } else {
        $sql = "SELECT debitorennummer FROM keis2_benutzer WHERE id = '".$conn->real_escape_string($benutzer_id)."' LIMIT 1";
    }
    
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        
        $debitorennummer = $row['debitorennummer'];
    }

    return $debitorennummer;
}

function getNameKinder($rechnung_id, $but_id) {
    
    global $conn;
    $namekinder = "";
    
    if ($but_id != null && !empty($but_id)) {
        $sql = "SELECT keis2_kind.vorname, keis2_kind.name FROM keis2_but, keis2_kind WHERE keis2_but.id = '".$conn->real_escape_string($but_id)."' AND keis2_but.kind = keis2_kind.id";
    } else {
        $sql = "SELECT keis2_kind.vorname, keis2_kind.name FROM keis2_rechnungspositionen, keis2_kind WHERE keis2_rechnungspositionen.id_rechnung = '".$conn->real_escape_string($rechnung_id)."' AND keis2_rechnungspositionen.kind_id = keis2_kind.id";
    }
    
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        
        if (!empty($namekinder)) {
            $namekinder .= "/";
        }
        $namekinder .= $row["vorname"]." ".$row["name"];
    }
    
    return $namekinder;
}
?>