<?php
/**
 * City Temperature System
 */
namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * City entity class that describes a city of which temperature data can be retrieved and stored.
 *
 * @package   App\Entity
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2022 MaxTheDragon
 * @license   Proprietary/Closed Source
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * City ID.
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The City entity's name.
     * 
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * The Temperature entities associated with this City entity.
     *
     * @ORM\OneToMany(targetEntity=Temperature::class, mappedBy="city", orphanRemoval=true)
     */
    private $temperatures;

    /**
     * Creates a new City entity object.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->temperatures = new ArrayCollection();
    }

    /**
     * Returns the city ID.
     *
     * @return int The city ID or null if it's a new city.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the city name.
     *
     * @return string The city name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the city name to a given value.
     *
     * @param string $name The city name.
     * @return City This City entity.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Returns the Temperature entities associated with this City entity.
     *
     * @return Collection<int, Temperature> The Temperature entities associated with this City entity.
     */
    public function getTemperatures(): Collection
    {
        return $this->temperatures;
    }

    /**
     * Adds a Temperature entity to this City entity.
     *
     * @param Temperature The Temperature entity to add to this City entity.
     * @return City This City entity.
     */
    public function addTemperature(Temperature $temperature): self
    {
        if (!$this->temperatures->contains($temperature)) {
            $this->temperatures[] = $temperature;
            $temperature->setCity($this);
        }

        return $this;
    }

    /**
     * Removes a Temperature entity from this City entity.
     *
     * @param Temperature The Temperature entity to remove from this City entity.
     * @return City This City entity.
     */
    public function removeTemperature(Temperature $temperature): self
    {
        if ($this->temperatures->removeElement($temperature)) {
            // set the owning side to null (unless already changed)
            if ($temperature->getCity() === $this) {
                $temperature->setCity(null);
            }
        }

        return $this;
    }
}
