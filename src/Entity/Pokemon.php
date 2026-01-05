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
     * @var Collection<int, Talent>
     */
    #[ORM\ManyToMany(targetEntity: Talent::class, mappedBy: 'pokemons', cascade: ['persist'])]
    private Collection $talent;

    /**
     * @var Collection<int, Type>
     */
    #[ORM\ManyToMany(targetEntity: Type::class, mappedBy: 'pokemonId', cascade: ['persist'])]
    private Collection $types;

    /**
     * @var Collection<int, Pokevolution>
     */
    #[ORM\OneToMany(targetEntity: Pokevolution::class, mappedBy: 'pokemon')]
    private Collection $pokevolutions;

    /**
     * @var Collection<int, Pokevolution>
     */
    #[ORM\OneToMany(targetEntity: Pokevolution::class, mappedBy: 'preEvolution1')]
    private Collection $preEvolution1;

    /**
     * @var Collection<int, Pokevolution>
     */
    #[ORM\OneToMany(targetEntity: Pokevolution::class, mappedBy: 'preEvolution2')]
    private Collection $preEvolution2;

    /**
     * @var Collection<int, Pokevolution>
     */
    #[ORM\OneToMany(targetEntity: Pokevolution::class, mappedBy: 'nextEvolution1')]
    private Collection $nextEvolution1;

    /**
     * @var Collection<int, Pokevolution>
     */
    #[ORM\OneToMany(targetEntity: Pokevolution::class, mappedBy: 'nextEvolution2')]
    private Collection $nextEvolution2;

    public function __construct()
    {
        $this->talent = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->pokevolutions = new ArrayCollection();
        $this->preEvolution1 = new ArrayCollection();
        $this->preEvolution2 = new ArrayCollection();
        $this->nextEvolution1 = new ArrayCollection();
        $this->nextEvolution2 = new ArrayCollection();
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

    /**
     * @return Collection<int, Pokevolution>
     */
    public function getPokevolutions(): Collection
    {
        return $this->pokevolutions;
    }

    public function addPokevolution(Pokevolution $pokevolution): static
    {
        if (!$this->pokevolutions->contains($pokevolution)) {
            $this->pokevolutions->add($pokevolution);
            $pokevolution->setPokemon($this);
        }

        return $this;
    }

    public function removePokevolution(Pokevolution $pokevolution): static
    {
        if ($this->pokevolutions->removeElement($pokevolution)) {
            // set the owning side to null (unless already changed)
            if ($pokevolution->getPokemon() === $this) {
                $pokevolution->setPokemon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pokevolution>
     */
    public function getPreEvolution1(): Collection
    {
        return $this->preEvolution1;
    }

    public function addPreEvolution1(Pokevolution $preEvolution1): static
    {
        if (!$this->preEvolution1->contains($preEvolution1)) {
            $this->preEvolution1->add($preEvolution1);
            $preEvolution1->setPreEvolution1($this);
        }

        return $this;
    }

    public function removePreEvolution1(Pokevolution $preEvolution1): static
    {
        if ($this->preEvolution1->removeElement($preEvolution1)) {
            // set the owning side to null (unless already changed)
            if ($preEvolution1->getPreEvolution1() === $this) {
                $preEvolution1->setPreEvolution1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pokevolution>
     */
    public function getPreEvolution2(): Collection
    {
        return $this->preEvolution2;
    }

    public function addPreEvolution2(Pokevolution $preEvolution2): static
    {
        if (!$this->preEvolution2->contains($preEvolution2)) {
            $this->preEvolution2->add($preEvolution2);
            $preEvolution2->setPreEvolution2($this);
        }

        return $this;
    }

    public function removePreEvolution2(Pokevolution $preEvolution2): static
    {
        if ($this->preEvolution2->removeElement($preEvolution2)) {
            // set the owning side to null (unless already changed)
            if ($preEvolution2->getPreEvolution2() === $this) {
                $preEvolution2->setPreEvolution2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pokevolution>
     */
    public function getNextEvolution1(): Collection
    {
        return $this->nextEvolution1;
    }

    public function addNextEvolution1(Pokevolution $nextEvolution1): static
    {
        if (!$this->nextEvolution1->contains($nextEvolution1)) {
            $this->nextEvolution1->add($nextEvolution1);
            $nextEvolution1->setNextEvolution1($this);
        }

        return $this;
    }

    public function removeNextEvolution1(Pokevolution $nextEvolution1): static
    {
        if ($this->nextEvolution1->removeElement($nextEvolution1)) {
            // set the owning side to null (unless already changed)
            if ($nextEvolution1->getNextEvolution1() === $this) {
                $nextEvolution1->setNextEvolution1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pokevolution>
     */
    public function getNextEvolution2(): Collection
    {
        return $this->nextEvolution2;
    }

    public function addNextEvolution2(Pokevolution $nextEvolution2): static
    {
        if (!$this->nextEvolution2->contains($nextEvolution2)) {
            $this->nextEvolution2->add($nextEvolution2);
            $nextEvolution2->setNextEvolution2($this);
        }

        return $this;
    }

    public function removeNextEvolution2(Pokevolution $nextEvolution2): static
    {
        if ($this->nextEvolution2->removeElement($nextEvolution2)) {
            // set the owning side to null (unless already changed)
            if ($nextEvolution2->getNextEvolution2() === $this) {
                $nextEvolution2->setNextEvolution2(null);
            }
        }

        return $this;
    }
}
