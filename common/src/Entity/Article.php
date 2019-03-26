<?php

class Article
{
	private $AR_Ref;
	private $AR_Design ;
	private $AR_Sommeil ;
	private $AR_DateCreation ;
	private $AR_Etat ;
	private $AR_DateDernierEntretient ;
	private $AR_DateDerniereReparation ;
	
	private $CA_Ref ;
	private $EM_Ref;
	private $EM_RefRep;
	
	
	private $AR_Suivi;
	
	function __construct($AR_Ref, $AR_Design, $AR_Sommeil, $AR_DateCreation, $AR_Etat, $AR_DateDernierEntretient, $AR_DateDerniereReparation, $CA_Ref, $EM_Ref, $EM_RefRep)
	{
		$this->AR_Ref = $AR_Ref;
		$this->AR_Design = $AR_Design;
		$this->AR_Sommeil = $AR_Sommeil;
		$this->AR_DateCreation = $AR_DateCreation;
		$this->AR_Etat = $AR_Etat;
		$this->AR_DateDernierEntretient = $AR_DateDernierEntretient;
		$this->AR_DateDerniereReparation = $AR_DateDerniereReparation;
		$this->CA_Ref = $CA_Ref;
		$this->EM_Ref = $EM_Ref;
		$this->EM_RefRep = $EM_RefRep;
	}
	
	public function getAR_Ref()
	{
		return $this->AR_Ref;
	}
	
	public function setAR_Ref(string $AR_Ref): self
    {
        $this->AR_Ref = $AR_Ref;

        return $this;
    }
	
	public function getAR_Design()
	{
		return $this->AR_Design;
	}
	
	public function setAR_Design(string $AR_Design): self
    {
        $this->AR_Design = $AR_Design;

        return $this;
    }
	
	public function getAR_Sommeil()
	{
		return $this->AR_Sommeil;
	}
	
	public function setAR_Sommeil(boolean $AR_Sommeil): self
    {
        $this->AR_Sommeil = $AR_Sommeil;

        return $this;
    }
	
	public function getAR_DateCreation()
	{
		return $this->AR_DateCreation;
	}
	
	public function setAR_DateCreation(DateTimeInterface $AR_DateCreation): self
    {
        $this->AR_DateCreation = $AR_DateCreation;

        return $this;
    }
	
	public function getAR_Etat()
	{
		return $this->AR_Etat;
	}
	
	public function setAR_Etat(string $AR_Etat): self
    {
        $this->AR_Etat = $AR_Etat;

        return $this;
    }
	
	public function getAR_DateDernierEntretient()
	{
		return $this->AR_DateDernierEntretient;
	}
	
	public function setAR_DateDernierEntretient(DateTimeInterface $AR_DateDernierEntretient): self
    {
        $this->AR_DateDernierEntretient = $AR_DateDernierEntretient;

        return $this;
    }
	public function getAR_DateDerniereReparation()
	{
		return $this->AR_DateDerniereReparation;
	}
	
	public function setAR_DateDerniereReparation(DateTimeInterface $AR_DateDerniereReparation): self
    {
        $this->AR_DateDerniereReparation = $AR_DateDerniereReparation;

        return $this;
    }
	public function getCA_Ref()
	{
		return $this->CA_Ref;
	}
	
	public function setCA_Ref(integer $CA_Ref): self
    {
        $this->CA_Ref = $CA_Ref;

        return $this;
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
	public function getEM_RefRep()
	{
		return $this->EM_RefRep;
	}
	
	public function setEM_RefRep(integer $EM_RefRep): self
    {
        $this->EM_RefRep = $EM_RefRep;

        return $this;
    }
	/*les collections*/
	
	public function getAR_Suivi(): Collection
    {
        return $this->AR_Suivi;
    }
    public function addAR_Suivi(AR_Suivi $AR_Suivi): self
    {
        if (!$this->AR_Suivi->contains($AR_Suivi)) {
            $this->AR_Suivi[] = $AR_Suivi;
            $AR_Suivi->setArticle($this);
        }
        return $this;
    }
    public function removeAR_Suivi(AR_Suivi $AR_Suivi): self
    {
        if ($this->AR_Suivi->contains($AR_Suivi)) {
            $this->AR_Suivi->removeElement($AR_Suivi);
            if ($AR_Suivi->getArticle() === $this) {
                $AR_Suivi->setArticle(null);
            }
        }
        return $this;
    }
}
?>
