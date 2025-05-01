<x-layout title="Login - Glory Ponsel" text1="Belum punya akun?" route="{{ route('register') }}" text2="Daftar">
    <h2 class="text-2xl font-medium text-[#ECECEB] mb-4">Verifikasi OTP</h2>

    <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-4">
        @csrf
        <x-input type="text" name="otp" placeholder="Masukkan OTP" required="true"/>
        <button type="submit" class="w-full bg-[#EE3D3D] text-white py-3 rounded-lg text-lg font-medium hover:bg-red-600">Verifikasi</button>
    </form>
</x-layout>
