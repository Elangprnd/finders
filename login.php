<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FindeRS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="assets/styles/style_logreg.css">
</head>
<body class="h-screen w-full flex overflow-hidden bg-white">

    <div class="w-full lg:w-5/12 flex flex-col justify-center items-center px-8 lg:px-16 bg-white z-10 shadow-xl lg:shadow-none relative overflow-hidden">
        
        <div class="absolute top-0 left-0 -ml-10 -mt-10 w-40 h-40 bg-green-100 rounded-full opacity-50 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 -mr-10 -mb-10 w-40 h-40 bg-blue-100 rounded-full opacity-50 blur-3xl"></div>

        <a href="index.php" class="absolute top-8 left-8 z-20 hover:opacity-80 transition cursor-pointer" title="Kembali ke Beranda">
            <img src="assets/img/FindeRS_Logo.png" alt="FindeRS Logo" class="h-12 w-auto">
        </a>

        <div class="w-full max-w-md relative z-10 mt-10">
            
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-finders-blue mb-2">Selamat Datang!</h1>
                <p class="text-gray-500 text-sm">Silakan login untuk mengakses akun Anda.</p>
            </div>

            <form action="api/auth/login.php" method="POST" class="space-y-5">
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 ml-4 mb-1">Email</label>
                    <div class="relative">
                        <input type="text" name="identifier" required 
                            class="block w-full px-6 py-3 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition text-sm" 
                            placeholder="user@example.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 ml-4 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" required 
                            class="block w-full px-6 py-3 bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent transition text-sm" 
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between px-2">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-xs text-gray-600">
                            Ingat saya
                        </label>
                    </div>
                    <div class="text-xs">
                        <a href="#" class="font-medium text-finders-blue hover:text-green-500 transition">Lupa password?</a>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full py-3 px-4 border border-transparent rounded-full shadow-md text-sm font-bold text-white bg-finders-green hover-bg-finders-green focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 transform hover:-translate-y-0.5 mt-4">
                    LOGIN SEKARANG
                </button>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-2 bg-white text-gray-400"></span>
                    </div>
                </div>

                <div class="text-center mt-6">
                    <p class="text-xs text-gray-500">
                        Belum punya akun? <a href="register.php" class="font-bold text-finders-blue hover:text-green-600 transition">Buat Akun Baru</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="absolute bottom-4 w-full text-center">
            <p class="text-[10px] text-gray-300">
                &copy; 2025 FindeRS Healthcare System.
            </p>
        </div>
    </div>

    <div class="hidden lg:flex lg:w-7/12 relative bg-finders-blue items-center justify-center overflow-hidden">
        
        <img src="assets/img/rumahsakit_bg.png" alt="Hospital Hallway" class="absolute inset-0 w-full h-full object-cover">
        
        <div class="absolute inset-0 bg-blue-900/85"></div>
        
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-green-400 rounded-full opacity-10 blob-anim blur-2xl"></div>
        <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-bl-full opacity-10"></div>
        
        <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-48 h-48 bg-finders-green rounded-full opacity-10 blob-anim blur-xl" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-tr-full opacity-10"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full px-16 text-white text-center">
             <div class="bg-white/10 p-4 rounded-full mb-6 backdrop-blur-sm border border-white/20 shadow-lg">
                 <svg class="w-12 h-12 text-finders-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
             </div>

            <h2 class="text-4xl lg:text-5xl font-bold leading-tight mb-4">
                Solusi Cepat <br>
                <span class="text-finders-green">Kesehatan Anda</span>
            </h2>
            
            <p class="text-blue-100 text-lg max-w-lg leading-relaxed font-light">
                Temukan rumah sakit terdekat, cek ketersediaan layanan, dan kelola janji temu medis Anda dengan mudah.
            </p>
        </div>
    </div>

</body>
</html>