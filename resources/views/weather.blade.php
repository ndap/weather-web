<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherSkiel - Advanced Weather Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.js"></script>
    <style>
        .gradient-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gradient-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .weather-icon {
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.2));
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .glow {
            animation: glow 2s infinite alternate;
        }

        @keyframes glow {
            from {
                filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0.4));
            }

            to {
                filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8));
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 min-h-screen text-white">
    <!-- Sidebar -->
    <x-side-bar></x-side-bar>

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Weather Dashboard</h1>
                <p class="text-blue-200">{{ $currentTime }}</p>
            </div>

            <div class="flex items-center space-x-4">
                <form class="relative" action="/dashboard" method="GET">
                    <input type="text" name="city" placeholder="Search location..."
                        class="pl-10 pr-4 py-2 rounded-xl bg-white/10 border border-white/20 focus:outline-none focus:border-white/40 w-72 transition-all">
                    <button type="submit" class="absolute left-3 top-2.5">
                        <i data-lucide="search" class="w-5 h-5 text-white/40"></i>
                    </button>
                </form>

                <button class="p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
            </div>
        </div>

        <!-- Main Weather Card -->
        <div class="gradient-card rounded-2xl p-8 border border-white/10 mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center space-x-2 mb-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-blue-300"></i>
                        <h2 class="text-4xl font-bold">{{ $weather['name'] }}</h2>
                    </div>
                    <p class="text-blue-200 ml-7">{{ $coordinates['country'] }}</p>
                </div>
                <div class="text-right">
                    <div class="text-7xl font-bold mb-2 flex items-start">
                        {{ round($weather['main']['temp']) }}
                        <span class="text-4xl ml-1">°C</span>
                    </div>
                    <p class="text-blue-200 ml-7">{{ ucfirst($weather['weather'][0]['description']) }}</p>
                </div>
            </div>

            <!-- Weather Details -->
            <div class="grid grid-cols-4 gap-6 mt-12">
                <div class="gradient-card rounded-xl p-4 text-center">
                    <i data-lucide="droplets" class="w-8 h-8 mx-auto mb-3 text-blue-300"></i>
                    <div class="text-2xl font-bold mb-1">{{ $weather['main']['humidity'] }}%</div>
                    <p class="text-blue-200 text-sm">Humidity</p>
                </div>
                <div class="gradient-card rounded-xl p-4 text-center">
                    <i data-lucide="wind" class="w-8 h-8 mx-auto mb-3 text-blue-300"></i>
                    <div class="text-2xl font-bold mb-1">{{ $weather['wind']['speed'] }}m/s</div>
                    <p class="text-blue-200 text-sm">Wind Speed</p>
                </div>
                <div class="gradient-card rounded-xl p-4 text-center">
                    <i data-lucide="thermometer" class="w-8 h-8 mx-auto mb-3 text-orange-300"></i>
                    <div class="text-2xl font-bold mb-1">{{ $weather['main']['feels_like'] }}°</div>
                    <p class="text-blue-200 text-sm">Feels Like</p>
                </div>
                <div class="gradient-card rounded-xl p-4 text-center">
                    <i data-lucide="eye" class="w-8 h-8 mx-auto mb-3 text-yellow-300"></i>
                    <div class="text-2xl font-bold mb-1">{{ $weather['visibility'] / 1000 }}km</div>
                    <p class="text-blue-200 text-sm">Visibility</p>
                </div>
            </div>
        </div>

        <!-- Hourly Forecast -->
        <h3 class="text-xl font-bold mb-4">24-Hour Forecast</h3>
        <div class="gradient-card rounded-2xl p-6 border border-white/10 mb-8 overflow-x-auto custom-scrollbar">
            <div class="flex space-x-8 min-w-max">
                @foreach ($forecast['hourly'] as $index => $hour)
                    <div
                        class="flex-none text-center px-6 {{ $index < count($forecast['hourly']) - 1 ? 'border-r border-white/10' : '' }}">
                        <p class="text-blue-200 mb-2">{{ $hour['precipitation'] }}%</p>
                        <div class="flex items-center justify-center mb-2 weather-icon">
                            <i data-lucide="{{ $hour['icon'] }}"
                                class="w-8 h-8 {{ $hour['icon'] === 'sun' ? 'text-yellow-300' : '' }}"></i>
                        </div>
                        <p class="text-2xl font-bold mb-1">{{ $hour['temp'] }}°</p>
                        <p class="text-sm text-blue-200">{{ $hour['time'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Additional Info Grid -->
        <div class="grid grid-cols-2 gap-8">
            <!-- 5-Day Forecast -->
            <div class="gradient-card rounded-2xl p-6 border border-white/10">
                <h3 class="text-xl font-bold mb-6">5-Day Forecast</h3>
                <div class="space-y-6">
                    @foreach ($forecast['daily'] as $day)
                        <div class="flex items-center justify-between">
                            <div class="w-28">
                                <p class="text-blue-200">{{ $day['day'] }}</p>
                                <p class="text-sm text-white/60">{{ $day['date'] }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i data-lucide="{{ $day['icon'] }}"
                                    class="w-8 h-8 {{ $day['icon'] === 'sun' ? 'text-yellow-300' : '' }}"></i>
                                <span class="text-blue-200 w-24">{{ $day['description'] }}</span>
                            </div>
                            <div class="w-32 flex items-center justify-end space-x-2">
                                <span class="text-xl font-bold">{{ $day['temp_max'] }}°</span>
                                <span class="text-blue-200">{{ $day['temp_min'] }}°</span>
                            </div>
                            <div
                                class="w-24 bg-gradient-to-r from-blue-500 to-yellow-500 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-500 h-full transition-all duration-500"
                                    style="width: {{ $day['precipitation'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Air Quality -->
            <div class="gradient-card rounded-2xl p-6 border border-white/10">
                <h3 class="text-xl font-bold mb-6">Air Quality</h3>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <div class="text-4xl font-bold {{ $airQuality['color'] }} mb-2">{{ $airQuality['text'] }}
                        </div>
                        <p class="text-blue-200">Air quality index: {{ $airQuality['aqi'] }}</p>
                    </div>
                    <div
                        class="w-20 h-20 rounded-full border-4 {{ str_replace('text', 'border', $airQuality['color']) }} flex items-center justify-center">
                        <span class="text-3xl font-bold">{{ $airQuality['aqi'] }}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="gradient-card p-4 rounded-xl">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-200">PM2.5</span>
                            <span>{{ round($airQuality['components']['pm2_5']) }} µg/m³</span>
                        </div>
                    </div>
                    <div class="gradient-card p-4 rounded-xl">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-200">PM10</span>
                            <span>{{ round($airQuality['components']['pm10']) }} µg/m³</span>
                        </div>
                    </div>
                    <div class="gradient-card p-4 rounded-xl">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-200">O3</span>
                            <span>{{ round($airQuality['components']['o3']) }} µg/m³</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Welcome Back!',
                text: '{{ $message }}',
                confirmButtonText: 'Okay',
                background: '#1e1b4b',
                color: '#fff',
                confirmButtonColor: '#3b82f6'
            });
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $message }}',
                confirmButtonText: 'Okay',
                background: '#1e1b4b',
                color: '#fff'
            });
        </script>
    @endif
</body>

</html>
