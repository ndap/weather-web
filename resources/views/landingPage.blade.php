<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherSKiel - Your Personal Weather Assistant</title>
    @vite("resources/css/app.css")
    <script src="https://unpkg.com/lucide"></script>
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-400 to-blue-600">
    <!-- Floating Clouds -->
    <div class="absolute w-full h-full overflow-hidden">
        <div class="animate-float">
            <i data-lucide="cloud" class="text-white/30 absolute top-20 left-20 w-16 h-16"></i>
            <i data-lucide="cloud" class="text-white/20 absolute top-40 right-40 w-12 h-12"></i>
            <i data-lucide="cloud" class="text-white/40 absolute bottom-40 left-1/3 w-14 h-14"></i>
        </div>
    </div>

    <!-- Main Content -->
    <div class="relative container mx-auto px-6 py-12">
        <!-- Navigation -->
        <nav class="flex justify-between items-center mb-16">
            <div class="flex items-center space-x-2">
                <i data-lucide="sun" class="text-yellow-300 w-8 h-8"></i>
                <span class="text-white text-2xl font-bold">WeatherSkiel</span>
            </div>
            <a href="{{ route('login') }}">
            <button class="flex items-center space-x-2 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition-all">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                <span>Sign In</span>
            </button>
            </a>
        </nav>

        <!-- Hero Section -->
        <div class="flex flex-col md:flex-row items-center justify-between mt-20">
            <div class="md:w-1/2 text-white">
                <h1 class="text-5xl font-bold mb-6">
                    Your Personal Weather Assistant
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    Dapatkan prakiraan cuaca yang akurat, pembaruan waktu nyata, dan peringatan cuaca yang dipersonalisasi untuk lokasi mana pun di seluruh dunia.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}">
                    <button class="flex items-center space-x-2 bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition-all font-semibold">
                        <span>Get Started</span>
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </button>
                    </a>
                    <button class="px-6 py-3 rounded-lg border border-white text-white hover:bg-white/10 transition-all">
                        Learn More
                    </button>
                </div>
            </div>

            <!-- Weather Card Preview -->
            <div class="md:w-1/2 mt-12 md:mt-0">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-white">Indonesia</h2>
                            <p class="text-blue-100">Bogor, Jawa Barat</p>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-bold text-white">35°C</p>
                            <p class="text-blue-100">Overcast clouds</p>
                        </div>
                    </div>
                    <div class="flex justify-between text-white">
                        <div class="text-center">
                            <p class="text-sm">Humidity</p>
                            <p class="font-bold">80%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm">Wind</p>
                            <p class="font-bold">12 mph</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm">Visibility</p>
                            <p class="font-bold">10 mi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="grid md:grid-cols-3 gap-8 mt-20">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-2">Pembaruan Waktu Nyata</h3>
                <p class="text-blue-100">Dapatkan pembaruan cuaca instan untuk lokasi Anda</p>
            </div>
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-2">Prakiraan Terperinci</h3>
                <p class="text-blue-100">Prakiraan 7 hari dengan prediksi setiap jam</p>
            </div>
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-2">Peringatan Cuaca</h3>
                <p class="text-blue-100">menerima peringatan dan pemberitahuan cuaca penting</p>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>