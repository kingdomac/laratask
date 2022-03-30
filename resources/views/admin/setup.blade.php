<x-app-layout>
    <div class="flex flex-wrap mt-4">
        <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3 class="font-semibold text-lg text-green-500 uppercase">
                                <i class="fas fa-tools mr-2 text-green-500"></i>{{ __('setup') }}
                            </h3>
                        </div>
                    </div>
                </div>
                <!-- Divider -->
                <hr class="my-4 md:min-w-full" />
                <!-- Heading -->
                <div class="block w-full">
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <!-- Roles -->
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                    <i class="fa-solid fa-hat-cowboy mr-2"></i>{{ __('roles') }}
                                </h3>
                                <!-- Repeat Roles -->
                                @foreach ($roles as $role)
                                    @livewire('admin.role-setup', ['role' => $role], key($role->id) )
                                @endforeach

                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4 md:min-w-full" />
                        <!-- Heading -->

                        <!-- Labels -->
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                    <i class="fa-solid fa-tags mr-2"></i>{{ __('labels') }}
                                </h3>
                                <!-- Repeat Lables -->
                                @foreach ($labels as $label)
                                    @livewire('admin.label-setup', ['label' => $label], key($label->id))
                                @endforeach


                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4 md:min-w-full" />
                        <!-- Heading -->

                        <!-- Priorities -->
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                    <i class="fa-solid fa-arrow-up-short-wide mr-2"></i>{{ __('priorities') }}
                                </h3>
                                <!-- Repeat priority -->
                                @foreach ($priorities as $priority)
                                    @livewire('admin.priority-setup', ['priority' => $priority], key($priority->id))
                                @endforeach
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4 md:min-w-full" />
                        <!-- Heading -->

                        <!-- Statuses -->
                        <div class="flex flex-wrap items-center">
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                    <i class="fa-solid fa-flag mr-2"></i>{{ __('statuses') }}
                                </h3>
                                <!-- Repeat status -->
                                @foreach ($statuses as $status)
                                    @livewire('admin.status-setup', ['status' => $status], key($status->id))
                                @endforeach
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4 md:min-w-full" />
                        <!-- Heading -->
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
        <script src="{{ asset('assets/vendor/colorpicker/jscolor.min.js') }}"></script>
    @endpush
</x-app-layout>
