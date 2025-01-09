<?php

namespace App\Entity;

use App\Repository\EvolutionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvolutionRepository::class)]
class Evolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pokemonId = null;

    #[ORM\ManyToOne(inversedBy: 'nextEvolution')]
    private ?Pokemon $nextEvolutionId = null;

    #[ORM\ManyToOne(inversedBy: 'preEvolution')]
    private ?Pokemon $preEvolutionId = null;

    #[ORM\Column]
    private ?bool $isMegaEvolution = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemonId(): ?int
    {
        return $this->pokemonId;
    }

    public function setPokemonId(int $pokemonId): static
    {
        $this->pokemonId = $pokemonId;

        return $this;
    }

    public function getNextEvolutionId(): ?Pokemon
    {
        return $this->nextEvolutionId;
    }

    public function setNextEvolutionId(?Pokemon $nextEvolutionId): static
    {
        $this->nextEvolutionId = $nextEvolutionId;

        return $this;
    }

    public function getPreEvolutionId(): ?Pokemon
    {
        return $this->preEvolutionId;
    }

    public function setPreEvolutionId(?Pokemon $preEvolutionId): static
    {
        $this->preEvolutionId = $preEvolutionId;

        return $this;
    }

    public function isMegaEvolution(): ?bool
    {
        return $this->isMegaEvolution;
    }

    public function setMegaEvolution(bool $isMegaEvolution): static
    {
        $this->isMegaEvolution = $isMegaEvolution;

        return $this;
    }
}
