<?php

class Compte
{
	private $CO_Ref;
	private $CO_Intitule;
	
	private $CO_Utilisateur;
	
	function __construct($CO_Ref, $CO_Intitule)
	{
		$this->CO_Ref = $CO_Ref;
		$this->CO_Intitule = $CO_Intitule;
	}
	
	public function getCO_Ref()
	{
		return $this->CO_Ref;
	}
	
	public function setCO_Ref(integer $CO_Ref): self
    {
        $this->CO_Ref = $CO_Ref;

        return $this;
    }
	
	public function getCO_Intitule()
	{
		return $this->CO_Intitule;
	}
	
	public function setCO_Intitule(string $CO_Intitule): self
    {
        $this->CO_Intitule = $CO_Intitule;

        return $this;
    }
	
	/*les collections*/
	
	public function getCO_Utilisateur(): Collection
    {
        return $this->CO_Utilisateur;
    }
    public function addCO_Utilisateur(CO_Utilisateur $CO_Utilisateur): self
    {
        if (!$this->CO_Utilisateur->contains($CO_Utilisateur)) {
            $this->CO_Utilisateur[] = $CO_Utilisateur;
            $CO_Utilisateur->setCompte($this);
        }
        return $this;
    }
    public function removeCO_Utilisateur(CO_Utilisateur $CO_Utilisateur): self
    {
        if ($this->CO_Utilisateur->contains($CO_Utilisateur)) {
            $this->CO_Utilisateur->removeElement($CO_Utilisateur);
            if ($CO_Utilisateur->getCompte() === $this) {
                $CO_Utilisateur->setCompte(null);
            }
        }
        return $this;
    }
}
	
?>