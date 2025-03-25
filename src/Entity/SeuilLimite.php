<?php

namespace App\Entity;

use PhpParser\Node\Expr\Cast\Double;

class SeuilLimite
{
    private ?int $id;
    private ?Double $pourcentage;
    private ?\DateTimeInterface $dateModif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaux(): ?float
    {
        return $this->pourcentage;
    }

    public function setTaux(Double $taux): self
    {
        $this->pourcentage = $taux;
        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModifn(\DateTimeInterface $dateModification): self
    {
        $this->dateModif = $dateModification;
        return $this;
    }
}
