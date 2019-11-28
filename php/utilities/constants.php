<?php 
class Constants
{
    const BASE_URL = "https://eis2.kinderland-plus.de";
    const ELTERN_BEREICH_URL = "https://eis2.kinderland-plus.de/php/elternbereich/elternbereich.php";
    const ADMIN_BEREICH_URL = "https://eis2.kinderland-plus.de/php/administration/einstellungen.php";
    const KUECHEN_BEREICH_URL = "https://eis2.kinderland-plus.de/php/kuechenbereich/kuechenbereich.php";
    const BUT_URL = "https://eis2.kinderland-plus.de/php/but/but.php";
    const LOGOUT_URL = "https://eis2.kinderland-plus.de/php/login/logout.php";
    const CONTACT_DETAILS_URL = "https://eis2.kinderland-plus.de/php/benutzerverwaltung/benutzer/show_contact_details.php";
    const KINDER_UEBERSCIHT_URL = "https://eis2.kinderland-plus.de/php/AdebisKITA/kinder.php";
    const BENUTZERVERWALTUNG = "https://eis2.kinderland-plus.de/php/benutzerverwaltung/benutzer/benutzer_list.php";
    const DATEV_EXPORT = "https://eis2.kinderland-plus.de/php/datev/datev.php";

    public static function getBaseURL()
    {
        return self::BASE_URL;
    }
    
    public static function getElternBereichURL()
    {
        return self::ELTERN_BEREICH_URL;
    }
    
    public static function getAdminBereichURL()
    {
        return self::ADMIN_BEREICH_URL;
    }
    
    public static function getKuechenBereichURL()
    {
        return self::KUECHEN_BEREICH_URL;
    }

    public static function getButURL()
    {
        return self::BUT_URL;
    }
    
    public static function getLogoutURL()
    {
        return self::LOGOUT_URL;
    }
    
    public static function getContactDetailsURL()
    {
        return self::CONTACT_DETAILS_URL;
    }
    
    public static function getKinderUebersichtURL()
    {
        return self::KINDER_UEBERSCIHT_URL;
    }
    
    public static function getBenutzerverwaltungURL()
    {
        return self::BENUTZERVERWALTUNG;
    }
    
    public static function getDatevExportURL()
    {
        return self::DATEV_EXPORT;
    }
} 
?>