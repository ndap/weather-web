<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeatherSkiel - Authentication</title>
    @vite("resources/css/app.css")
    <script src="https://unpkg.com/lucide"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-blue-400 to-blue-600">
    <!-- Login Form -->
    <div id="loginForm" class="container mx-auto px-4 h-screen flex items-center justify-center">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 w-full max-w-md">

            
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Welcome Back to WeatherSkiel</h2>
            @if ($errors->any())
                <div class="bg-red-200 px-4 py-3 rounded-lg mb-4">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-xs">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form class="space-y-6" method="POST" action="">
                @csrf
                <div>
                    <label class="block text-blue-100 mb-2" for="email">Email Address</label>
                    <input value="{{ old('email') }}" type="email" name="email" id="email" class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:border-white" placeholder="Input your email">
                </div>
                
                <div>
                    <label class="block text-blue-100 mb-2" for="password">Password</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:border-white" placeholder="••••••••">
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-blue-100">
                        <input type="checkbox" class="mr-2">
                        Remember me
                    </label>
                    <a href="#" class="text-sm text-blue-100 hover:text-white">Forgot Password?</a>
                </div>
                
                <button type="submit" class="w-full bg-white text-blue-600 py-3 rounded-lg hover:bg-blue-50 transition-all font-semibold">
                    Sign In
                </button>
            </form>
            
            <p class="text-center text-blue-100 mt-6">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-white hover:underline font-semibold">Sign Up</a>
            </p>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($message = Session::get('failed'))
    <script>
        Swal.fire("Password atau Email yang anda masukkan salah");
    </script>
    @endif
    @if ($message = Session::get('logout'))
    <script>
        Swal.fire("Berhasil Log out");
    </script>
    @endif
    @if ($message = Session::get('regist'))
    <script>
        Swal.fire({
  title: "Akun berhasil dibuat",
  icon: "success",
  draggable: true
});
    </script>
    @endif
</body>
</html>