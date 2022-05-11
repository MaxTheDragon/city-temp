<?php
/**
 * City Temperature System
 */
namespace App\Entity;

use App\Repository\TemperatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Temperature entity class that describes a record of temperature data for a given city and timestamp.
 * The record contains temperature values in degrees Celsius from two different Weather API services.
 *
 * @package   App\Entity
 * @author    Max Waterman <MaxTheDragon@outlook.com>
 * @copyright 2022 MaxTheDragon
 * @license   Proprietary/Closed Source
 * @ORM\Entity(repositoryClass=TemperatureRepository::class)
 */
class Temperature
{
    /**
     * Temperature ID.
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The City entity this Temperature entity belongs to.
     * 
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="temperatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * The timestamp upon which the temperature readings were retrieved from the API's.
     *
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * The temperature reading from the OpenWeather API service.
     * 
     * @ORM\Column(type="float")
     */
    private $api1Value;

    /**
     * The temperature reading from the WeatherAPI API service.
     * 
     * @ORM\Column(type="float")
     */
    private $api2Value;
    
    /**
     * Creates a new Temperature entity for a given City and with given temperature API values.
     * The timestamp is set to now.
     *
     * @param City $city The City entity this Temperature entity should be associated with.
     * @param float $api1Value The OpenWeather API temperature value for this Temperature entity.
     * @param float $api2Value The WeatherAPI API temperature value for this Temperature entity.
     */
    public function __construct(City $city, float $api1Value, float $api2Value)
    {
        $this->city = $city;
        $this->api1Value = $api1Value;
        $this->api2Value = $api2Value;
        $this->time = new \DateTime();
    }

    /**
     * Returns the temperature ID.
     *
     * @return int The temperature ID or null if it's a new temperature.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the City entity associated with Temperature entity.
     *
     * @return City The City entity associated with Temperature entity.
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * Sets the City entity this Temperature entity is associated with.
     * 
     * @param City $city The City entity this Temperature entity should be associated with.
     * @return Temperature This Temperature entity.
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Returns the timestamp of this Temperature entity.
     *
     * @return \DateTimeInterface The timestamp of this Temperature entity.
     */
    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    /**
     * Sets the City entity this Temperature entity is associated with.
     * 
     * @param City $city The City entity this Temperature entity should be associated with.
     * @return Temperature This Temperature entity.
     */
    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Returns the OpenWeather API temperature value of this Temperature entity.
     * 
     * @return float The OpenWeather API temperature value of this Temperature entity.
     */
    public function getApi1Value(): ?float
    {
        return $this->api1Value;
    }

    /**
     * Sets the OpenWeather API temperature value of this Temperature entity.
     * 
     * @param float $api1Value The OpenWeather API temperature value for this Temperature entity.
     * @return Temperature This Temperature entity.
     */
    public function setApi1Value(float $api1Value): self
    {
        $this->api1Value = $api1Value;

        return $this;
    }

    /**
     * Returns the WeatherAPI API temperature value of this Temperature entity.
     * 
     * @return float The WeatherAPI API temperature value of this Temperature entity.
     */
    public function getApi2Value(): ?float
    {
        return $this->api2Value;
    }

    /**
     * Sets the WeatherAPI API temperature value of this Temperature entity.
     * 
     * @param float $api2Value The WeatherAPI API temperature value for this Temperature entity.
     * @return Temperature This Temperature entity.
     */
    public function setApi2Value(float $api2Value): self
    {
        $this->api2Value = $api2Value;

        return $this;
    }
}
