<?php

class Utilisateur
{
	private $UT_Ref;
	private $UT_Mail;
	private $UT_Nom;
	private $UT_Prenom;
	private $UT_DateCreation;
	private $UT_Password;
	private $UT_UniqId;
	private $UT_Sommeil;

	private $UT_Type;
	
	private $UT_Suivi;

	function __construct($UT_Ref, $UT_Mail, $UT_Nom, $UT_Prenom, $UT_DateCreation, $UT_Password, $UT_UniqId, $UT_Sommeil, $UT_Type)
	{
		$this->UT_Ref = $UT_Ref;
		$this->UT_Mail = $UT_Mail;
		$this->UT_Nom = $UT_Nom;
		$this->UT_Prenom = $UT_Prenom;
		$this->UT_DateCreation = $UT_DateCreation;
		$this->UT_Password = $UT_Password;
		$this->UT_UniqId = $UT_UniqId;
		$this->UT_Sommeil = $UT_Sommeil;
		$this->UT_Type = $UT_Type;
	}
	
	public function getUT_Ref()
	{
		return $this->UT_Ref;
	}
	
	public function getUT_Mail()
	{
		return $this->UT_Mail;
	}
	
	public function getUT_Nom()
	{
		return $this->UT_Nom;
	}
	
	public function getUT_Prenom()
	{
		return $this->UT_Prenom;
	}
	
	public function getUT_DateCreation()
	{
		return $this->UT_DateCreation;
	}
	
	public function getUT_Password()
	{
		return $this->UT_Password;
	}
	
	public function getUT_UniqIl()
	{
		return $this->UT_UniqIl;
	}
	
	public function getUT_Sommeil()
	{
		return $this->UT_Sommeil;
	}
	
	public function getUT_Type()
	{
		return $this->UT_Type;
	}
	
	public function setUT_Ref(integer $UT_Ref): self
    {
        $this->UT_Ref = $UT_Ref;

        return $this;
    }
	
	public function setUT_Mail(string $UT_Mail): self
    {
        $this->UT_Mail = $UT_Mail;

        return $this;
    }
	
	public function setUT_Nom(string $UT_Nom): self
    {
        $this->UT_Nom = $UT_Nom;

        return $this;
    }
	
	public function setUT_Prenom(string $UT_Prenom): self
    {
        $this->UT_Prenom = $UT_Prenom;

        return $this;
    }
	
	public function setUT_DateCreation(DateTimeInterface $UT_DateCreation): self
    {
        $this->UT_DateCreation = $UT_DateCreation;

        return $this;
    }
	
	public function setUT_Password(string $UT_Password): self
    {
        $this->UT_Password = $UT_Password;

        return $this;
    }
	
	public function setUT_UniqId(string $UT_UniqId): self
    {
        $this->UT_UniqIl = $UT_UniqIl;

        return $this;
    }
	
	public function setUT_Sommeil(boolean $UT_Sommeil): self
    {
        $this->UT_Sommeil = $UT_Sommeil;

        return $this;
    }
	
	public function setUT_Type(integer $UT_Type): self
    {
        $this->UT_Type = $UT_Type;

        return $this;
    }
	
	/*les collections*/
	
	public function getUT_Suivi(): Collection
    {
        return $this->UT_Suivi;
    }
    public function addUT_Suivi(UT_Suivi $UT_Suivi): self
    {
        if (!$this->UT_Suivi->contains($UT_Suivi)) {
            $this->UT_Suivi[] = $UT_Suivi;
            $UT_Suivi->setUtilisateur($this);
        }
        return $this;
    }
    public function removeUT_Suivi(UT_Suivi $UT_Suivi): self
    {
        if ($this->UT_Suivi->contains($UT_Suivi)) {
            $this->UT_Suivi->removeElement($UT_Suivi);
            if ($UT_Suivi->getUtilisateur() === $this) {
                $UT_Suivi->setUtilisateur(null);
            }
        }
        return $this;
    }
}
?>
	
	
