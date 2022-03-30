@props(['name', 'type' => 'error'])
@error($name)
    <span {!! $attributes->class(['text-xs', 'text-red-500' => $type == 'error', 'text-green-500' => $type == 'success'])->merge(['class']) !!}>{{ $message }}</span>
@enderror
