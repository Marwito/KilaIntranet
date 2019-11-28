<?php
class Zutat
{
    private $id;
    private $name;
    private $einheit;
    
    public function __construct($id, $name, $einheit)
    {
        $this->id = $id;
        $this->name = $name;
        $this->einheit = $einheit;
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
    
    public function getEinheit()
    {
        return $this->einheit;
    }
    
    public function setEinheit($value)
    {
        $this->einheit = $value;
    }
}
?>