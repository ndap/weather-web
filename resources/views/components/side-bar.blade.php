<div class="fixed left-0 top-0 h-full w-64 bg-black/30 backdrop-blur-2xl p-6 border-r border-white/10">
        <div class="flex items-center space-x-3 mb-12">
            <i data-lucide="cloud-lightning" class="text-yellow-300 w-8 h-8 glow"></i>
            <span class="text-2xl font-bold tracking-tight">WeatherSkiel</span>
        </div>

        <nav class="space-y-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('locations.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-white/10 transition-all hover:bg-white/20">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                <span>Locations</span>
            </a>
            <a href="{{ route('logout') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span>Log Out</span>
            </a>
        </nav>

        <!-- User Profile Section -->
        <div class="absolute bottom-6 left-6 right-6">
            <div class="gradient-card rounded-xl p-4 flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="font-medium">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-blue-200">Orang Ganteng</p>
                </div>
            </div>
        </div>
    </div>