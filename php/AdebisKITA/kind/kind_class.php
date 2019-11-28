<?php
class Kind
{
	private $id;
	private $adebisKitaID;
	private $vorname;
	private $name;
	private $geburtsdatum;
	private $beitragszahler_anrede;
	private $beitragszahler_vorname;
	private $beitragszahler_name;
	private $debitorennummer;
	private $strasse;
	private $plz;
	private $ort;
	private $email;
	private $zuordnung_gruppe;
	private $adebisKitaEinrichtungID;
	private $adebisKitaGruppe;
	private $eltern;

	/*public function __construct($id, $adebisKitaID, $vorname, $name, $geburtsdatum, $beitragszahler_anrede, $beitragszahler_vorname,
	    $beitragszahler_name, $debitorennummer, $anschrift, $email, $zuordnung_gruppe, $adebisKitaEinrichtungID,
	    $adebisKitaGruppe, $eltern)
	{
		$this->id = $id;
		$this->adebisKitaID = $adebisKitaID;
		$this->vorname = $vorname;
		$this->name = $name;
		$this->geburtsdatum = $geburtsdatum;
		$this->beitragszahlerAnrede = $beitragszahler_anrede;
		$this->beitragszahler_vorname = $beitragszahler_vorname;
		$this->beitragszahler_name = $beitragszahler_name;
		$this->debitorennummer = $debitorennummer;
		$this->anschrift = $anschrift;
		$this->email = $email;
		$this->zuordnung_gruppe = $zuordnung_gruppe;
		$this->adebisKitaEinrichtungID = $adebisKitaEinrichtungID;
		$this->adebisKitaGruppe = $adebisKitaGruppe;
		$this->eltern = $eltern;
	}*/
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($value)
	{
		$this->id = $value;
	}
	
	public function getAdebisKitaID()
	{
	    return $this->adebisKitaID;
	}
	
	public function setAdebisKitaID($value)
	{
	    $this->adebisKitaID = $value;
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
	
	public function getGeburtsdatum()
	{
	    return $this->geburtsdatum;
	}
	
	public function setGeburtsdatum($value)
	{
	    $this->geburtsdatum = $value;
	}

	public function getAdebisKitaBzId()
	{
	    return $this->adebisKitaBzId;
	}
	
	public function setAdebisKitaBzId($value)
	{
	    $this->adebisKitaBzId = $value;
	}

	public function getBeitragszahler_anrede()
	{
	    return $this->beitragszahler_anrede;
	}
	
	public function setBeitragszahler_anrede($value)
	{
	    $this->beitragszahler_anrede = $value;
	}
	
	public function getBeitragszahler_vorname()
	{
	    return $this->beitragszahler_vorname;
	}
	
	public function setBeitragszahler_vorname($value)
	{
	    $this->beitragszahler_vorname = $value;
	}
	
	public function getBeitragszahler_name()
	{
	    return $this->beitragszahler_name;
	}
	
	public function setBeitragszahler_name($value)
	{
	    $this->beitragszahler_name = $value;
	}
	
	public function getDebitorennummer()
	{
	    return $this->debitorennummer;
	}
	
	public function setDebitorennummer($value)
	{
	    $this->debitorennummer = $value;
	}
	
	public function getStrasse()
	{
	    return $this->strasse;
	}
	
	public function setStrasse($value)
	{
	    $this->strasse = $value;
	}

	public function getPlz()
	{
	    return $this->plz;
	}
	
	public function setPlz($value)
	{
	    $this->plz = $value;
	}
	
	public function getOrt()
	{
	    return $this->ort;
	}
	
	public function setOrt($value)
	{
	    $this->ort = $value;
	}
	
	public function getZuordnung_gruppe()
	{
	    return $this->zuordnung_gruppe;
	}
	
	public function setZuordnung_gruppe($value)
	{
	    $this->zuordnung_gruppe = $value;
	}
	
	public function getEmail()
	{
	    return $this->email;
	}
	
	public function setEmail($value)
	{
	    $this->email = $value;
	}

	public function getAdebisKitaEinrichtungID()
	{
	    return $this->adebisKitaEinrichtungID;
	}
	
	public function setAdebisKitaEinrichtungID($value)
	{
	    $this->adebisKitaEinrichtungID = $value;
	}
	
	public function getAdebisKitaGruppe()
	{
	    return $this->adebisKitaGruppe;
	}
	
	public function setAdebisKitaGruppe($value)
	{
	    $this->adebisKitaGruppe = $value;
	}
	
	public function getEltern()
	{
	    return $this->eltern;
	}
	
	public function setEltern($value)
	{
	    $this->eltern = $value;
	}
}
?>