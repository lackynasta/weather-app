<?php

namespace App\Controller;

use App\Entity\History;
use App\Repository\CityRepository;
use App\Repository\HistoryRepository;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    public function __construct()
    {
        date_default_timezone_set('Indian/Antananarivo');
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

        $apiKey = '8661357cb962c5f2bc7aaa4f9ec29d0c';
        $cityName = $currentCity->getName();
        $lang = 'fr';

        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$cityName&lang=$lang&appid=$apiKey&units=metric";

        $data = file_get_contents($apiUrl);

        // Convertissez les données JSON en tableau associatif
        $weatherData = json_decode($data, true);

//        dump($weatherData);
//        dump($data);
        $weatherHistory = self::setHistory($weatherData, $currentCity);

        $em->persist($weatherHistory);
        $em->flush();


        return $this->render('weather/index.html.twig', [
            'cities' => $cities,
            'weatherData' => $weatherHistory,
            'histories' => $histories,
            'currentCity' => $currentCity
        ]);
    }

    /**
     * @throws Exception
     */
    private function setHistory($weatherData, $city): History
    {
        // Get current date and time
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
}
