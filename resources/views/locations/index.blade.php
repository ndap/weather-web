<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherSkiel - Enhanced</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide"></script>
    <style>
        .gradient-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .gradient-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .weather-icon {
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.2));
        }

        .glow {
            animation: glow 2s infinite alternate;
        }

        @keyframes glow {
            from { filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0.4)); }
            to { filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.8)); }
        }

        /* Mobile menu animation */
        .mobile-menu {
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.hidden {
            transform: translateX(-100%);
        }

        /* Loading skeleton animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        .skeleton {
            background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-950 min-h-screen text-white">
    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button" class="fixed top-4 left-4 z-50 p-2 rounded-lg bg-white/10 lg:hidden">
        <i data-lucide="menu" class="w-6 h-6"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white/5 backdrop-blur-xl p-6 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">WeatherSkiel</h1>
            <button class="lg:hidden" id="close-sidebar">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <nav class="space-y-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('locations.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl bg-white/10 transition-all">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                <span>Locations</span>
            </a>
            <div class="pt-4 mt-4 border-t border-white/10">
                <a href="{{ route('logout') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all text-red-400">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span>Log Out</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64 p-4 md:p-8 pt-16 lg:pt-8">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6 animate-fade-in">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-400">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Top Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Saved Locations</h1>
                <p class="text-blue-200">Manage your favorite weather locations</p>
            </div>

            <button onclick="openAddLocationModal()"
                class="w-full md:w-auto flex items-center justify-center space-x-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Add Location</span>
            </button>
        </div>

        <!-- Locations Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($savedLocations as $location)
                <div class="gradient-card rounded-2xl border border-white/10 overflow-hidden group">
                    <div class="relative h-40 bg-gradient-to-b from-black/40 to-transparent p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold mb-1">{{ $location->city }}</h3>
                                <p class="text-blue-200">{{ $location->country }}</p>
                            </div>
                            <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-white/10 rounded-lg transition-all"
                                        onclick="return confirm('Are you sure you want to remove this location?')">
                                        <i data-lucide="trash-2" class="w-5 h-5 text-red-400"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                            <div class="flex items-center space-x-2">
                                <i data-lucide="{{ $location->weather_icon }}" class="w-8 h-8 weather-icon glow"></i>
                                <span class="text-3xl font-bold">{{ $location->temperature }}°</span>
                            </div>
                            <span class="text-blue-200 text-sm md:text-base">{{ $location->description }}</span>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="gradient-card rounded-xl p-3">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i data-lucide="droplets" class="w-4 h-4 text-blue-300"></i>
                                    <span class="text-blue-200 text-sm">Humidity</span>
                                </div>
                                <p class="text-xl font-bold">{{ $location->humidity }}%</p>
                            </div>
                            <div class="gradient-card rounded-xl p-3">
                                <div class="flex items-center space-x-2 mb-2">
                                    <i data-lucide="wind" class="w-4 h-4 text-blue-300"></i>
                                    <span class="text-blue-200 text-sm">Wind</span>
                                </div>
                                <p class="text-xl font-bold">{{ $location->wind_speed }}m/s</p>
                            </div>
                        </div>
                        <button onclick="viewDetails({{ $location->id }})"
                            class="w-full py-3 rounded-xl bg-white/10 hover:bg-white/20 transition-all">
                            View Details
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Add Location Modal -->
    <div id="addLocationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="gradient-card rounded-2xl p-6 w-full max-w-md mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Add New Location</h3>
                <button type="button" onclick="closeAddLocationModal()"
                    class="p-2 hover:bg-white/10 rounded-lg transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('locations.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="location" class="text-sm text-blue-200 mb-1 block">Search Location</label>
                        <div class="relative">
                            <input type="text" id="location" name="location"
                                placeholder="Enter city name..."
                                required
                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-white/10 border border-white/20 focus:outline-none focus:border-white/40 focus:ring-2 focus:ring-white/10">
                            <i data-lucide="search" class="absolute left-3 top-3.5 w-5 h-5 text-white/40"></i>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 transition-all flex items-center justify-center space-x-2">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                        <span>Add Location</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Mobile menu functionality
        const sidebar = document.getElementById('sidebar');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebarButton = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
        }

        mobileMenuButton.addEventListener('click', toggleSidebar);
        closeSidebarButton.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });

        // Modal functions with improved animations
        function openAddLocationModal() {
            const modal = document.getElementById('addLocationModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Focus the input after modal opens
            setTimeout(() => {
                document.getElementById('location').focus();
            }, 100);
        }

        function closeAddLocationModal() {
            const modal = document.getElementById('addLocationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('addLocationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddLocationModal();
            }
        });

        // Escape key closes modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddLocationModal();
            }
        });

        // Location management functions
        function editLocation(id) {
            // Implement edit functionality
            console.log('Editing location:', id);
        }

        function viewDetails(id) {
            window.location.href = `/dashboard?location_id=${id}`;
        }

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>

    <!-- SweetAlert2 with enhanced styling -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ $message }}',
                confirmButtonText: 'Okay',
                background: '#1e1b4b',
                color: '#fff',
                confirmButtonColor: '#3b82f6',
                showClass: {
                    popup: 'animate__animated animate__fadeIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                },
                customClass: {
                    popup: 'gradient-card'
                }
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
                color: '#fff',
                confirmButtonColor: '#ef4444',
                showClass: {
                    popup: 'animate__animated animate__fadeIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                },
                customClass: {
                    popup: 'gradient-card'
                }
            });
        </script>
    @endif

    <!-- Loading State -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
        <div class="text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-500 border-t-transparent"></div>
            <p class="mt-4 text-blue-200">Loading...</p>
        </div>
    </div>

    <!-- Add this before closing body tag -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <script>
        // Show loading overlay
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('loading-overlay').classList.add('flex');
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
            document.getElementById('loading-overlay').classList.remove('flex');
        }

        // Add loading state to form submission
        document.querySelector('form').addEventListener('submit', function() {
            showLoading();
        });

        // Lazy load images and optimize performance
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize intersection observer for lazy loading
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const lazyElement = entry.target;
                        if (lazyElement.dataset.src) {
                            lazyElement.src = lazyElement.dataset.src;
                            lazyElement.removeAttribute('data-src');
                        }
                        observer.unobserve(lazyElement);
                    }
                });
            });

            // Observe all elements with data-src attribute
            document.querySelectorAll('[data-src]').forEach(elem => observer.observe(elem));
        });

        // Add smooth hover effect for cards
        document.querySelectorAll('.gradient-card').forEach(card => {
            card.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === '/') {
                e.preventDefault();
                document.getElementById('location').focus();
            }
        });
    </script>
</body>
</html>
