<?php

class Categorie
{
	private $CA_Ref;
	private $CA_Nom;
	
	private $CA_RefParent;
	
	private $CA_Categorie;
	private $CA_Article;
	
	function __construct($CA_Ref, $CA_Nom, $CA_RefParent)
	{
		$this->CA_Ref = $CA_Ref;
		$this->CA_Nom = $CA_Nom;
		$this->CA_RefParent = $CA_RefParent;
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
	
	public function getCA_Nom()
	{
		return $this->CA_Nom;
	}
	
	public function setCA_Nom(string $CA_Nom): self
    {
        $this->CA_Nom = $CA_Nom;

        return $this;
    }
	
	public function getCA_RefParent()
	{
		return $this->CA_RefParent;
	}
	
	public function setCA_RefParent(integer $CA_RefParent): self
    {
        $this->CA_RefParent = $CA_RefParent;

        return $this;
    }
	/*les collections*/
	
	public function getAR_Categorie(): Collection
    {
        return $this->CA_Categorie;
    }
    public function addCA_Categorie(CA_Categorie $CA_Categorie): self
    {
        if (!$this->CA_Categorie->contains($CA_Categorie)) {
            $this->CA_Categorie[] = $CA_Categorie;
            $CA_Categorie->setCategorie($this);
        }
        return $this;
    }
    public function removeCA_Categorie(CA_Categorie $CA_Categorie): self
    {
        if ($this->CA_Categorie->contains($CA_Categorie)) {
            $this->CA_Categorie->removeElement($CA_Categorie);
            if ($CA_Categorie->getCategorie() === $this) {
                $CA_Categorie->setCategorie(null);
            }
        }
        return $this;
    }
	
	public function getCA_Article(): Collection
    {
        return $this->CA_Article;
    }
    public function addCA_Article(CA_Article $CA_Article): self
    {
        if (!$this->CA_Article->contains($CA_Article)) {
            $this->CA_Article[] = $CA_Article;
            $CA_Article->setCategorie($this);
        }
        return $this;
    }
    public function removeCA_Article(CA_Article $CA_Article): self
    {
        if ($this->CA_Article->contains($CA_Article)) {
            $this->CA_Article->removeElement($CA_Article);
            if ($CA_Article->getCategorie() === $this) {
                $CA_Article->setCategorie(null);
            }
        }
        return $this;
    }
	
}
	
?>