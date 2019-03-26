<?php

class Suivi
{
	private $SU_Id;
	private $SU_DateMouvement;
	
	private $AR_Ref;
	private $EM_Ref;
	private $UT_Ref;
	
	function __construct($SU_Id, $SU_DateMouvement, $AR_Ref, $EM_Ref, $UT_Ref)
	{
		$this->SU_Id = $SU_Id;
		$this->SU_DateMouvement = $SU_DateMouvement;
		$this->AR_Ref = $AR_Ref;
		$this->EM_Ref = $EM_Ref;
		$this->UT_Ref = $UT_Ref;
	}
	
	public function getSU_Id()
	{
		return $this->SU_Id;
	}
	
	public function setSU_Id(integer $SU_Id): self
    {
        $this->SU_Id = $SU_Id;

        return $this;
    }
	
	public function getSU_DateMouvement()
	{
		return $this->SU_DateMouvement;
	}
	
	public function setSU_DateMouvement(?\DateTimeInterface $SU_DateMouvement): self
    {
        $this->SU_DateMouvement = $SU_DateMouvement;

        return $this;
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
	
	public function getEM_Ref()
	{
		return $this->EM_Ref;
	}
	
	public function setEM_Ref(integer $EM_Ref): self
    {
        $this->EM_Ref = $EM_Ref;

        return $this;
    }
	
	public function getUT_Ref()
	{
		return $this->UT_Ref;
	}
	
	public function setUT_Ref(integer $UT_Ref): self
    {
        $this->UT_Ref = $UT_Ref;

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