<?php
class Speiseplan
{
    private $id;
    private $datum;
    private $hauptgericht;
    private $beilage;
    private $nachspeise;
    private $einrichtung;
    
    public function __construct($id, $datum, $hauptgericht, $beilage, $nachspeise, $einrichtung)
    {
        $this->id = $id;
        $this->datum = $datum;
        $this->hauptgericht = $hauptgericht;
        $this->beilage = $beilage;
        $this->nachspeise = $nachspeise;
        $this->einrichtung = $einrichtung;
    }
    
    public function get()
    {
        return $this->id;
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getDatum()
    {
        return $this->datum;
    }
    
    public function setDatum($value)
    {
        $this->datum = $value;
    }
    
    public function getHauptgericht()
    {
        return $this->hauptgericht;
    }
    
    public function setHauptgericht($value)
    {
        $this->hauptgericht = $value;
    }
    
    public function getBeilage()
    {
        return $this->beilage;
    }
    
    public function setBeilage($value)
    {
        $this->beilage = $value;
    }
    
    public function getNachspeise()
    {
        return $this->nachspeise;
    }
    
    public function setNachspeise($value)
    {
        $this->nachspeise = $value;
    }
    
    public function getEinrichtung()
    {
        return $this->einrichtung;
    }
    
    public function setEinrichtung($value)
    {
        $this->einrichtung = $value;
    }
}
?>