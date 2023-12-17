<?php

namespace App\Service;

class WeatherApiService
{
    public function fetchWeatherData(string $cityName, string $lang, string $apiKey): array
    {
        $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=$cityName&lang=$lang&appid=$apiKey&units=metric";
        $data = file_get_contents($apiUrl);
        return json_decode($data, true);
    }
}