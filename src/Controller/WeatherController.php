<?php

namespace App\Controller;

use App\Entity\History;
use App\Repository\CityRepository;
use App\Repository\HistoryRepository;
use App\Service\WeatherApiService;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var int
     */
    private $nightStartHour;
    /**
     * @var int
     */
    private $nightEndHour;
    /**
     * @var WeatherApiService
     */
    private $weatherApiService;

    public function __construct(WeatherApiService $weatherApiService)
    {
        date_default_timezone_set('Indian/Antananarivo');
        $this->apiKey = '8661357cb962c5f2bc7aaa4f9ec29d0c';
        $this->nightStartHour = 18;
        $this->nightEndHour = 6;
        $this->weatherApiService = $weatherApiService;
    }

    /**
     * @Route("/{id}", name="app_weather")
     * @throws Exception
     */
    public function index(CityRepository $cityRepository,
                          HistoryRepository $historyRepository,
                          EntityManagerInterface $em, $id = null): Response
    {
        $cities = $cityRepository->findAll();
        $defaultCity = $cityRepository->findOneBy(['name' => 'Antananarivo']);
        $currentCity = ($id !== null and $cityRepository->find($id)) ? $cityRepository->find($id) : $defaultCity;
        $histories = $historyRepository->findBy(['city' => $currentCity], ['id' => 'DESC']);

        $cityName = $currentCity->getName();
        $lang = 'fr';
        $weatherData = $this->weatherApiService->fetchWeatherData($cityName, $lang, $this->apiKey);

        $weatherHistory = $this->createHistory($weatherData, $currentCity);
        $em->persist($weatherHistory);
        $em->flush();

        return $this->render('weather/index.html.twig', [
            'cities' => $cities,
            'weatherData' => $weatherHistory,
            'histories' => $histories,
            'currentCity' => $currentCity,
            'weatherIcon' => $this->getWeatherIcon($weatherHistory)
        ]);
    }

    protected function getWeatherData(string $cityName, string $lang, string $apiKey): array
    {
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$cityName&lang=$lang&appid=$apiKey&units=metric";
        $data = file_get_contents($apiUrl);
        return json_decode($data, true);
    }

    /**
     * @throws Exception
     */
    protected function createHistory($weatherData, $city): History
    {
        $main = $weatherData['main'];
        $weather = $weatherData['weather'][0];
        $wind = $weatherData['wind'];
        $timestamp = $weatherData['dt'];
        $currentDate = DateTime::createFromFormat('U', $timestamp);
        $currentDate->setTimezone(new DateTimeZone('Indian/Antananarivo'));

        $history = new History();
        $history->setCity($city)
        ->setDate($currentDate)
        ->setTime($currentDate)
        ->setTemp($main['temp'])
        ->setFeelsLike($main['feels_like'])
        ->setTempMin($main['temp_min'])
        ->setTempMax($main['temp_max'])
        ->setRain(array_key_exists('rain', $weatherData))
        ->setRainQuantity(array_key_exists('rain', $weatherData) ? $weatherData['rain']['1h'] : 0)
        ->setClouds(array_key_exists('clouds', $weatherData) ? $weatherData['clouds']['all'] : 0)
        ->setPressure($main['pressure'])
        ->setHumidity($main['humidity'])
        ->setWindSpeed($wind['speed'])
        ->setWindDirection($wind['deg'])
        ->setWeather($weather['main'])
        ->setDescription($weather['description']);

        return $history;
    }

    protected function getWeatherIcon($weather): string
    {
        $icons = [
            'Clear' => ['day' => 'sun', 'night' => 'moon'],
            'Clouds' => ['day' => 'cloud-sun', 'night' => 'cloud-moon'],
            'Rain' => ['day' => 'cloud-showers-heavy', 'night' => 'cloud-moon-rain'],
            'Snow' => 'snowflake',
            'Thunderstorm' => 'cloud-bolt',
            'Fog' => 'cloud-smog',
            'Mist' => 'cloud-smog',
            'Smoke' => 'cloud-smog',
            'Tornado' => 'tornado'
        ];

        $currentHour = (int)$weather->getTime()->format('H');
        $isNight = ($currentHour >= $this->nightStartHour || $currentHour < $this->nightEndHour);
        $defaultIcon = $isNight ? 'cloud-moon' : 'cloud-sun';

        $weatherType = $weather->getWeather();
        $selectedIcons = is_array($icons[$weatherType]) ? $icons[$weatherType] : ['day' => $icons[$weatherType], 'night' => $icons[$weatherType]];

        return $selectedIcons[$isNight ? 'night' : 'day'] ?? $defaultIcon;
    }

}
