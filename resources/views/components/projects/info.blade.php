<div x-data="{ expanded: false }" x-cloak
    class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
    <div class="rounded-t mb-0 px-4 py-3 border-0">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex flex-grow flex-1 gap-2 relative w-full max-w-full">
                <h3 class="font-semibold text-sm text-amber-500 uppercase">
                    <i class="fas fa-diagram-project mr-2 text-sm text-amber-500"></i>
                    <a href="{{ route('admin.projects.index') }}">
                        {{ __('projects') }}
                    </a> / <span class=" text-blueGray-700">
                        <a
                            href="{{ route('admin.projects.issues.index', [$project->id, 'sprintId' => request('sprintId')]) }}">
                            {{ $project->name }}
                        </a></span>
                </h3>
                <a href="{{ URL::previous() }}"><i class="fa-solid fa-backward" title="back"></i></a>
            </div>
            <template x-if="expanded">
                <a @click="expanded=false">
                    <i class="fa-solid fa-caret-up text-lg cursor-pointer"></i>
                </a>
            </template>
            <template x-if="!expanded">
                <a @click="expanded=true">
                    <i class="fa-solid fa-caret-down text-lg cursor-pointer"></i>
                </a>
            </template>
        </div>

    </div>

    <div x-show="expanded" x-collapse.duration.1000ms class=" w-full overflow-x-auto">
        <!-- Divider -->
        <hr class="my-1 md:min-w-full" />
        <!-- Heading -->
        <div class="px-5 pt-5">
            <div class="w-full">
                <div class="flex flex-wrap content-start w-full justify-between font-medium text-xs text-gray-700">
                    <div class="flex flex-col gap-2">
                        @if (auth()->user()->isSuperAdmin)
                            <div class="flex gap-2 text-blueGray-400">
                                <div class="capitalize">
                                    <i class="fa-brands fa-creative-commons-by"></i>
                                    {{ __('by') }}:
                                </div>
                                <div>{{ $project->creator->name }}</div>
                            </div>
                            <div class="flex flex-wrap gap-2 text-blueGray-400">
                                <div class="flex gap-1">
                                    <div class="capitalize">
                                        <i class="fa-solid fa-certificate"></i>
                                        {{ __('owner') }}:
                                    </div>
                                    <div>{{ $project->agent->name }}</div>
                                </div>
                                <div class="flex">
                                    <div class="capitalize">
                                        <i class="fa-solid fa-square-phone"></i>
                                        {{ $project->agent->phone }}
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="capitalize">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $project->agent->address }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 text-blueGray-400">
                                <div class="capitalize">
                                    <i class="fa-solid fa-calendar"></i>
                                    {{ __('created at') }}:
                                </div>
                                <div>{{ $project->created_at }}</div>
                            </div>
                        @endif
                        <div class="flex gap-2">
                            <div class="capitalize">
                                <i class="fa-solid fa-calendar"></i>
                                {{ __('due date') }}:
                            </div>
                            <div class="text-red-300">{{ $project->due_date->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>
                    @if (auth()->user()->isSuperAdmin)
                        <div class="flex gap-2 items-center font-semibold text-sm">
                            <div class="capitalize">
                                <i class="fa-solid fa-money-bill"></i>
                                {{ __('budget') }}:
                            </div>
                            <div class="text-green-700">${{ number_format($project->budget, 0) }} USD
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Divider -->
                <hr class="my-4 md:min-w-full" />
                <!-- Heading -->
                <div>
                    <div class="relative w-full max-w-full">
                        <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                            <i class="fa-solid fa-circle-info mr-2"></i>{{ __('info') }}
                        </h3>
                    </div>
                    <div class="pt-5 text-xs">
                        <div class="flex items-center gap-2 font-medium text-xs text-gray-700">
                            <div class="capitalize text-blue-500">
                                <i class="fa-brands fa-internet-explorer"></i>
                                {{ __('website address') }}:
                            </div>
                            <div><a href="{{ $project->website }}" class="hover:text-gray-400"
                                    target="_blank">{{ $project->website }}</a></div>
                        </div>
                        <div class="pt-5 pb-5 text-xs leading-[1.6]">
                            {!! $project->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
