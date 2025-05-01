<x-layout title="Login - Glory Ponsel" text1="Belum punya akun?" route="{{ route('register') }}" text2="Daftar">
    <h2 class="text-2xl font-medium text-[#ECECEB] mb-4">Reset Password</h2>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <x-input type="password" name="password" placeholder="Masukkan Password Baru" required="true"/>
        <x-input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" required="true"/>
        <button type="submit" class="w-full bg-[#EE3D3D] text-white py-3 rounded-lg text-lg font-medium hover:bg-red-600">Reset Password</button>
    </form>
</x-layout>
