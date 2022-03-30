@props(['online' => false])

@if ($online)
    <i class="fa-solid fa-circle text-green-400 "></i>
@else
    <i class="fa-solid fa-circle "></i>
@endif
