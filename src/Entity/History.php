<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 */
class History
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=City::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\Column(type="float")
     */
    private $temp;

    /**
     * @ORM\Column(type="float")
     */
    private $feels_like;

    /**
     * @ORM\Column(type="float")
     */
    private $temp_min;

    /**
     * @ORM\Column(type="float")
     */
    private $temp_max;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rain;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $rain_quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $pressure;

    /**
     * @ORM\Column(type="float")
     */
    private $humidity;

    /**
     * @ORM\Column(type="float")
     */
    private $clouds;

    /**
     * @ORM\Column(type="float")
     */
    private $wind_speed;

    /**
     * @ORM\Column(type="float")
     */
    private $wind_direction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $weather;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTemp(): ?float
    {
        return $this->temp;
    }

    public function setTemp(float $temp): self
    {
        $this->temp = $temp;

        return $this;
    }

    public function getFeelsLike(): ?float
    {
        return $this->feels_like;
    }

    public function setFeelsLike(float $feels_like): self
    {
        $this->feels_like = $feels_like;

        return $this;
    }

    public function getTempMin(): ?float
    {
        return $this->temp_min;
    }

    public function setTempMin(float $temp_min): self
    {
        $this->temp_min = $temp_min;

        return $this;
    }

    public function getTempMax(): ?float
    {
        return $this->temp_max;
    }

    public function setTempMax(float $temp_max): self
    {
        $this->temp_max = $temp_max;

        return $this;
    }

    public function isRain(): ?bool
    {
        return $this->rain;
    }

    public function setRain(bool $rain): self
    {
        $this->rain = $rain;

        return $this;
    }

    public function getRainQuantity(): ?float
    {
        return $this->rain_quantity;
    }

    public function setRainQuantity(?float $rain_quantity): self
    {
        $this->rain_quantity = $rain_quantity;

        return $this;
    }

    public function getPressure(): ?float
    {
        return $this->pressure;
    }

    public function setPressure(float $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?float
    {
        return $this->humidity;
    }

    public function setHumidity(float $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getClouds(): ?float
    {
        return $this->clouds;
    }

    public function setClouds(float $clouds): self
    {
        $this->clouds = $clouds;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->wind_speed;
    }

    public function setWindSpeed(float $wind_speed): self
    {
        $this->wind_speed = $wind_speed;

        return $this;
    }

    public function getWindDirection(): ?float
    {
        return $this->wind_direction;
    }

    public function setWindDirection(float $wind_direction): self
    {
        $this->wind_direction = $wind_direction;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(string $weather): self
    {
        $this->weather = $weather;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
