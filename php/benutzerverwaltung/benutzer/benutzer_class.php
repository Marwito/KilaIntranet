<?php
class Benutzer
{
    private $id;
    private $benutzername;
    private $vorname;
    private $name;
    private $email;
    private $position;
    private $aktiv;
    private $telefon;
    private $mobil;
    private $strasse;
    private $plz;
    private $ort;
    private $einrichtung;
    private $kueche;
    
    /*
    public function __construct($id, $vorname, $name, $email, $position, $aktiv, $einrichtung_kueche)
    {
        $this->id = $id;
        $this->vorname = $vorname;
        $this->name = $name;
        $this->email = $email;
        $this->position = $position;
        $this->aktiv = $aktiv;
        $this->einrichtung_kueche = $einrichtung_kueche;
    }*/
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getVorname()
    {
        return $this->vorname;
    }
    
    public function setVorname($value)
    {
        $this->vorname = $value;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($value)
    {
        $this->email = $value;
    }
    
    public function getPosition()
    {
        return $this->position;
    }
    
    public function setPosition($value)
    {
        $this->position = $value;
    }
    
    public function getAktiv()
    {
        return $this->aktiv;
    }
    
    public function setAktiv($value)
    {
        $this->aktiv = $value;
    }
    
    public function getEinrichtung()
    {
        return $this->einrichtung;
    }
    
    public function setEinrichtung($value)
    {
        $this->einrichtung = $value;
    }
    
    public function getKueche()
    {
        return $this->kueche;
    }
    
    public function setKueche($value)
    {
        $this->kueche = $value;
    }
    
    public function isAdmin($usergroup) {
        if($usergroup == 'Administrator') {
            return true;
        }else{
            return false;
        }
    }
    
    public function isMitarbeiter($usergroup) {
        if($usergroup == 'Mitarbeiter') {
            return true;
        }else{
            return false;
        }
    }
    
    public function isLeiter($usergroup) {
        if($usergroup == 'Leiter') {
            return true;
        }else{
            return false;
        }
    }
    
    public function isCatererKueche($usergroup) {
        if($usergroup == 'Caterer/Küche') {
            return true;
        }else{
            return false;
        }
    }
    
    public function isEltern($usergroup) {
        if($usergroup == 'Eltern') {
            return true;
        }else{
            return false;
        }
    }
}
?>