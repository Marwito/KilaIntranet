<?php 
/*
 * Struktur der Datenschnittstelle aus AdebisKITA
 <?xml version="1.0" encoding="UTF-8" ?>
 <ESSENSBUCHUNGEN>
 <ESSENSBUCHUNG>
 <KIND-ID>81</KIND-ID>
 <NAMEKIND>Altmann</NAMEKIND>
 <VORNAMEKIND>Leon</VORNAMEKIND>
 <GEBURTSDATUM>21.04.2013</GEBURTSDATUM>
 <BEITRAGSZAHLERANREDE>Frau</BEITRAGSZAHLERANREDE>
 <BEITRAGSZAHLERNAME>Altmann</BEITRAGSZAHLERNAME>
 <BEITRAGSZAHLERVORNAME>Heidrun</BEITRAGSZAHLERVORNAME>
 <BEITRAGSZAHLERSTRASSE>Hinterm Mond 9</BEITRAGSZAHLERSTRASSE>
 <BEITRAGSZAHLERPLZORT>12345 Testhausen</BEITRAGSZAHLERPLZORT>
 <BEITRAGSZAHLEREMAIL>info@elogica.de</BEITRAGSZAHLEREMAIL>
 <DEBITORENNUMMER>304212</DEBITORENNUMMER>
 <ESSENKATEGORIE>normal</ESSENKATEGORIE>
 <ESSENSTART>01.09.2018</ESSENSTART>
 <ESSENENDE>31.08.2019</ESSENENDE>
 <DAUERBESTELLUNG>Montag,Dienstag,Mittwoch,Donnerstag,Freitag</DAUERBESTELLUNG>
 <EINRICHTUNGID>AN</EINRICHTUNGID>
 <GRUPPE>Klatschmohn/ Kiga</GRUPPE>
 <EINRICHTUNGNAME>Die Marienkäfer Kindervilla e.K.</EINRICHTUNGNAME>
 <EINRICHTUNGSTRASSE>Löwenzahnweg 13</EINRICHTUNGSTRASSE>
 <EINRICHTUNGPLZORT>12345 Testhausen</EINRICHTUNGPLZORT>
 </ESSENSBUCHUNG>
 <ESSENSBUCHUNGEN>
 */

require_once('../utilities/db_connection.php');
require_once('../AdebisKITA/kind/kind_class.php');

try {

    // Datenbankverbindung herstellen
    $database = new DatabaseConnection();
    $conn = $database->getConn();
    
    // XML-Datei laden
    $xml = simplexml_load_string(file_get_contents("adebiskita-1234567890-20181126.xml"));
    if (false === $xml) {
        throw new Exception("Error: XML-Datei konnte nicht verarbeitet werden!");
    }

    // Inhalte auswerten
    foreach($xml->ESSENSBUCHUNG as $adebisKitaKind) {

        $kind = new Kind();
        $kind->setAdebisKitaId($adebisKitaKind->KINDID);
        $kind->setVorname($adebisKitaKind->VORNAMEKIND);
        $kind->setName($adebisKitaKind->NAMEKIND);
        $kind->setGeburtsdatum($adebisKitaKind->GEBURTSDATUM);
        $kind->setBeitragszahler_anrede($adebisKitaKind->BEITRAGSZAHLERANREDE);
        $kind->setBeitragszahler_vorname($adebisKitaKind->BEITRAGSZAHLERVORNAME);
        $kind->setBeitragszahler_name($adebisKitaKind->BEITRAGSZAHLERNAME);
        $kind->setDebitorennummer($adebisKitaKind->DEBITORENNUMMER);
        $kind->setStrasse($adebisKitaKind->BEITRAGSZAHLERSTRASSE);
        $kind->setPlz($adebisKitaKind->BEITRAGSZAHLERPLZ);
        $kind->setOrt($adebisKitaKind->BEITRAGSZAHLERORT);
        $kind->setEmail($adebisKitaKind->BEITRAGSZAHLEREMAIL);
        $kind->setEssenkategorie($adebisKitaKind->ESSENKATEGORIE);
        
        // Dauerbestellung Montag
        if (strpos($adebisKitaKind->DAUERBESTELLUNG, 'Montag') !== false) {
            $kind->setMontag(1);
        } else {
            $kind->setMontag(0);
        }
        
        // Dauerbestellung Dienstag
        if (strpos($adebisKitaKind->DAUERBESTELLUNG, 'Dienstag') !== false) {
            $kind->setDienstag(1);
        } else {
            $kind->setDienstag(0);
        }
        
        // Dauerbestellung Mittwoch
        if (strpos($adebisKitaKind->DAUERBESTELLUNG, 'Mittwoch') !== false) {
            $kind->setMittwoch(1);
        } else {
            $kind->setMittwoch(0);
        }
        
        // Dauerbestellung Donnerstag
        if (strpos($adebisKitaKind->DAUERBESTELLUNG, 'Donnerstag') !== false) {
            $kind->setDonnerstag(1);
        } else {
            $kind->setDonnerstag(0);
        }
        
        // Dauerbestellung Freitag
        if (strpos($adebisKitaKind->DAUERBESTELLUNG, 'Freitag') !== false) {
            $kind->setFreitag(1);
        } else {
            $kind->setFreitag(0);
        }
        
        $kind->setDauerbestellung($adebisKitaKind->DAUERBESTELLUNG);
        $kind->setEssenstart($adebisKitaKind->ESSENSTART);
        $kind->setEssenende($adebisKitaKind->ESSENENDE);
        $kind->setAdebisKitaGruppe($adebisKitaKind->GRUPPE);
        $kind->setAdebisKitaEinrichtungID($adebisKitaKind->EINRICHTUNGID);

        // ************************* Vorabvalidierungen ***********************************      
        // Kind
        if ($kind->getAdebisKitaId() === null || empty($kind->getAdebisKitaId())) {
            throw new Exception('Es konnte keine Kind-ID in der XML-Datei gefunden werden!');
        }
        
        // Einrichtung
        if ($kind->getAdebisKitaEinrichtungID() === null || empty($kind->getAdebisKitaEinrichtungID())) {
            throw new Exception('Es konnte keine Einrichtungs-ID in der XML-Datei (Kind-ID:'.$kind->getAdebisKitaId().') gefunden werden!');
        }
                
        if (!isset($adebisKitaKind->EINRICHTUNGNAME) || empty($adebisKitaKind->EINRICHTUNGNAME)) {
            throw new Exception('Es wurde kein Einrichtungsname in der XML-Datei (Kind-ID:'.$kind->getAdebisKitaId().') gefunden!');
        }
                
        // Gruppe
        if ($kind->getAdebisKitaGruppe() === null || empty($kind->getAdebisKitaGruppe())) {
            throw new Exception('Es konnte keine Gruppe in der XML-Datei (Kind-ID:'.$kind->getAdebisKitaId().') gefunden werden!');
        }
        
        // Essenkategorie
        if ($kind->getEssenkategorie() === null || empty($kind->getEssenkategorie())) {
            throw new Exception('Es konnte keine Essenkategrie in der XML-Datei (Kind-ID:'.$kind->getAdebisKitaId().') gefunden werden!');
        }
        
        // Beitragszahler vorname
        if ($kind->getBeitragszahler_vorname() === null || empty($kind->getBeitragszahler_vorname())) {
            throw new Exception('Es wurde kein Vorname des Beitragszahlers in der XML-Datei (Kind-ID:'.$kind->getAdebisKitaId().') angegeben!');
        }
        
        // Beitragszahler name
        if ($kind->getBeitragszahler_name() === null || empty($kind->getBeitragszahler_name())) {
            throw new Exception('Es wurde kein Name des Beitragszahlers in der XML-Datei (Kind-ID:'.$kind->getAdebisKitaId().') angegeben!');
        }
        
        // Beitragszahler email
        if ($kind->getEmail() === null || empty($kind->getEmail())) {
            throw new Exception('Es wurde keine E-Mail Adresse des Beitragszahlers in der XML-Datei (Kind-ID:'.$kind->getAdebisKitaId().') angegeben!');
        }
        // ************************* Vorabvalidierungen ***********************************
        
        // ********************** Einrichtungen verarbeiten *******************************
        // Einrichtung laden
        $sql_einrichtung = "SELECT * from keis2_einrichtung where adebisKitaID = '".$conn->real_escape_string($kind->getAdebisKitaEinrichtungID())."'";
        $result_einrichtung = $conn->query($sql_einrichtung);

        // Update or Insert
        if ($result_einrichtung->num_rows > 0) {
            $row_update_einrichtung = $result_einrichtung->fetch_assoc();

            $sql_update_einrichtung = "UPDATE keis2_einrichtung SET 
                                       name='".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGNAME)."', 
                                       strasse='".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGSTRASSE)."',
                                       plz='".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGPLZ)."',
                                       ort='".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGORT)."',
                                       adebisKitaID='".$conn->real_escape_string($kind->getAdebisKitaEinrichtungID())."',
                                       update_date=NOW()
                                       WHERE id=".$conn->real_escape_string($row_update_einrichtung['id'])."";

            if ($conn->query($sql_update_einrichtung) === FALSE) {
                throw new Exception('Die Einrichtung '.$adebisKitaKind->Einrichtungname.' mit der AdebisKITA-ID '.$kind->getAdebisKitaEinrichtungID().' konnte nicht aktualisiert werden');
            }
                
        } else {

            $sql_insert_einrichtung = "INSERT INTO keis2_einrichtung (name, strasse, plz, ort, adebisKitaID, insert_date)
                            		   VALUES ('".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGNAME)."',
                            		   '".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGSTRASSE)."',
                                       '".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGPLZ)."',
                                       '".$conn->real_escape_string($adebisKitaKind->EINRICHTUNGORT)."',
                                       '".$conn->real_escape_string($kind->getAdebisKitaEinrichtungID())."',
                                       NOW())";

            if ($conn->query($sql_insert_einrichtung) === FALSE) {
                throw new Exception('Die Einrichtung '.$adebisKitaKind->Einrichtungname.' mit der AdebisKITA-ID '.$kind->getAdebisKitaEinrichtungID().' konnte nicht eingefügt werden');
            }
        }
        // ********************** Einrichtungen verarbeiten *******************************

        // *************************** Einrichtung ID laden *******************************
        // Einrichtungs-ID laden, da diese in der Gruppe benötigt wird
        $sql_einrichtung_id = "SELECT id from keis2_einrichtung where adebisKitaID = '".$conn->real_escape_string($kind->getAdebisKitaEinrichtungID())."'";
        $result_einrichtung_id = $conn->query($sql_einrichtung_id);

        if ($result_einrichtung_id->num_rows == 0) {
            throw new Exception('Die ID der Einrichtung '.$adebisKitaKind->Einrichtungname.' konnte nicht gefunden werden. Die Einrichtung ist nicht vorhanden!');
        }
        
        $row_einrichtung_id = $result_einrichtung_id->fetch_assoc();
        // *************************** Einrichtung ID laden *******************************

        // *************************** Gruppe verarbeiten *********************************
        $adbGruppeAr = explode("/", $kind->getAdebisKitaGruppe());
        $adbGruppe = $adbGruppeAr[0];
        $adbGruppe = preg_replace('/\s+/', ' ', $adbGruppe);
        
        // Gruppe laden
        $sql_gruppe = "SELECT * from keis2_gruppe where LOWER(name) = LOWER('".$conn->real_escape_string($adbGruppe)."')";
        $result_gruppe = $conn->query($sql_gruppe);
        
        // Update or Insert
        if ($result_gruppe->num_rows > 0) {

            $sql_update_gruppe = "UPDATE keis2_gruppe SET
                                  einrichtung='".$conn->real_escape_string($row_einrichtung_id['id'])."',
                                  update_date=NOW()
                                  WHERE name='".$conn->real_escape_string($adbGruppe)."'";

            if ($conn->query($sql_update_gruppe) === FALSE) {
                throw new Exception('Die Gruppe '.$kind->getAdebisKitaGruppe().' konnte nicht aktualisiert werden');
            }
            
        } else {
            
            $sql_insert_gruppe = "INSERT INTO keis2_gruppe (name, einrichtung, insert_date)
        		                  VALUES ('".$conn->real_escape_string($adbGruppe)."',
        		                          '".$conn->real_escape_string($row_einrichtung_id['id'])."',
                                            NOW())";

            if ($conn->query($sql_insert_gruppe) === FALSE) {
                throw new Exception('Die Gruppe '.$kind->getAdebisKitaGruppe().' konnte nicht eingefügt werden');
            }
        }
        // *************************** Gruppe verarbeiten *********************************
        
        // ************************** Gruppen ID laden *****************************
        // Essenskategorie-ID laden, da diese im Kind verwendet wird
        $sql_gruppe_id = "SELECT id from keis2_gruppe where LOWER(name) = LOWER('".$conn->real_escape_string($adbGruppe)."')";
        $result_gruppe_id = $conn->query($sql_gruppe_id);
        
        if ($result_gruppe_id->num_rows == 0) {
            throw new Exception('Die ID Gruppe '.$adbGruppe.' konnte nicht gefunden werden. Die Gruppe ist nicht vorhanden!');
        }
        
        $row_gruppe_id = $result_gruppe_id->fetch_assoc();
        // ************************** Gruppen ID laden *****************************
        
        // ************************** Essenkategorie verarbeiten **************************
        // Gruppe laden
        $sql_essenkategorie = "SELECT * from keis2_essenkategorie where LOWER(kategorie) = LOWER('".$conn->real_escape_string($kind->getEssenkategorie())."')";
        $result_essenkategorie = $conn->query($sql_essenkategorie);

        // Immer Insert Insert
        if ($result_essenkategorie->num_rows == 0) {
            
            $sql_insert_essenkategorie = "INSERT INTO keis2_essenkategorie (kategorie, insert_date)
        		                  VALUES ('".$conn->real_escape_string($kind->getEssenkategorie())."',
                                            NOW())";
            
            if ($conn->query($sql_insert_essenkategorie) === FALSE) {
                throw new Exception('Die Essenkategorie '.$kind->getEssenkategorie().' konnte nicht eingefügt werden');
            }
        }
        // ************************** Essenkategorie verarbeiten **************************

        // ************************** Essenkategorie ID laden *****************************
        // Essenskategorie-ID laden, da diese im Kind verwendet wird
        $sql_essenkategorie_id = "SELECT id from keis2_essenkategorie where LOWER(kategorie) = LOWER('".$conn->real_escape_string($kind->getEssenkategorie())."')";
        $result_essenskategorie_id = $conn->query($sql_essenkategorie_id);
        
        if ($result_essenskategorie_id->num_rows == 0) {
            throw new Exception('Die ID Essenskategorie '.$kind->getEssenkategorie().' konnte nicht gefunden werden. Die Essenkategorie ist nicht vorhanden!');
        }
        
        $row_essenkategorie_id = $result_essenskategorie_id->fetch_assoc();
        // ************************** Essenkategorie ID laden *****************************
        
        // ***************************** Benutzer verarbeiten *****************************
        // Benutzer
        $sql_benutzer = "SELECT * from keis2_benutzer where LOWER(email) = LOWER('".$conn->real_escape_string($kind->getEmail())."')";
        $result_benutzer = $conn->query($sql_benutzer);
        
        // Update or Insert
        if ($result_benutzer->num_rows > 0) {
            
            $sql_update_benutzer = "UPDATE keis2_benutzer SET
                                vorname='".$conn->real_escape_string($kind->getBeitragszahler_vorname())."',
                                name='".$conn->real_escape_string($kind->getBeitragszahler_name())."',
                                strasse='".$conn->real_escape_string($kind->getStrasse())."',
                                plz='".$conn->real_escape_string($kind->getPlz())."',
                                ort='".$conn->real_escape_string($kind->getOrt())."',
                                email='".$conn->real_escape_string($kind->getEmail())."',
                                update_date=NOW()
                                WHERE LOWER(email)=LOWER('".$conn->real_escape_string($kind->getAdebisKitaId())."')";
            
            if ($conn->query($sql_update_benutzer) === FALSE) {
                throw new Exception('Der Benutzer '.$kind->getEmail().' '.$kind->getBeitragszahler_vorname().' '.$kind->getBeitragszahler_name().' konnte nicht aktualisiert werden');
            }
            
        } else {
            
            // Benutzername erstellen
            $benutzername_tmp = str_replace(' ','',$kind->getBeitragszahler_vorname())[0];
            $benutzername_tmp = $benutzername_tmp.str_replace(' ','',$kind->getBeitragszahler_name());
            $benutzername_tmp = strtolower($benutzername_tmp);

            // Passwort erstellen
            $benutzerpasswd_tmp =  password_hash(generatePassword(10, 1, 1, true), PASSWORD_ARGON2I);
            
            $sql_insert_benutzer = "INSERT INTO keis2_benutzer (benutzername, passwort, vorname, name, aktiv, position, email,
                                                        strasse, plz, ort, insert_date)
                                       VALUES ('".$conn->real_escape_string($benutzername_tmp)."',
                            		   '".$conn->real_escape_string($benutzerpasswd_tmp)."',
                                       '".$conn->real_escape_string($kind->getBeitragszahler_vorname())."',
                                       '".$conn->real_escape_string($kind->getBeitragszahler_name())."',
                                       1,
                                       2,
                                       '".$conn->real_escape_string($kind->getEmail())."',
                                       '".$conn->real_escape_string($kind->getStrasse())."',
                                       '".$conn->real_escape_string($kind->getPlz())."',
                                       '".$conn->real_escape_string($kind->getOrt())."',
                                       NOW())";
            
            if ($conn->query($sql_insert_benutzer) === FALSE) {
                throw new Exception('Der Benutzer '.$kind->getEmail().' '.$kind->getBeitragszahler_vorname().' '.$kind->getBeitragszahler_name().' konnte nicht hinzugefügt werden');
            }
        }
        // ***************************** Benutzer verarbeiten *****************************

        // ***************************** Benutzer ID laden ********************************
        // Benutzer-ID laden, da diese im Kind verwendet wird
        $sql_benutzer_id = "SELECT id from keis2_benutzer where LOWER(email) = LOWER('".$conn->real_escape_string($kind->getEmail())."')";
        $result_benutzer_id = $conn->query($sql_benutzer_id);
        
        if ($result_benutzer_id->num_rows == 0) {
            throw new Exception('Die ID des Benutzers '.$kind->getBeitragszahler_vorname().' '.$kind->getBeitragszahler_name().' konnte nicht gefunden werden. Der Benutzer ist nicht vorhanden!');
        }
        
        $row_benutzer_id = $result_benutzer_id->fetch_assoc();
        // ***************************** Benutzer ID laden ********************************
        
        // ***************************** Kind verarbeiten *********************************
        // Kind
        $sql_kind = "SELECT * from keis2_kind where adebisKitaID = '".$conn->real_escape_string($kind->getAdebisKitaId())."'";
        $result_kind = $conn->query($sql_kind);
        
        // Update or Insert
        if ($result_kind->num_rows > 0) {
            
            $sql_update_kind = "UPDATE keis2_kind SET
                                vorname='".$conn->real_escape_string($kind->getVorname())."',
                                name='".$conn->real_escape_string($kind->getName())."',
                                geburtsdatum='".$conn->real_escape_string($kind->getGeburtsdatum())."',
                                beitragszahler_anrede='".$conn->real_escape_string($kind->getBeitragszahler_anrede())."',
                                beitragszahler_vorname='".$conn->real_escape_string($kind->getBeitragszahler_vorname())."',
                                beitragszahler_name='".$conn->real_escape_string($kind->getBeitragszahler_name())."',
                                debitorennummer='".$conn->real_escape_string($kind->getDebitorennummer())."',
                                strasse='".$conn->real_escape_string($kind->getStrasse())."',
                                plz='".$conn->real_escape_string($kind->getPlz())."',
                                ort='".$conn->real_escape_string($kind->getOrt())."',
                                email='".$conn->real_escape_string($kind->getEmail())."',
                                essenkategorie='".$conn->real_escape_string($row_essenkategorie_id['id'])."',
                                montag='".$conn->real_escape_string($kind->getMontag())."',
                                dienstag='".$conn->real_escape_string($kind->getDienstag())."',
                                mittwoch='".$conn->real_escape_string($kind->getMittwoch())."',
                                donnerstag='".$conn->real_escape_string($kind->getDonnerstag())."',
                                freitag='".$conn->real_escape_string($kind->getFreitag())."',
                                essenstart='".$conn->real_escape_string($kind->getEssenstart())."',
                                essenende='".$conn->real_escape_string($kind->getEssenende())."',
                                dauerbestellung='".$conn->real_escape_string($kind->getDauerbestellung())."',
                                adebisKitaEinrichtungID='".$conn->real_escape_string($kind->getAdebisKitaEinrichtungID())."',
                                adebisKitaGruppe='".$conn->real_escape_string($kind->getAdebisKitaGruppe())."',
                                zuordnung_gruppe='".$conn->real_escape_string($row_gruppe_id['id'])."',
                                update_date=NOW()
                                WHERE adebisKitaID='".$conn->real_escape_string($kind->getAdebisKitaId())."'";

            if ($conn->query($sql_update_kind) === FALSE) {
                throw new Exception('Das Kind '.$kind->getAdebisKitaId().' '.$kind->getVorname().' '.$kind->getName().' konnte nicht aktualisiert werden');
            }
            
        } else {
            
            $sql_insert_kind = "INSERT INTO keis2_kind (vorname, name, geburtsdatum, beitragszahler_anrede, beitragszahler_vorname, beitragszahler_name, debitorennummer,
                                                        strasse, plz, ort, email, essenkategorie, montag, dienstag, mittwoch, donnerstag, freitag, essenstart, essenende,
                                                        dauerbestellung, adebisKitaID, adebisKitaEinrichtungID, adebisKitaGruppe, eltern, zuordnung_gruppe, insert_date) 
        		VALUES ('".$conn->real_escape_string($kind->getVorname())."',
                		'".$conn->real_escape_string($kind->getName())."',
                        '".$conn->real_escape_string($kind->getGeburtsdatum())."',
                        '".$conn->real_escape_string($kind->getBeitragszahler_anrede())."',
                        '".$conn->real_escape_string($kind->getBeitragszahler_vorname())."',
                        '".$conn->real_escape_string($kind->getBeitragszahler_name())."',
                        '".$conn->real_escape_string($kind->getDebitorennummer())."',
                        '".$conn->real_escape_string($kind->getStrasse())."',
                        '".$conn->real_escape_string($kind->getPlz())."',
                        '".$conn->real_escape_string($kind->getOrt())."',
                        '".$conn->real_escape_string($kind->getEmail())."',
                        '".$conn->real_escape_string($row_essenkategorie_id['id'])."',
                        '".$conn->real_escape_string($kind->getMontag())."',
                        '".$conn->real_escape_string($kind->getDienstag())."',
                        '".$conn->real_escape_string($kind->getMittwoch())."',
                        '".$conn->real_escape_string($kind->getDonnerstag())."',
                        '".$conn->real_escape_string($kind->getFreitag())."',
                        '".$conn->real_escape_string($kind->getEssenstart())."',
                        '".$conn->real_escape_string($kind->getEssenende())."',
                        '".$conn->real_escape_string($kind->getDauerbestellung())."',
                        '".$conn->real_escape_string($kind->getAdebisKitaId())."',
                        '".$conn->real_escape_string($kind->getAdebisKitaEinrichtungID())."',
                        '".$conn->real_escape_string($kind->getAdebisKitaGruppe())."',
                        '".$conn->real_escape_string($row_benutzer_id['id'])."',
                        '".$conn->real_escape_string($row_gruppe_id['id'])."',
                        NOW())";

            if ($conn->query($sql_insert_kind) === FALSE) {
                throw new Exception('Das Kind '.$kind->getAdebisKitaId().' '.$kind->getVorname().' '.$kind->getName().' konnte nicht eingefügt werden');
            }
        }
        
        // ***************************** Kind verarbeiten *********************************
    }
    
    $database->closeConnection();
    
    // ******************************** Datei verschieben *********************************
    
    exit();
} catch (Exception $exception) {
    $to = "info@elogica.de";
    $subject = "Fehlermeldung in AdebisKITA - EIS Schnittstelle";
    $txt = "
            <html>
            <head>
            <title>Es ist ein Fehler aufgetreten</title>
            </head>
            <body>
            Hallo, <br>
            <p>
            bei der Abarbeitung der XML-Datei aus der Schnittstelle AdebisKITA - EIS ist ein Fehleraufgetreten.
            <br>
            <br>
            <b>Fehlermeldung:</b><br>
            ".$exception."<br><br>
            Mit freundlichen Grüßen
            </body>
            </html>
    ";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@kinderland-plus.de" . "\r\n";
    mail($to,$subject,$txt,$headers);
}


function generatePassword ( $passwordlength = 8,
                            $numNonAlpha = 0,
                            $numNumberChars = 0,
                            $useCapitalLetter = false ) {
        
    $numberChars = '0123456789';
    $specialChars = '@$!%*#?&';
    $secureChars = 'abcdefghjklmnopqrstuvwxyz';
    $stack = '';
    
    // Stack für Password-Erzeugung füllen
    $stack = $secureChars;
    
    if ( $useCapitalLetter == true ) {
        $stack .= strtoupper ( $secureChars );
        
        $count = $passwordlength - $numNonAlpha - $numNumberChars;
        $temp = str_shuffle ( $stack );
        $stack = substr ( $temp , 0 , $count );
        
        if ( $numNonAlpha > 0 ) {
            $temp = str_shuffle ( $specialChars );
            $stack .= substr ( $temp , 0 , $numNonAlpha );
        }
        
        if ( $numNumberChars > 0 ) {
            $temp = str_shuffle ( $numberChars );
            $stack .= substr ( $temp , 0 , $numNumberChars );
        }
        
        
        // Stack durchwürfeln
        $stack = str_shuffle ( $stack );
        
        // Rückgabe des erzeugten Passwort
        return $stack;
    }
}
?>