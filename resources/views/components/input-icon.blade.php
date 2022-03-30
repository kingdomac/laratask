@props(['disabled' => false, 'icon' => '', 'color' => ''])
<div class="absolute ml-2 mt-0.5" style="color:{{ $color }}">{!! $icon !!}</div>
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'px-2 pl-7 py-1 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full']) !!}>
