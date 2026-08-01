@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'readonly' => false,
    'error' => null,
])

<div class="form-group">
    @if($label)
        <label for="{{ $name }}" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
            {{ $label }}
            @if($required) <span class="text-rose-500">*</span> @endif
        </label>
    @endif
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}" 
           value="{{ old($name, $value) }}"
           placeholder="{{ $placeholder }}"
           @if($required) required @endif
           @if($readonly) readonly @endif
           {{ $attributes->merge(['class' => 'form-control rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-[#151E3D] text-slate-900 dark:text-white p-3 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition']) }}>
    @if($error || $errors->has($name))
        <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $error ?? $errors->first($name) }}</span>
    @endif
</div>
