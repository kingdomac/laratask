<div>

    <form wire:submit.prevent='update'>
        <div class="mt-5 flex flex-wrap gap-2 items-center content-center">
            <div>
                <x-input wire:model.lazy="name" class="capitalize" type="text" style="color:{{ $color }}" />
            </div>
            <div>
                <input type="text" data-jscolor="" wire:model.defer="color" value=""
                    style="background-color: {{ $color }}"
                    class="px-2 py-1 capitalize placeholder-blueGray-300 text-white relative bg-white rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full">
            </div>

            <x-button>
                save
            </x-button>
            @if ($message)
                <x-success-icon color="{{ $color }}" />
            @endif
        </div>
    </form>

</div>
