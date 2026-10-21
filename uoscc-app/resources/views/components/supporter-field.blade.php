@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => true])
<div>
    <label for="{{ $name }}" class="label">{{ $label }} @if(!$required)<span class="font-normal">(opcional)</span>@endif</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" @if($type !== 'password') value="{{ old($name, $value) }}" @endif {{ $attributes->class(['input']) }} @required($required) aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}" @if($errors->has($name)) aria-describedby="{{ $name }}-error" @endif>
    @error($name)<p id="{{ $name }}-error" class="mt-2 text-sm text-red-700 dark:text-red-400">{{ $message }}</p>@enderror
</div>
