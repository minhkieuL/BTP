<?php

class Emplacement
{
	private $EM_Ref;
	private $EM_Intitule;
	private $EM_Type;
	
	private $EM_Suivi;
	
	
	function __construct($EM_Ref, $EM_Intitule, $EM_Type)
	{
		$this->CA_Ref = $CA_Ref;
		$this->EM_Intitule = $EM_Intitule;
		$this->EM_Type = $EM_Type;
	}
	
	public function getEM_Ref()
	{
		return $this->EM_Ref;
	}
	
	public function setEM_Ref(integer $EM_Ref): self
    {
        $this->EM_Ref = $EM_Ref;

        return $this;
    }
	
	public function getEM_Intitule()
	{
		return $this->EM_Intitule;
	}
	
	public function setEM_Intitule(string $EM_Intitule): self
    {
        $this->EM_Intitule = $EM_Intitule;

        return $this;
    }
	
	public function getEM_Type()
	{
		return $this->EM_Type;
	}
	
	public function setEM_Type(integer $EM_Type): self
    {
        $this->EM_Type = $EM_Type;

        return $this;
    }
	
	/*les collections*/
	
	public function getEM_Suivi(): Collection
    {
        return $this->EM_Suivi;
    }
    public function addEM_Suivi(EM_Suivi $EM_Suivi): self
    {
        if (!$this->EM_Suivi->contains($EM_Suivi)) {
            $this->EM_Suivi[] = $EM_Suivi;
            $EM_Suivi->setEmplacement($this);
        }
        return $this;
    }
    public function removeEM_Suivi(EM_Suivi $EM_Suivi): self
    {
        if ($this->EM_Suivi->contains($EM_Suivi)) {
            $this->EM_Suivi->removeElement($EM_Suivi);
            if ($EM_Suivi->getEmplacement() === $this) {
                $EM_Suivi->setEmplacement(null);
            }
        }
        return $this;
    }
	
	
}

?>