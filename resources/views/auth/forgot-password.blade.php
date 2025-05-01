
<x-layout title="Login - Glory Ponsel" text1="Belum punya akun?" route="{{ route('register') }}" text2="Daftar">
    <h2 class="text-2xl font-medium text-[#ECECEB] mb-4">Lupa Password</h2>

    @if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
    <form action="{{ route('password.otp.send') }}" method="POST" class="space-y-4">
        @csrf
        <x-input type="email" name="email" placeholder="Nomor Telepon/Email" required="true"/>
        <button type="submit" class="w-full bg-[#EE3D3D] text-white py-3 rounded-lg text-lg font-medium hover:bg-red-600">Kirim OTP</button>
    </form>
</x-layout>

