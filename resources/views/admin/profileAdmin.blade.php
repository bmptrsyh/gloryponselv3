<x-dashboard>

    <!-- Main Content -->
    <div style="max-width: 1200px; margin: 30px auto; padding: 0 30px; display: flex; gap: 40px;">
    <!-- Left Sidebar -->
    <div style="width: 250px; background-color: #f8f9fa; padding: 20px; border-radius: 8px; height: fit-content;">
            
            <!-- Upload Photo Form -->
            <form action="{{ route('admin.profil.upload') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 20px;">
                @csrf
                @method('PUT')
                <div style="text-align: center; margin-bottom: 20px;">
                    <img id="gambar-preview" src="{{ asset($admin->foto_profil) }}" alt="Foto Profil" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin: 0 auto 10px;">
                    <h3 style="margin: 0; font-size: 18px;">{{ $admin->nama }}</h3>
                    <p style="margin: 5px 0 15px; color: #666; font-size: 14px;">{{ $admin->email }}</p>
                    <input type="file" name="foto_profil" id="foto_profil" accept="image/*" style="display: none;">
                    <label for="foto_profil" style="background-color: #4CAF50; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 14px; display: inline-block;">
                        Pilih Foto
                    </label>
                    <button type="submit" style="background-color: #4CAF50; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-size: 14px; margin-top: 10px;">Upload</button>
                    @error('foto_profil')
                        <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                    @enderror
                </div>
            </form>
    </div>

     <!-- Main Profile Content -->
    <div style="flex: 1; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0; font-size: 22px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Informasi Profil</h2>
        
        @if(session('success'))
            <div style="background-color: #dff0d8; color: #3c763d; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Name Update Form -->
        <form method="POST" action="{{ route('admin.profil.update')}}" style="margin-bottom: 25px;">
            @csrf
            @method('PUT')
            <input type="text" name="email" value = "{{ $admin->email }}" class="type hidden">
            <input type="text" name="alamat" value = "{{ $admin->alamat }}" class="type hidden">
            <div style="margin-bottom: 10px;">
                <label style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Nama:</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="nama" value="{{ $admin->nama }}" 
                           style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"
                           required>
                    <button type="submit" style="background-color: #2196F3; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 14px;">Update</button>
                </div>
                @error('nama')
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>
        </form>

        <!-- Email Update Form -->
        <form method="POST" action="{{ route('admin.profil.update')}}" style="margin-bottom: 25px;">
            @csrf
            @method('PUT')
            <input type="text" name="nama" value = "{{ $admin->nama }}" class="type hidden">
            <input type="text" name="alamat" value = "{{ $admin->alamat }}" class="type hidden">
            <div style="margin-bottom: 10px;">
                <label style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Email:</label>
                <div style="display: flex; gap: 10px;">
                    <input type="email" name="email" value="{{ $admin->email }}" 
                           style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <button type="submit" style="background-color: #2196F3; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 14px;">Update</button>
                </div>
                @error('email')
                    <p style="color: red; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>
</div>

<!-- Success Message Script -->
@if(session('success'))
    <script>
        setTimeout(function() {
            document.querySelector('[style*="background-color: #dff0d8"]').style.display = 'none';
        }, 3000);
    </script>
@endif

<script>
    document.getElementById('foto_profil').addEventListener('change', function (event) {
        const preview = document.getElementById('gambar-preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
</x-dashboard>