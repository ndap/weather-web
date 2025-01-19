<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherSkiel - Create Your Account</title>
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
        .password-strength-1 { background: #EF4444; }
        .password-strength-2 { background: #F59E0B; }
        .password-strength-3 { background: #10B981; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="animate-float">
            <i data-lucide="cloud" class="text-white/5 absolute top-20 left-20 w-32 h-32"></i>
            <i data-lucide="cloud-snow" class="text-white/5 absolute top-40 right-40 w-24 h-24"></i>
            <i data-lucide="cloud-rain" class="text-white/5 absolute bottom-40 left-1/3 w-28 h-28"></i>
            <i data-lucide="sun" class="text-white/5 absolute top-1/4 right-1/4 w-20 h-20"></i>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="container max-w-6xl mx-auto flex flex-col lg:flex-row items-center gap-12 relative">
            <!-- Left Column - Welcome Content -->
            <div class="lg:w-1/2 text-center lg:text-left">
                <a href="/" class="inline-flex items-center mb-8">
                    <i data-lucide="cloud-lightning" class="text-yellow-300 w-10 h-10 mr-2"></i>
                    <span class="text-3xl font-bold text-white">WeatherSkiel</span>
                </a>
                
                <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                    Start Your Weather Journey
                </h1>
                
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Join, ini website dari tugas pa hehe, kalo mau liat cuaca secara real time disini aja
                </p>

                <!-- Feature List -->
                <div class="space-y-6 mb-8">
                    <div class="flex items-center space-x-4 text-blue-100">
                    </div>
                    
                    <div class="flex items-center space-x-4 text-blue-100">
                        <div class="flex-shrink-0 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                            <i data-lucide="map" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Multiple Locations</h3>
                            <p>Track weather anywhere in the world</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 text-blue-100">
                        <div class="flex-shrink-0 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                            <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Detailed Analytics</h3>
                            <p>Advanced weather data and trends</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Registration Form -->
            <div class="lg:w-1/2 w-full max-w-md">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-white/20">
                    <h2 class="text-2xl font-bold text-white mb-2">Create Account</h2>
                    <p class="text-blue-100 mb-6">Enter your details to get started</p>

                    <form class="space-y-6" action="" method="POST">
                        @csrf
                        <!-- Name Fields -->
                        <div>
    <label class="block text-sm font-medium text-blue-100 mb-2">Username</label>
    <input type="text" name="name" id="name"
        class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all"
        placeholder="Masukkan username">
</div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">Email Address</label>
                            <div class="relative">
                                <input type="email" name="email" id="email"
                                    class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all"
                                    placeholder="Input your email">
                                <i data-lucide="mail" class="absolute left-4 top-3.5 w-5 h-5 text-white/40"></i>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-blue-100 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all"
                                    placeholder="••••••••">
                                <i data-lucide="lock" class="absolute left-4 top-3.5 w-5 h-5 text-white/40"></i>
                            </div>
                            <div class="flex gap-2 mt-2">
                                <div class="h-1 flex-1 rounded-full bg-white/10 overflow-hidden">
                                    <div class="h-full w-0 password-strength-1"></div>
                                </div>
                            </div>
                            <p class="text-xs text-blue-100 mt-2">Password must be at least 8 characters</p>
                        </div>


                        <!-- Submit Button -->
                        <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-400 to-indigo-500 text-white py-3 px-4 rounded-xl font-semibold hover:opacity-90 transition-all focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 flex items-center justify-center space-x-2">
                            <span>Create Account</span>
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </button>

                        <!-- Login Link -->
                        <p class="text-center text-blue-100">
                            Already have an account? 
                            <a href="/login" class="text-white font-semibold hover:underline">Sign in</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>