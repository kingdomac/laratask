<div>
    @if ($showModal)
        <div x-data="{}" tabindex="0"
            class="bg-black bg-transparent bg-opacity-50 modal-overlay z-40 left-0 top-0 right-0 w-full h-full fixed">
            <div class="z-50 relative p-3 mx-auto  my-auto  max-w-full" style="width: 500px;">
                <div @click.away="$wire.hide"
                    class="modal-container bg-white rounded shadow-lg border flex flex-col overflow-hidden px-5 py-5">
                    <button wire:loading.class="opacity-10" wire:loading.attr="disabled" wire:click="hide"
                        type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="text-center py-6 text-xl uppercase text-gray-700">
                        <i
                            class="fa-solid fa-folder-tree hover:text-blue-500 mr-2"></i>{{ __('Grouping issue inside story') }}
                    </div>
                    <x-message type="danger" />
                    <div class="text-center font-light text-gray-700 mb-3">
                        <x-input-icon wire:model="keyword" placeholder="search for user"
                            class="border-[1px] border-gray-500" icon="<i class='fa-solid fa-magnifying-glass'></i>" />
                    </div>
                    <div wire:loading.flex wire:target='keyword' class="justify-center">
                        <div class="text-xs">{{ __('Loading...') }}</div>
                    </div>
                    <div class="pl-2 text-xs">
                        @if ($issue->parent_id)
                            <div wire:loading.class="opacity-60" wire:click="assignToStory()"
                                class="w-full uppercase bg-red-500 hover:bg-red-100 text-gray-200 py-2 px-3 rounded-lg cursor-pointer mb-2 text-sm">
                                {{ __('ungroup') }}
                            </div>
                        @endif

                        @foreach ($stories as $story)
                            <div wire:loading.class="opacity-60" wire:click="assignToStory({{ $story->id }})"
                                class="w-full hover:bg-blue-100 py-2 px-3 rounded-lg cursor-pointer mb-2">
                                <span class="font-semibold"
                                    style="color:{{ $story->label->color }}">{{ $story->id }}</span>.
                                <i class="fa-solid fa-folder-tree hover:text-blue-500 mr-2"></i>{{ $story->title }}

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50">
            </div>

        </div>
    @endif
</div>
