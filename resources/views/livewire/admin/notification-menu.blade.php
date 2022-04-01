<div>
    <x-dropdown align="right" width="80" maxHeight="96" class="mr-3">

        <x-slot name="trigger">
            {{-- wire:poll.keep-alive.10000ms='countUnreadNotifications' --}}
            <button
                class="relative flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                @if (count($notifications))
                    <i class="fas fa-bell"></i>
                @endif
                <span id="notification-counter">
                    @if ($countUnreadNotifications)
                        <a
                            class="absolute rounded-full text-left text-[10px] bg-red-600 text-white px-1.5 -top-3 -left-3">
                            {{ $countUnreadNotifications }}
                        </a>
                    @endif
                </span>


            </button>

        </x-slot>

        <x-slot name="content">
            @if (count($notifications))
                <div class="capitalize p-2 fixed">{{ __('notifications') }}</div>
                <div class="max-h-64 overflow-y-auto mt-10">
                    @foreach ($notifications as $notification)
                        <div
                            class="p-2 text-xs border border-b-white {{ $notification->read_at ? '' : 'bg-blue-200' }}">
                            <div>
                                <span class="first-letter:uppercase">{{ $notification->data['message'] ?? '' }}</span>
                                <span wire:click.prevent="markAsRead('{{ $notification->id }}')"
                                    class="cursor-pointer text-green-500">
                                    {{ $notification->data['title'] }}
                                </span>
                                <span class="text-yellow-400"> -
                                    {{ $notification->data['project_name'] ?? '' }}</span>
                                <div class="flex text-gray-400 text-xs">
                                    <div>{{ $notification->created_at->diffForHumans() }}</div>
                                    <a wire:click.prevent="delete('{{ $notification->id }}')" href="#"
                                        class="text-red-400 ml-2">{{ __('delete') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-right">
                    <a wire:click.prevent="clearAll" href="#"
                        class="text-xs first-letter:uppercase underline p-2 text-blue-300">clear all</a>
                </div>
            @endif
        </x-slot>
    </x-dropdown>
    @push('scripts')
        <script>
            var userId = {{ auth()->user()->id }}
            Echo.private('users.' + userId)
                .notification((notification) => {
                    if (notification.id) {
                        var counterElement = document.querySelector('#notification-counter');
                        @this.countUnreadNotifications++;
                        if (@this.countUnreadNotifications > 0) {
                            counterElement.innerHTML = `
                            <a class="absolute rounded-full text-left text-[10px] bg-red-600 text-white px-1.5 -top-3 -left-3">
                                ${@this.countUnreadNotifications}
                            </a>
                            `;
                        }

                    }
                });
        </script>
    @endpush
</div>
