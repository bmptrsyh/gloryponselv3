<x-layout title="Login - Glory Ponsel" text1="Belum punya akun?" route="{{ route('register') }}" text2="Daftar">
   <h2 class="text-2xl font-medium text-[#ECECEB] mb-4">Log In</h2>
   <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
      @csrf
      <x-input type="text" name="login" placeholder="Nomor Telepon/Email" required="true" />
      <x-input type="password" name="password" placeholder="Password" required="true" />
      <button type="submit"
         class="w-full bg-[#EE3D3D] text-white py-3 rounded-lg text-lg font-medium hover:bg-red-600">LOG IN</button>
   </form>

   <!-- Lupa Kata Sandi Link -->
   <div class="mt-1 text-start">
      <a href="{{ route('password.request') }}" class="text-[#EE3D3D] hover:underline">Lupa Kata Sandi ?</a>
</x-layout>
