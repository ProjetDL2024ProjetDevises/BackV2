<?php

namespace App\Entity;

use App\Repository\DonneeRepository;
use Doctrine\ORM\Mapping as ORM;
use \DateTimeInterface;

#[ORM\Entity(repositoryClass: DonneeRepository::class)]
class Donnee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 25)]
    private string $monnaie;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_change;

    #[ORM\Column(type: "decimal", precision: 25, scale: 20)]
    private string $taux_change;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonnaie(): ?string
    {
        return $this->monnaie;
    }
    public function setMonnaie(string $monnaie): ?self
    {
        $this->monnaie = $monnaie;
        return $this;
    }

    public function getDate_change(): ?\DateTimeInterface
    {
        return $this->date_change;
    }
    public function setDate_change(\DateTimeInterface $date): ?self
    {
        $this->date_change = $date;
        return $this;
    }

    public function getTaux_change(): ?string
    {
        return $this->taux_change;
    }
    public function setTaux_change(string $taux): self
    {
        $this->taux_change = $taux;
        return $this;
    }
}
