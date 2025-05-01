@props(['type' => 'text', 'name', 'placeholder' => '', 'value' => '', 'required' => false])

<div class="relative w-full">
    <input 
        id="{{ $name }}"
        type="{{ $type }}" 
        name="{{ $name }}" 
        placeholder="{{ $placeholder }}" 
        value="{{ old($name, $value) }}" 
        class="w-full p-4 h-12 border border-gray-300 rounded-lg text-lg pr-12 @error($name) border-red-500 @enderror 
        @if($type === 'number') appearance-none [&::-webkit-inner-spin-button]:hidden [&::-webkit-outer-spin-button]:hidden @endif" 
        {{ $required ? 'required' : '' }}
    >

    {{-- Jika input password, tambahkan ikon mata --}}
    @if ($type === 'password')
    <button type="button" onclick="togglePassword('{{ $name }}')" 
        class="absolute right-4 top-3">
        <img id="eye-open-{{ $name }}" src="{{ asset('assets/images/eye-open.png') }}" 
            class="w-6 h-6 hidden">
        <img id="eye-closed-{{ $name }}" src="{{ asset('assets/images/eye-closed.png') }}" 
            class="w-6 h-6">
    </button>
    @endif
</div>

@error($name)
<p class="text-red-500 text-sm mt-1">{{ $message }}</p>
@enderror
