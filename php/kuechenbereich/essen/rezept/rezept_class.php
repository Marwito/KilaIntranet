<?php
class Rezept
{
    private $id;
    private $name;
    private $zutaten;
    
    public function __construct($id, $name, $zutaten)
    {
        $this->id = $id;
        $this->name = $name;
        $this->zutaten = $zutaten;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($value)
    {
        $this->name = $value;
    }
    
    public function getZutaten()
    {
        return $this->zutaten;
    }
    
    public function setZutaten($value)
    {
        $this->zutaten = $value;
    }
}
?>