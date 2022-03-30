@props(['avg' => 0])
@php
if ($avg <= 50) {
    $color = 'red';
} elseif ($avg > 50 && $avg < 100) {
    $color = 'lightBlue';
} else {
    $color = 'emerald';
}
@endphp
<span class="mr-2">
    {{ (int) $avg }}%
</span>
<div {!! $attributes->class(['relative w-full'])->merge(['class']) !!}>
    <div class="overflow-hidden h-2 w-3/2 text-xs flex rounded bg-{{ $color }}-200">
        <div style="width: {{ $avg }}%"
            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-{{ $color }}-500">
        </div>
    </div>
</div>
