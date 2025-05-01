<!-- resources/views/components/layout.blade.php -->
@props(['title' => 'Glory Ponsel', 'text1' => '', 'route' => '', 'text2' => '', 'judul' => 'Welcome to Glory Ponsel'])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#10316B] flex items-center justify-center min-h-screen">
    <div class="w-full max-w-lg p-8 rounded-lg">
        <h1 class="text-4xl font-bold text-[#ECECEB] mb-4 text-center">{{ $judul }}</h1>
        
        <!-- Slot untuk konten dinamis -->
        {{ $slot }}

        <!-- Divider atau garis pemisah -->
        <div class="relative my-6 text-center">
            <div class="absolute left-0 top-1/2 w-1/3 border-t border-[#ECECEB]"></div>
            <span class="text-light text-[#ECECEB] px-3">ATAU</span>
            <div class="absolute right-0 top-1/2 w-1/3 border-t border-[#ECECEB]"></div>
        </div>

        <!-- Tombol Login dengan Google/Facebook -->
        <div class="space-y-4">
            <a href="{{ route('google.login') }}" class="w-full flex items-center justify-center gap-2 bg-white text-black py-3 rounded-lg text-lg text-center shadow-md hover:bg-red-600">
                <img src="{{ asset('assets/images/google.svg') }}" alt="Google" class="w-6 h-6"> Continue with Google
            </a>
            <a href="{{ route('facebook.login') }}" class="w-full flex items-center justify-center gap-2 bg-white text-black py-3 rounded-lg text-lg text-center shadow-md hover:bg-red-600">
                <img src="{{ asset('assets/images/facebook.svg') }}" alt="Facebook" class="w-6 h-6"> Continue with Facebook
            </a>
        </div>

        <!-- Tautan ke halaman lain -->
        <div class="mt-6 text-lg text-center">
            <span class="text-[#ECECEB]">{{ $text1 }}</span> 
            <a href="{{ $route }}" class="text-red-600 font-semibold hover:text-blue-600">{{ $text2 }}</a>
        </div>

    </div>
</body>

<script>
    function togglePassword(fieldName) {
        let passwordInput = document.getElementById(fieldName);
        let eyeOpen = document.getElementById("eye-open-" + fieldName);
        let eyeClosed = document.getElementById("eye-closed-" + fieldName);
    
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeOpen.classList.remove("hidden");
            eyeClosed.classList.add("hidden");
        } else {
            passwordInput.type = "password";
            eyeOpen.classList.add("hidden");
            eyeClosed.classList.remove("hidden");
        }
    }
    </script>
    
    
</html>
