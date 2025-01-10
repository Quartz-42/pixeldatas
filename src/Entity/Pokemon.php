<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pokedexId = null;

    #[ORM\Column]
    private ?int $generation = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $spriteRegular = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $spriteShiny = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $spriteGmax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $spriteGmaxShiny = null;

    #[ORM\Column]
    private ?int $hp = null;

    #[ORM\Column]
    private ?int $atk = null;

    #[ORM\Column]
    private ?int $def = null;

    #[ORM\Column]
    private ?int $speAtk = null;

    #[ORM\Column]
    private ?int $speDef = null;

    #[ORM\Column]
    private ?int $vit = null;

    #[ORM\Column(length: 255)]
    private ?string $height = null;

    #[ORM\Column(length: 255)]
    private ?string $weight = null;

    /**
     * @var Collection<int, Evolution>
     */
    #[ORM\OneToMany(targetEntity: Evolution::class, mappedBy: 'nextEvolutionId')]
    private Collection $nextEvolution;

    /**
     * @var Collection<int, Evolution>
     */
    #[ORM\OneToMany(targetEntity: Evolution::class, mappedBy: 'preEvolutionId')]
    private Collection $preEvolution;

    /**
     * @var Collection<int, Talent>
     */
    #[ORM\ManyToMany(targetEntity: Talent::class, mappedBy: 'pokemons', cascade: ['persist'])]
    private Collection $talent;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\ManyToMany(targetEntity: Type::class, mappedBy: 'pokemonId', cascade: ['persist'])]
    private Collection $types;

    public function __construct()
    {
        $this->nextEvolution = new ArrayCollection();
        $this->preEvolution = new ArrayCollection();
        $this->talent = new ArrayCollection();
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokedexId(): ?int
    {
        return $this->pokedexId;
    }

    public function setPokedexId(int $pokedexId): static
    {
        $this->pokedexId = $pokedexId;

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): static
    {
        $this->generation = $generation;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
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

    public function getSpriteRegular(): ?string
    {
        return $this->spriteRegular;
    }

    public function setSpriteRegular(string $spriteRegular): static
    {
        $this->spriteRegular = $spriteRegular;

        return $this;
    }

    public function getSpriteShiny(): ?string
    {
        return $this->spriteShiny;
    }

    public function setSpriteShiny(?string $spriteShiny): static
    {
        $this->spriteShiny = $spriteShiny;

        return $this;
    }

    public function getSpriteGmax(): ?string
    {
        return $this->spriteGmax;
    }

    public function setSpriteGmax(?string $spriteGmax): static
    {
        $this->spriteGmax = $spriteGmax;

        return $this;
    }

    public function getSpriteGmaxShiny(): ?string
    {
        return $this->spriteGmaxShiny;
    }

    public function setSpriteGmaxShiny(?string $spriteGmaxShiny): static
    {
        $this->spriteGmaxShiny = $spriteGmaxShiny;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getAtk(): ?int
    {
        return $this->atk;
    }

    public function setAtk(int $atk): static
    {
        $this->atk = $atk;

        return $this;
    }

    public function getDef(): ?int
    {
        return $this->def;
    }

    public function setDef(int $def): static
    {
        $this->def = $def;

        return $this;
    }

    public function getSpeAtk(): ?int
    {
        return $this->speAtk;
    }

    public function setSpeAtk(int $speAtk): static
    {
        $this->speAtk = $speAtk;

        return $this;
    }

    public function getSpeDef(): ?int
    {
        return $this->speDef;
    }

    public function setSpeDef(int $speDef): static
    {
        $this->speDef = $speDef;

        return $this;
    }

    public function getVit(): ?int
    {
        return $this->vit;
    }

    public function setVit(int $vit): static
    {
        $this->vit = $vit;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection<int, Evolution>
     */
    public function getNextEvolution(): Collection
    {
        return $this->nextEvolution;
    }

    public function addNextEvolution(Evolution $nextEvolution): static
    {
        if (!$this->nextEvolution->contains($nextEvolution)) {
            $this->nextEvolution->add($nextEvolution);
            $nextEvolution->setNextEvolutionId($this);
        }

        return $this;
    }

    public function removeNextEvolution(Evolution $nextEvolution): static
    {
        if ($this->nextEvolution->removeElement($nextEvolution)) {
            // set the owning side to null (unless already changed)
            if ($nextEvolution->getNextEvolutionId() === $this) {
                $nextEvolution->setNextEvolutionId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evolution>
     */
    public function getPreEvolution(): Collection
    {
        return $this->preEvolution;
    }

    public function addPreEvolution(Evolution $preEvolution): static
    {
        if (!$this->preEvolution->contains($preEvolution)) {
            $this->preEvolution->add($preEvolution);
            $preEvolution->setPreEvolutionId($this);
        }

        return $this;
    }

    public function removePreEvolution(Evolution $preEvolution): static
    {
        if ($this->preEvolution->removeElement($preEvolution)) {
            // set the owning side to null (unless already changed)
            if ($preEvolution->getPreEvolutionId() === $this) {
                $preEvolution->setPreEvolutionId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Talent>
     */
    public function getTalent(): Collection
    {
        return $this->talent;
    }

    public function addTalent(Talent $talent): static
    {
        if (!$this->talent->contains($talent)) {
            $this->talent->add($talent);
            $talent->addPokemon($this);
        }

        return $this;
    }

    public function removeTalent(Talent $talent): static
    {
        if ($this->talent->removeElement($talent)) {
            $talent->removePokemon($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->addPokemonId($this);
        }

        return $this;
    }

    public function removeType(Type $type): static
    {
        if ($this->types->removeElement($type)) {
            $type->removePokemonId($this);
        }

        return $this;
    }
}
