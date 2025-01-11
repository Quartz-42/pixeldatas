<?php

namespace App\Entity;

use App\Repository\PokevolutionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokevolutionRepository::class)]
class Pokevolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pokevolutions')]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'preEvolution')]
    private ?Pokemon $preEvolution = null;

    #[ORM\ManyToOne(inversedBy: 'nextEvolution')]
    private ?Pokemon $nextEvolution = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isMegaEvolution = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): static
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getPreEvolution(): ?Pokemon
    {
        return $this->preEvolution;
    }

    public function setPreEvolution(?Pokemon $preEvolution): static
    {
        $this->preEvolution = $preEvolution;

        return $this;
    }

    public function getNextEvolution(): ?Pokemon
    {
        return $this->nextEvolution;
    }

    public function setNextEvolution(?Pokemon $nextEvolution): static
    {
        $this->nextEvolution = $nextEvolution;

        return $this;
    }

    public function isMegaEvolution(): ?bool
    {
        return $this->isMegaEvolution;
    }

    public function setMegaEvolution(?bool $isMegaEvolution): static
    {
        $this->isMegaEvolution = $isMegaEvolution;

        return $this;
    }
}
