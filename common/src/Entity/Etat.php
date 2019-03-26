<?php

class Etat
{
	private $ET_Ref;
	private $ET_Intitule;
	
	private $ET_Article;
	
	function __construct($ET_Ref, $ET_Intitule)
	{
		$this->ET_Ref = $ET_Ref;
		$this->ET_Intitule = $ET_Intitule;
	}
	
	public function getET_Ref()
	{
		return $this->ET_Ref;
	}
	
	public function setET_Ref(string $ET_Ref): self
    {
        $this->ET_Ref = $ET_Ref;

        return $this;
    }
	
	public function getET_Intitule()
	{
		return $this->ET_Intitule;
	}
	
	public function setET_Intitule(string $ET_Intitule): self
    {
        $this->ET_Intitule = $ET_Intitule;

        return $this;
    }
	
	/*les collections*/
	
	public function getET_Article(): Collection
    {
        return $this->ET_Article;
    }
    public function addET_Article(ET_Article $ET_Article): self
    {
        if (!$this->ET_Article->contains($ET_Article)) {
            $this->ET_Article[] = $ET_Article;
            $ET_Article->setEtat($this);
        }
        return $this;
    }
    public function removeET_Article(ET_Article $ET_Article): self
    {
        if ($this->ET_Article->contains($ET_Article)) {
            $this->ET_Article->removeElement($ET_Article);
            if ($ET_Article->getEtat() === $this) {
                $ET_Article->setEtat(null);
            }
        }
        return $this;
    }
}
	
?>
