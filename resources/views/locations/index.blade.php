<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App - Locations</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide"></script>
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
        <!-- Add this near the top of your content section -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-400">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2">Saved Locations</h1>
                <p class="text-blue-200">Manage your favorite weather locations</p>
            </div>

            <div class="flex items-center space-x-4">
                <button onclick="openAddLocationModal()"
                    class="flex items-center space-x-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-xl transition-all">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Add Location</span>
                </button>
            </div>
        </div>

        <!-- Locations Grid -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Example of a location card -->
            @foreach ($savedLocations as $location)
                <div class="gradient-card rounded-2xl border border-white/10 overflow-hidden">
                    <div class="relative h-40 bg-gradient-to-b from-black/40 to-transparent p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold mb-1">{{ $location->city }}</h3>
                                <p class="text-blue-200">{{ $location->country }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="p-2 hover:bg-white/10 rounded-lg transition-all"
                                    onclick="editLocation({{ $location->id }})">
                                    <i data-lucide="edit" class="w-5 h-5"></i>
                                </button>
                                <!-- Fix the delete form -->
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                    class="inline">
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
                                <i data-lucide="{{ $location->weather_icon }}" class="w-8 h-8 weather-icon"></i>
                                <span class="text-3xl font-bold">{{ $location->temperature }}°</span>
                            </div>
                            <span class="text-blue-200">{{ $location->description }}</span>
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
    <!-- Add Location Modal -->
    <div id="addLocationModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center">
        <div class="gradient-card rounded-2xl p-6 w-full max-w-md">
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
                            <input type="text" id="location" name="location" placeholder="Enter city name..."
                                required
                                class="w-full pl-10 pr-4 py-2 rounded-xl bg-white/10 border border-white/20 focus:outline-none focus:border-white/40">
                            <i data-lucide="search" class="absolute left-3 top-2.5 w-5 h-5 text-white/40"></i>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 transition-all">
                        Add Location
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Modal functions
        function openAddLocationModal() {
            document.getElementById('addLocationModal').style.display = 'flex';
        }

        function closeAddLocationModal() {
            document.getElementById('addLocationModal').style.display = 'none';
        }

        // Location management functions
        function editLocation(id) {
            // Implement edit functionality
        }

        function deleteLocation(id) {
            if (confirm('Are you sure you want to remove this location?')) {
                // Implement delete functionality
            }
        }

        function viewDetails(id) {
            // Implement view details functionality
            window.location.href = `/dashboard?location_id=${id}`;
        }

        // Update the modal functions
        function openAddLocationModal() {
            const modal = document.getElementById('addLocationModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
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
    </script>


    <!-- SweetAlert2 -->
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
