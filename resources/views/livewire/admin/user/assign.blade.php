<div tabindex="0"
    class="@if (!$showAssignModal) hidden @endif modal-overlay z-40 left-0 top-0 right-0 w-full h-full fixed">
    @if ($showAssignModal)
        <div class="z-50 relative p-3 mx-auto  my-auto  max-w-full" style="width: 500px;">
            <div @click.away="$wire.hide"
                class="modal-container bg-white rounded shadow-lg border flex flex-col overflow-hidden px-5 py-5">
                <button wire:loading.class="opacity-10" wire:loading.attr="disabled" wire:click="hide" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="text-lg">
                    <span style="color:{{ $issue->label->color }}">{!! $issue->label->icon !!}</span>
                    {{ $issue->title }}
                </div>
                <div class="text-center py-6 text-sm text-gray-700">
                    <div class="uppercase mb-3">
                        <i class="fa-solid fa-people-arrows-left-right mr-2 cursor-pointer"></i>{{ __('Assign to') }}
                    </div>
                    @if ($issue->isStory)
                        <p class="text-sm normal-case text-left text-red-300">
                            <span class="text-blue-500">{{ __('Note:') }}</span>
                            {!! __('Assigning a <strong>story</strong> will reassign all its subtasks to the user as new assignements. It is better to assign the <strong>subtasks</strong> individually if you want to keep its entries.') !!}
                        </p>
                    @endif
                </div>
                @if ($issue?->user_id)
                    <div wire:loading.class="opacity-60" wire:click="assign()"
                        class="w-full uppercase bg-red-500 hover:bg-red-100 text-gray-200 py-2 px-3 rounded-lg cursor-pointer mb-2 text-sm">
                        {{ __('unassign') }}
                    </div>
                @endif
                <div class="text-center font-light text-gray-700 mb-3">
                    <x-input-icon wire:model="keyword" placeholder="search for user" autofocus="autofocus"
                        class="border-[1px] border-gray-500" icon="<i class='fa-solid fa-magnifying-glass'></i>" />
                </div>
                <x-message type="danger" />
                <div wire:loading.flex wire:target='keyword' class="justify-center">
                    <div class="text-xs">{{ __('Loading...') }}</div>
                </div>
                <div class="pl-2">
                    @foreach ($users as $user)
                        <div wire:loading.class="opacity-60" wire:target='assign({{ $user->id }})'
                            wire:click="assign({{ $user->id }})"
                            class="w-full hover:bg-blue-100 py-2 px-3 rounded-lg cursor-pointer mb-2 text-sm">
                            <i class="fa-solid fa-person mr-2"></i>{{ $user->name }}
                            <span style="color:{{ $user->role->color }}">{!! $user->role->icon !!}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50">
        </div>
    @endif
</div>
