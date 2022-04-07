<div x-data="{ expanded: true, show: false }" x-cloak
    class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
    <div class="rounded-t mb-0 px-4 py-3">
        <div class="flex flex-wrap gap-3 items-center content-center">
            <div class="relative">
                <h3 class="font-semibold text-sm  text-amber-500 uppercase">
                    @if ($issue->parent_id)
                        <span style="color:{{ $issue->parent->label->color }}">{!! $issue->parent->label->icon !!}</span>
                        <span class=" text-blueGray-700">
                            <a
                                href="{{ route('admin.projects.issues.show', [$issue->project_id, $issue->parent->id]) }}">
                                {{ $issue->parent->title }}
                            </a>

                        </span>
                        /
                    @endif
                    <span style="color:{{ $issue->label->color }}">{!! $issue->label->icon !!}</span>
                    <span class=" text-blueGray-700">{{ $issue->title }}</span>
                    <span
                        class="text-gray-400 text-xs">{{ $issue->label->name }}[{{ $issue->children_count }}]</span>
                </h3>
            </div>
            @if ($issue->sprint_id)
                <div
                    class="capitalize font-semibold border rounded-lg shadow-sm hover:shadow-md px-4 py-2 text-xs text-gray-600 w-fit">
                    <a
                        href="{{ route('admin.projects.issues.index', [$project->id, 'sprintId' => $issue->sprint_id]) }}">
                        <i class="fa-solid fa-arrow-rotate-left"></i> {{ $issue->sprint?->name }}</a>
                </div>
            @endif
            <div class="flex-grow flex items-center text-xs">
                @if (auth()->user()->isSuperAdmin)
                    @php
                        $avg = $issue->isStory && $issue->active_issues_count ? 100 * ($issue->verified_count / $issue->active_issues_count) : number_format($issue->completion_perc) + 0;
                    @endphp
                @else
                    @php
                        $avg = $issue->isStory ? number_format($issue->children_avg_completion_perc, 1) + 0 : number_format($issue->completion_perc) + 0;
                    @endphp
                @endif
                <x-avg :avg="$avg" class="w-1/2" />

            </div>


            @if ($issue->isStory && auth()->user()->isSuperAdmin)
                <div class="capitalize content-center text-sm text-blue-700">
                    <a wire:click="$emitTo('admin.issue.issue-form-modal', 'show');" class="cursor-pointer"
                        title="create subtask">
                        <i class="fa-solid fa-square-plus"></i>
                        {{ __('subtask') }}
                    </a>
                </div>
            @endif
        </div>

    </div>
    <div class="mt-2">
        <x-message type="error" />
    </div>
    <div class="flex w-full overflow-x-auto">
        <div class="flex-1">
            <!-- Divider -->
            <hr class="my-4 md:min-w-full" />
            <!-- Heading -->
            <div class="px-5">
                <div class="w-full text-sm">
                    <div
                        class="flex flex-wrap gap-2 content-start items-start w-full justify-between font-medium text-xs text-gray-700">
                        <div class="flex flex-col gap-2">
                            <div class="flex gap-2 text-blueGray-400">
                                <div class="capitalize">
                                    <i class="fa-brands fa-creative-commons-by"></i>
                                    {{ __('by') }}:
                                </div>
                                <div class="capitalize">
                                    @if ($issue->creator->id != auth()->user()->id)
                                        {{ $issue->creator->name }}
                                    @else
                                        {{ __('me') }}
                                    @endif
                                </div>
                            </div>
                            @if ($issue->updater)
                                <div class="flex gap-2 text-blueGray-400">
                                    <div class="capitalize">
                                        <i class="fa-brands fa-creative-commons-by"></i>
                                        {{ __('updated by') }}:
                                    </div>

                                    <div class="capitalize">
                                        @if ($issue->updater->id != auth()->id())
                                            {{ $issue->updater?->name }}
                                        @else
                                            {{ __('me') }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="flex gap-2 text-blueGray-400">
                                <div class="capitalize">
                                    <i class="fa-solid fa-calendar"></i>
                                    {{ __('created at') }}:
                                </div>
                                <div>{{ $issue->created_at }}</div>
                            </div>
                            @if (!$issue->isStory && (!auth()->user()->isAdmin || ($issue->status_id != App\Enums\StatusEnum::CANCELED->value && $issue->status_id != App\Enums\StatusEnum::VERIFIED->value)))
                                <div class="flex capitalize items-center gap-2">
                                    {{ __('completion') }}:
                                    <x-input wire:model.lazy.trim="completionPerc" type="number" max="100"
                                        class="h-6" />
                                    <i wire:loading.class='opacity-10' wire:target='completionPerc'
                                        class="fa-solid fa-percent"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col gap-2 items-center font-semibold text-xs">

                            <div class="flex flex-col gap-2 justify-between font-semibold">
                                {{-- check if time spent not null --}}
                                <div class="flex gap-2 capitalize">
                                    <div class="">
                                        <i class="fa-solid fa-clock"></i>
                                        {{ __('time spent') }}:
                                    </div>
                                    <div class="lowercase">
                                        @if ($issue->user_id)
                                            {{ $issue->time_spent ? $issue->time_spent . __('min') : '' }}
                                        @endif
                                    </div>
                                </div>

                                <div class="flex gap-2 capitalize">
                                    <div class="">
                                        <i class="fa-brands fa-first-order"></i>
                                        {{ __('priority') }}:
                                    </div>
                                    <div style="color:{{ $issue->priority?->color }}">
                                        {{ $issue->priority?->name }}
                                    </div>
                                </div>
                                {{-- check if user authenticated, or is superadmin --}}
                                @if (!$issue->isStory)
                                    <div x-data={showStatus:false}>
                                        <div class="flex gap-2">
                                            <div class="capitalize">
                                                <i class="fa-solid fa-satellite"></i>
                                                {{ __('status') }}:
                                            </div>
                                            <div style="color:{{ $issue->status?->color }}"
                                                class="capitalize flex gap-1 justify-start items-start">

                                                <span>{{ $issue->status?->id ? $issue->status->name : __('new') }}</span>
                                                @if (!auth()->user()->isAdmin || ($issue->status_id != App\Enums\StatusEnum::CANCELED->value && $issue->status_id != App\Enums\StatusEnum::VERIFIED->value))
                                                    <div>
                                                        <a @click.prevent="showStatus=!showStatus" href="#"
                                                            class="hover:text-blue-500"><i
                                                                class="fa-solid fa-square-pen text-sm"></i></a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div @click.away="showStatus=false" x-show="showStatus" class="flex-none mt-2">
                                            <select @change="showStatus=false" wire:model="statusId"
                                                wire:change='changeStatus'
                                                class="rounded-lg text-xs leading-tight block capitalize appearance-none">
                                                <option value="0">--{{ __('choose status') }}</option>
                                                @foreach ($statuses as $status)
                                                    @if (auth()->user()->isAdmin && ($status->id == App\Enums\StatusEnum::VERIFIED->value || $status->id == App\Enums\StatusEnum::CANCELED->value))
                                                        @continue
                                                    @endif
                                                    <option value="{{ $status->id }}">{{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </div>
                        @if (auth()->user()->isSuperAdmin)
                            <div class="flex flex-col gap-2 items-center font-semibold text-xs">
                                <div class="flex flex-col gap-2 justify-start items-start font-semibold">

                                    <div class="flex gap-2 items-center">
                                        <div class="capitalize">
                                            <i wire:loading.class='opacity-10' wire:target='storyPoint'
                                                class="fa-solid fa-circle-dot"></i>
                                            {{ __('story') }}:
                                        </div>
                                        <div class="text-green-700">
                                            @if (auth()->user()->isSuperAdmin)
                                                <x-input type="number" class="h-5 w-[100px]"
                                                    wire:model.lazy.trim="storyPoint" />
                                            @else
                                                {{ $issue->story_point }}
                                            @endif
                                        </div>
                                    </div>


                                    <div class="flex gap-2">
                                        <div class="capitalize">
                                            <i wire:loading.class='opacity-10' wire:target='valuePoint'
                                                class="fa-solid fa-bullseye"></i>
                                            {{ __('value') }}:
                                        </div>
                                        <div class="text-green-700">
                                            @if (auth()->user()->isSuperAdmin)
                                                <x-input type="number" wire:model.lazy.trim="valuePoint"
                                                    class="h-5 w-[100px]" />
                                            @else
                                                {{ $issue->value_point }}
                                            @endif

                                        </div>
                                    </div>

                                    <!-- Divider -->
                                    <hr class="md:min-w-full" />
                                    <!-- Heading -->
                                    @if ($issue->story_point)
                                        <div class="flex gap-2">
                                            <div class="capitalize">
                                                {{ __('BFTB') }}:
                                            </div>
                                            <div class="text-green-700">
                                                @if ($issue?->story_point > 0)
                                                    {{ number_format($issue?->value_point / $issue?->story_point, 2) + 0 }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                    <!-- Divider -->
                    <hr class="my-4 md:min-w-full" />
                    <!-- Heading -->
                    @if ($issue->isStory)
                        <div class="block">
                            <div class="relative w-full max-w-full">
                                <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                    <i class="fa-solid fa-list mr-1"></i>{{ __('subtasks') }}
                                    [{{ $issue->children_count }}]
                                </h3>
                            </div>
                            <div class="pt-2 pb-5 text-sm leading-[1.6]">
                                @livewire('admin.issue.issues-table', ['project' => $project, 'parentId' => $issue->id])
                            </div>
                        </div>
                    @endif
                    <!-- Summary -->
                    <div class="block">
                        <div class="relative w-full max-w-full">
                            <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                <i class="fa-solid fa-circle-info mr-1"></i>{{ __('summary') }}
                            </h3>
                        </div>
                        <div class="pt-2 pb-5 text-sm leading-[1.6]">
                            {!! nl2br(e($issue->details)) !!}
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="my-4 md:min-w-full" />
                    <!-- Heading -->

                    <!-- Archiving only for super admin -->
                    @if (auth()->user()->isSuperAdmin && $issue->history->count())
                        <div class="pb-5">
                            <div class="relative w-full max-w-full">
                                <h3 class="flex gap-1 items-center font-semibold text-xs text-blueGray-700 uppercase">
                                    <i class="fa-solid fa-folder-open"></i>{{ __('archive old assignement') }}
                                </h3>
                            </div>
                            <div
                                class="flex flex-col divide-dashed divide-y-2
                                         divide-coolGray-300
                                           text-xs
                                           text-gray-400">

                                @foreach ($issue->history as $history)
                                    <div class="flex w-full gap-4 pt-2">
                                        <div class="flex-none">{{ $history->name }}
                                        </div>

                                        <div class="flex-none">
                                            {{ $history->pivot->time_spent ?? __('--') }} {{ __('min') }}
                                        </div>


                                        <div class="flex-none capitalize">
                                            {{ $history->status_id ? $history->status->name : __('new') }}
                                        </div>
                                        <div>
                                            <span class="mr-2">
                                                {{ $history->pivot->completion_perc ? $history->pivot->completion_perc : '0' }}%
                                            </span>
                                        </div>
                                        <div>
                                            <span class="mr-2">
                                                {{ date('D d-m-Y g:i a', strtotime($history->pivot->created_at)) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach



                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @livewire('admin.issue.issue-form-modal', ['project' => $project, 'parentId' => $issue->id])
    </div>
</div>
