<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('weather.api_key');
        $this->baseUrl = config('weather.base_url');
    }

    public function getCurrentWeather($city)
    {
        $cacheKey = 'weather_' . strtolower($city);

        // Check if we have cached data
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::get($this->baseUrl . 'weather', [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric' // Use metric units
            ]);

            if ($response->successful()) {
                $weatherData = $response->json();
                
                // Cache the data for 30 minutes
                Cache::put($cacheKey, $weatherData, now()->addMinutes(30));
                
                return $weatherData;
            }

            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }
}