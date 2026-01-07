<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Pokemon>
     */
    #[ORM\ManyToMany(targetEntity: Pokemon::class, inversedBy: 'types')]
    private Collection $pokemonId;

    public function __construct()
    {
        $this->pokemonId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemonId(): Collection
    {
        return $this->pokemonId;
    }

    public function addPokemonId(Pokemon $pokemonId): static
    {
        if (!$this->pokemonId->contains($pokemonId)) {
            $this->pokemonId->add($pokemonId);
        }

        return $this;
    }

    public function removePokemonId(Pokemon $pokemonId): static
    {
        $this->pokemonId->removeElement($pokemonId);

        return $this;
    }
}
