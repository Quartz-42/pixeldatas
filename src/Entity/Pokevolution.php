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
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pokevolutions')]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'preEvolution1')]
    private ?Pokemon $preEvolution1 = null;

    #[ORM\ManyToOne(inversedBy: 'preEvolution2')]
    private ?Pokemon $preEvolution2 = null;

    #[ORM\ManyToOne(inversedBy: 'nextEvolution1')]
    private ?Pokemon $nextEvolution1 = null;

    #[ORM\ManyToOne(inversedBy: 'nextEvolution2')]
    private ?Pokemon $nextEvolution2 = null;

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

    public function getPreEvolution1(): ?Pokemon
    {
        return $this->preEvolution1;
    }

    public function setPreEvolution1(?Pokemon $preEvolution1): static
    {
        $this->preEvolution1 = $preEvolution1;

        return $this;
    }

    public function getPreEvolution2(): ?Pokemon
    {
        return $this->preEvolution2;
    }

    public function setPreEvolution2(?Pokemon $preEvolution2): static
    {
        $this->preEvolution2 = $preEvolution2;

        return $this;
    }

    public function getNextEvolution1(): ?Pokemon
    {
        return $this->nextEvolution1;
    }

    public function setNextEvolution1(?Pokemon $nextEvolution1): static
    {
        $this->nextEvolution1 = $nextEvolution1;

        return $this;
    }

    public function getNextEvolution2(): ?Pokemon
    {
        return $this->nextEvolution2;
    }

    public function setNextEvolution2(?Pokemon $nextEvolution2): static
    {
        $this->nextEvolution2 = $nextEvolution2;

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
