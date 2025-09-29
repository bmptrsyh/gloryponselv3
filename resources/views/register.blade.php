<!-- resources/views/auth/login.blade.php -->
<x-layout title="Register - Glory Ponsel" text1="Sudah punya akun?" route="{{ route('login') }}" text2="Log In">
    <h2 class="text-2xl font-medium text-[#ECECEB] mb-4">Register</h2>
    <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="nama" class="block text-gray-200 mb-1">Nama Lengkap</label>
            <x-input type="text" name="nama" placeholder="Nama Lengkap"/>
        </div>

        <div>
            <label for="email" class="block text-gray-200 mb-1">Email</label>
            <x-input type="email" name="email" placeholder="Email" required="true"/>
        </div>

        <div>
            <label for="password" class="block text-gray-200 mb-1">Password</label>
            <x-input type="password" name="password" placeholder="Password" required="true"/>
        </div>

        <div>
            <label for="password_confirmation" class="block text-gray-200 mb-1">Konfirmasi Password</label>
            <x-input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required="true"/>
        </div>

        <div>
            <label for="alamat" class="block text-gray-200 mb-1">Alamat Lengkap</label>
            <x-input type="text" name="alamat" placeholder="Alamat Lengkap" required="true"/>
        </div>

        <div>
            <label for="nomor_telepon" class="block text-gray-200 mb-1">Nomor Telepon</label>
            <x-input type="number" name="nomor_telepon" placeholder="Nomor Telepon" required="true"/>
        </div>

        {{-- Custom File Upload --}}
        <div>
            <label for="foto_profil" class="block text-gray-200 mb-1">Foto Profil (Opsional)</label>
            <div class="flex flex-col space-y-3">
                <!-- Input File -->
                <div class="flex items-center space-x-3">
                    <input 
                        id="foto_profil" 
                        type="file" 
                        name="foto_profil" 
                        accept="image/*"
                        class="hidden" 
                        onchange="previewFile(this)"
                    >
                    <label for="foto_profil" 
                        class="cursor-pointer bg-[#EE3D3D] hover:bg-red-600 text-white py-2 px-4 rounded-lg shadow-md transition duration-200">
                        Pilih Foto
                    </label>
                    <span id="file-name" class="text-gray-200">Belum ada file</span>
                </div>

                <!-- Preview Gambar -->
                <div id="preview-container" class="hidden">
                    <p class="text-gray-200 mb-2">Preview Foto:</p>
                    <img id="preview-image" class="w-32 h-32 object-cover rounded-lg border border-gray-300 shadow-md transition duration-300" alt="Preview Foto">
                </div>
            </div>
        </div>

        <button type="submit" 
            class="w-full bg-[#EE3D3D] text-white py-3 rounded-lg text-lg font-medium hover:bg-red-600 transition duration-200">
            Daftar
        </button>
    </form>  
</x-layout>

<script>
    function previewFile(input) {
        const file = input.files[0];
        const fileName = file ? file.name : "Belum ada file";
        document.getElementById('file-name').textContent = fileName;

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewContainer = document.getElementById('preview-container');
                const previewImage = document.getElementById('preview-image');

                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('preview-container').classList.add('hidden');
        }
    }
</script>
