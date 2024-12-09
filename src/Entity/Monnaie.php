<?php

namespace App\Entity;

use App\Repository\MonnaieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonnaieRepository::class)]
class Monnaie
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 25)]
    private ?string $monnaie = null;

    public function getMonnaie(): ?string
    {
        return $this->monnaie;
    }

    public function setMonnaie(string $monnaie): static
    {
        $this->monnaie = $monnaie;

        return $this;
    }

}
