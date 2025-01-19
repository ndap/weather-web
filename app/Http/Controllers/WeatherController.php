<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    private $apiKey;
    private $baseUrl = 'https://api.openweathermap.org/data/2.5/';

    public function __construct()
    {
        $this->apiKey = 'cf33ea81fd47ad5cf1206adb3fd211df';
    }

    public function index(Request $request)
    {
        $city = $request->input('city', 'Jakarta');

        try {
            $coordinates = $this->getCoordinates($city);

            if (!$coordinates) {
                return "City not found.";
            }

            $currentWeather = $this->getCurrentWeather($city);
            $forecast = $this->getForecastData($coordinates['lat'], $coordinates['lon']);
            $airQuality = $this->getAirQuality($coordinates['lat'], $coordinates['lon']);

            $currentTime = Carbon::now('Asia/Jakarta')->format('l, d F H:i');
            $formatDate = Carbon::now('Asia/Jakarta')->format('h:i A');

            return view('weather', [
                'weather' => $currentWeather,
                'coordinates' => $coordinates,
                'forecast' => $forecast,
                'airQuality' => $airQuality,
                'currentTime' => $currentTime,
                'formatDate' => $formatDate
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function getCoordinates($city)
    {
        $cacheKey = "weather_coordinates_{$city}";

        return Cache::remember($cacheKey, 3600, function () use ($city) {
            $response = Http::get("http://api.openweathermap.org/geo/1.0/direct", [
                'q' => $city,
                'limit' => 1,
                'appid' => $this->apiKey
            ]);

            if ($response->successful() && !empty($response->json())) {
                $data = $response->json()[0];
                return [
                    'lat' => $data['lat'],
                    'lon' => $data['lon'],
                    'name' => $data['name'],
                    'country' => $data['country'],
                    'country_name' => $this->getCountryName($data['country'])
                ];
            }

            throw new \Exception('City not found');
        });
    }

    private function getCurrentWeather($city)
    {
        $response = Http::get("{$this->baseUrl}weather", [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data;
        }

        throw new \Exception('Failed to fetch current weather data');
    }

    private function getForecastData($lat, $lon)
    {
    $response = Http::get("{$this->baseUrl}forecast", [
            'lat' => $lat,
            'lon'=> $lon,
            'appid' => $this->apiKey,
            'units' => 'metric'
        ]);

        if ($response->successful()) {
            $data = $response->json();

            $hourlyForecast = collect(array_slice($data['list'], 0, 8))
                ->map(function ($hour) {
                    $dateTime = Carbon::createFromTimestamp($hour['dt']);
                    $weather = $hour['weather'][0]['main'];

                    $icon = $this->mapWeatherToIcon($weather);

                    return [
                        'time' => $dateTime->format('g A'),
                        'temp' => round($hour['main']['temp']),
                        'icon' => $icon,
                        'description' => ucfirst($hour['weather'][0]['description']),
                        'precipitation' => isset($hour['pop']) ? round($hour['pop'] * 100) : 0
                    ];
                });

            $dailyForecast = collect($data['list'])
                ->groupBy(function ($item) {
                    return Carbon::createFromTimestamp($item['dt'])->format('Y-m-d');
                })
                ->take(5)
                ->map(function ($day) {
                    $dayData = $day->first();
                    $dateTime = Carbon::createFromTimestamp($dayData['dt']);
                    $weather = $dayData['weather'][0]['main'];
                    $icon = $this->mapWeatherToIcon($weather);

                    return [
                        'day' => $dateTime->format('l'),
                        'date' => $dateTime->format('M d'),
                        'temp_max' => round(collect($day->pluck('main.temp_max'))->max()),
                        'temp_min' => round(collect($day->pluck('main.temp_min'))->min()),
                        'icon' => $icon,
                        'description' => ucfirst($dayData['weather'][0]['description']),
                        'precipitation' => isset($dayData['pop']) ? round($dayData['pop'] * 100) : 0
                    ];
                });

            return [
                'hourly' => $hourlyForecast,
                'daily' => $dailyForecast
            ];
        }

        throw new \Exception('Failed to fetch forecast data');
    }

    private function getAirQuality($lat, $lon)
    {
        $response = Http::get("{$this->baseUrl}air_pollution", [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $aqi = $data['list'][0]['main']['aqi'];

            $aqiMap = [
                1 => ['text' => 'Good', 'color' => 'text-green-400'],
                2 => ['text' => 'Fair', 'color' => 'text-yellow-400'],
                3 => ['text' => 'Moderate', 'color' => 'text-orange-400'],
                4 => ['text' => 'Poor', 'color' => 'text-red-400'],
                5 => ['text' => 'Very Poor', 'color' => 'text-purple-400']
            ];

            return [
                'aqi' => $aqi,
                'text' => $aqiMap[$aqi]['text'],
                'color' => $aqiMap[$aqi]['color'],
                'components' => $data['list'][0]['components']
            ];
        }

        throw new \Exception('Failed to fetch air quality data');
    }

    private function mapWeatherToIcon($weather)
    {
        $iconMap = [
            'Clear' => 'sun',
            'Clouds' => 'cloud',
            'Rain' => 'cloud-rain',
            'Drizzle' => 'cloud-drizzle',
            'Thunderstorm' => 'cloud-lightning',
            'Snow' => 'cloud-snow',
            'Mist' => 'cloud',
            'Fog' => 'cloud',
            'Haze' => 'cloud'
        ];

        return $iconMap[$weather] ?? 'cloud';
    }

    private function getCountryName($countryCode)
    {
        $countries = [
            'ID' => 'Indonesia',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            // Add more as needed
        ];

        return $countries[$countryCode] ?? $countryCode;
    }
}
