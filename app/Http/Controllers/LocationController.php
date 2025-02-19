<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    private $apiKey;
    private $baseUrl = 'http://api.openweathermap.org/data/2.5';

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }
    public function index()
    {
        $savedLocations = Location::where('user_id', Auth::id())
            ->get()
            ->map(function ($location) {
                $weatherData = $this->getWeatherData($location->latitude, $location->longitude);
                return $this->mergeLocationWithWeather($location, $weatherData);
            });

        return view('locations.index', compact('savedLocations'));
    }
    public function store(Request $request)
{
    $request->validate([
        'location' => 'required|string|max:255',
    ]);

    try {
        // Mendapatkan koordinat dari nama lokasi
        $geocodingUrl = "http://api.openweathermap.org/geo/1.0/direct";
        $response = Http::get($geocodingUrl, [
            'q' => $request->location,
            'limit' => 1,
            'appid' => $this->apiKey
        ]);

        // Log seluruh respons untuk debugging lebih lanjut
        \Log::debug('Respons API Geocoding', ['response' => $response->json(), 'status' => $response->status()]);

        if ($response->successful()) {
            // Memeriksa apakah respons memiliki lokasi yang valid
            $locationData = $response->json();

            if (count($locationData) > 0) {
                $locationData = $locationData[0];  // Menggunakan hasil pertama

                // Memeriksa apakah lokasi sudah ada untuk pengguna
                $existingLocation = Location::where('user_id', Auth::id())
                    ->where('latitude', $locationData['lat'])
                    ->where('longitude', $locationData['lon'])
                    ->first();

                if ($existingLocation) {
                    return redirect()->back()
                        ->with('error', 'Lokasi ini sudah ada dalam lokasi yang disimpan.');
                }

                // Membuat data lokasi baru
                Location::create([
                    'user_id' => Auth::id(),
                    'city' => $locationData['name'],
                    'country' => $locationData['country'] ?? '',
                    'latitude' => $locationData['lat'],
                    'longitude' => $locationData['lon'],
                ]);

                return redirect()->back()
                    ->with('success', 'Lokasi berhasil ditambahkan!');
            }

            // Jika tidak ditemukan lokasi
            return redirect()->back()
                ->with('error', 'Lokasi tidak ditemukan. Silakan periksa nama dan coba lagi.');

        } else {
            // Log status respons dan pesan error
            \Log::error('Geocoding API gagal', [
                'status' => $response->status(),
                'response_body' => $response->body()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghubungi layanan geocoding. Silakan coba lagi nanti.');
        }
    } catch (\Exception $e) {
        // Log error yang tidak terduga
        \Log::error('Terjadi kesalahan saat menambahkan lokasi', ['exception' => $e]);

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menambahkan lokasi. Silakan coba lagi.');
    }
}



    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, Location $location)
    {
        // Ensure the location belongs to the authenticated user
        if ($location->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'location' => 'required|string|max:255',
        ]);

        try {
            // Get coordinates from location name
            $geocodingUrl = "http://api.openweathermap.org/geo/1.0/direct";
            $response = Http::get($geocodingUrl, [
                'q' => $request->location,
                'limit' => 1,
                'appid' => $this->apiKey
            ]);

            if ($response->successful() && count($response->json()) > 0) {
                $locationData = $response->json()[0];

                $location->update([
                    'city' => $locationData['name'],
                    'country' => $locationData['country'] ?? '',
                    'latitude' => $locationData['lat'],
                    'longitude' => $locationData['lon'],
                ]);

                return redirect()->back()
                    ->with('success', 'Location updated successfully!');
            }

            return redirect()->back()
                ->with('error', 'Location not found. Please try again.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the location. Please try again.');
        }
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location)
    {
        // Ensure the location belongs to the authenticated user
        if ($location->user_id !== Auth::id()) {
            return redirect()->back()
                ->with('error', 'Unauthorized action.');
        }

        try {
            $location->delete();
            return redirect()->route('locations.index')
                ->with('success', 'Location removed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('locations.index')
                ->with('error', 'An error occurred while removing the location. Please try again.');
        }
    }

    /**
     * Get weather data from OpenWeather API
     */
    private function getWeatherData($lat, $lon)
    {
        try {
            $response = Http::get("{$this->baseUrl}/weather", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Merge location model with weather data
     */
    private function mergeLocationWithWeather($location, $weatherData)
    {
        if (!$weatherData) {
            return $location;
        }

        $location->temperature = round($weatherData['main']['temp']);
        $location->humidity = $weatherData['main']['humidity'];
        $location->wind_speed = round($weatherData['wind']['speed'], 1);
        $location->description = ucfirst($weatherData['weather'][0]['description']);

        // Map OpenWeather icons to Lucide icons
        $iconMapping = [
            '01d' => 'sun',
            '01n' => 'moon',
            '02d' => 'cloud-sun',
            '02n' => 'cloud-moon',
            '03d' => 'cloud',
            '03n' => 'cloud',
            '04d' => 'clouds',
            '04n' => 'clouds',
            '09d' => 'cloud-drizzle',
            '09n' => 'cloud-drizzle',
            '10d' => 'cloud-rain',
            '10n' => 'cloud-rain',
            '11d' => 'cloud-lightning',
            '11n' => 'cloud-lightning',
            '13d' => 'cloud-snow',
            '13n' => 'cloud-snow',
            '50d' => 'cloud-fog',
            '50n' => 'cloud-fog',
        ];

        $location->weather_icon = $iconMapping[$weatherData['weather'][0]['icon']] ?? 'cloud';

        return $location;
    }
}
