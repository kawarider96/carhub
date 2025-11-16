@props([
    'name',
    'label' => '',
    'type' => 'text'
])

<div class="space-y-2">
    
    @if ($label)
        <label for="{{ $name }}" class="block text-xs uppercase tracking-widest text-gray-500">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name) }}"
        class="w-full bg-panel border border-border rounded-lg px-4 py-3 text-gray-200
               focus:border-accent focus:outline-none tracking-wide"
    >

    @error($name)
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror

</div>
