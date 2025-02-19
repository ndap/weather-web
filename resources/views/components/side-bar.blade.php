<!-- Mobile Menu Button -->
<button id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-black/30 backdrop-blur-xl border border-white/10">
    <i data-lucide="menu" class="w-6 h-6"></i>
</button>

<!-- Sidebar -->
<div id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-black/30 backdrop-blur-2xl p-6 border-r border-white/10 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <!-- Close Button for Mobile -->
    <button id="close-sidebar" class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-white/5">
        <i data-lucide="x" class="w-6 h-6"></i>
    </button>

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
            <div class="min-w-0">
                <p class="font-medium truncate">{{ Auth::user()->name }}</p>
                <p class="text-sm text-blue-200 truncate">Orang Ganteng</p>
            </div>
        </div>
    </div>
</div>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 lg:hidden hidden"></div>

<style>
    /* Additional styles for the sidebar */
    @media (max-width: 1023px) {
        body.sidebar-open {
            overflow: hidden;
        }
    }
</style>

<script>
    // Sidebar functionality
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const closeSidebarButton = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('sidebar-open');
    }

    mobileMenuButton.addEventListener('click', openSidebar);
    closeSidebarButton.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    // Close sidebar on screen resize if larger than mobile
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });

    // Handle escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeSidebar();
        }
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) &&
            !mobileMenuButton.contains(e.target) &&
            !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });
</script>
