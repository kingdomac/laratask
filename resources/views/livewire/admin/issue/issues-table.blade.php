<div x-data="{ showFilter: false, showTaskForm: true, showAssignUserModal: false, selectedIssue: null }" class="relative rounded-t mb-0 px-4 py-3">
    @if ($showDeleteModal)
        <x-delete-confirmation-modal />
    @endif

    @livewire('admin.issue.story-list-modal')
    @livewire('admin.user.assign')
    @if (!$parentId)
        <div class="flex flex-wrap justify-between items-center content-center ">
            <div class="flex gap-2 relative w-full  max-w-full flex-grow flex-1">
                <h3 class="font-semibold text-lg text-blueGray-700 uppercase">
                    <i class="fa-solid fa-list"></i>
                    {{ __('issues') }} [{{ count($issues) }}]
                </h3>
                @if ($sprint->id > 0)
                    <div
                        class="capitalize font-semibold bg-blue-300 text-gray-100 border rounded-lg shadow-sm px-4 py-2 text-xs w-fit">
                        <i class="fa-solid fa-arrow-rotate-left"></i> {{ $sprint->name }}
                    </div>

                    <div
                        class="capitalize font-semibold bg-blue-500 text-gray-100 border rounded-lg shadow-sm px-4 py-2 text-xs w-fit">
                        <a href="{{ route('admin.projects.sprints.index', $sprint->project_id) }}">
                            <i class="fa-solid fa-arrow-rotate-left"></i> {{ __('back to sprints') }}
                        </a>
                    </div>
                @endif
            </div>
            <div class="mr-5">
                <x-input-icon wire:model.debounce.200ms="keyword" icon="<i class='fa-solid fa-magnifying-glass'></i>" />
            </div>
            <div class="flex gap-2 items-baseline">
                <template x-if="!showFilter">
                    <a @click="showFilter=true" title="open filter" class="cursor-pointer">
                        <i class="fa-solid fa-filter"></i>
                    </a>
                </template>
                <template x-if="showFilter">
                    <a @click="showFilter=false" title="close filter" class="cursor-pointer">
                        <i class="fa-solid fa-filter-circle-xmark"></i>
                    </a>

                </template>
                @if (auth()->user()->isSuperAdmin)
                    <a @click="showTaskForm=true;selectedIssue=null"
                        wire:click="$emitTo('admin.issue.issue-form-modal', 'show')" title="new issue"
                        class="cursor-pointer">
                        <i class="fa-solid  fa-square-plus text-lg cursor-pointer"></i>
                    </a>
                @endif
            </div>

        </div>
    @endif
    <div class="flex flex-col gap-3 px-5">
        <div class="block w-full overflow-x-auto mt-5">
            @if (!$parentId)
                <!-- Filtration -->
                <div x-show="showFilter" x-transition x-cloak class="p-5  border-b-2 border-black-50">

                    <div class="flex flex-wrap gap-2 justify-between text-sm">
                        <div>
                            <div class="capitalize font-medium">{{ __('labels') }}:</div>
                            <div class="flex justify-start ml-1 gap-2 mb-3">
                                @foreach ($labels as $label)
                                    <div class="form-check form-check-inline">
                                        <input wire:model="selectedLabels"
                                            class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                            type="checkbox" id="label{{ $label->id }}" value="{{ $label->id }}">
                                        <label class="form-check-label capitalize inline-block text-gray-800"
                                            for="label{{ $label->id }}" style="color:{{ $label->color }}">
                                            {!! $label->icon !!}
                                            {{ $label->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                        <div>
                            <div class="capitalize font-medium">{{ __('priority') }}:</div>
                            <div class="flex justify-start gap-2 mb-3">

                                @foreach ($priorities as $priority)
                                    <div class="form-check form-check-inline">
                                        <input wire:model="selectedPriorities"
                                            class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                            type="checkbox" id="priority{{ $priority->id }}"
                                            value="{{ $priority->id }}">
                                        <label class="form-check-label capitalize inline-block text-gray-800"
                                            for="priority{{ $priority->id }}" style="color:{{ $priority->color }}">
                                            {{ $priority->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-5 justify-between text-sm">
                        <div>
                            <div class="capitalize font-medium">{{ __('status') }}:</div>
                            <div class="flex flex-wrap justify-start ml-1 gap-2 mb-3">
                                <div class="form-check form-check-inline">
                                    <input wire:model="selectedStatuses"
                                        class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                        type="checkbox" id="priorityNew" value="new">
                                    <label class="form-check-label capitalize inline-block text-gray-800"
                                        for="priorityNew" style="color:black">
                                        {{ __('new') }}
                                    </label>
                                </div>
                                @foreach ($statuses as $status)
                                    <div class="form-check form-check-inline">
                                        <input wire:model="selectedStatuses"
                                            class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                            type="checkbox" id="status{{ $status->id }}"
                                            value="{{ $status->id }}">
                                        <label class="form-check-label capitalize inline-block text-gray-800"
                                            for="status{{ $status->id }}" style="color:{{ $status->color }}">
                                            {{ $status->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- issues table -->
            <x-message type="success" />
            <div id="ajax-msg"
                class="bg-green-100 hidden rounded-lg py-2 px-6 mb-2 text-xs text-green-700 items-center w-full">
            </div>
            <div wire:poll.30000ms class="flex">
                <div class="w-full">
                    <table border="0" class="items-center w-full bg-transparent">
                        @if (count($issues))
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        @if ($sortBy != 'order')
                                            <a wire:click.prevent="sortBy('order')" href="#">
                                                <i wire:loading.class="opacity-10" wire:loading.attr="disabled"
                                                    wire:target="sortBy('order')"
                                                    class="fa-solid fa-sort cursor-pointer mr-2"></i>{{ __('reset order') }}
                                            </a>
                                        @endif
                                        <span
                                            class="text-[10px] text-gray-400 capitalize">[{{ __('drag to sort') }}]</span>
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        <a wire:loading.class="opacity-10" wire:loading.attr="disabled"
                                            wire:target="sortBy('priority_id')" wire:click="sortBy('priority_id')"
                                            class="cursor-pointer underline">{{ __('priority') }}</a>
                                    </th>
                                    @if (!$parentId)
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                            <a wire:loading.class="opacity-10" wire:loading.attr="disabled"
                                                wire:target="sortBy('bftb')" wire:click="sortBy('bftb')"
                                                class="cursor-pointer underline" title="Bang For The Buck">BFTB</a>
                                        </th>
                                    @endif

                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        <a wire:loading.class="opacity-10" wire:loading.attr="disabled"
                                            wire:target="sortBy('status_id')" wire:click="sortBy('status_id')"
                                            class="cursor-pointer underline">{{ __('status') }}</a>
                                    </th>
                                    @if (auth()->user()->isSuperAdmin)
                                        <th
                                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                            <a wire:loading.class="opacity-10" wire:loading.attr="disabled"
                                                wire:target="sortBy('user_id')" wire:click="sortBy('user_id')"
                                                class="cursor-pointer underline">{{ __('user') }}</a>
                                        </th>
                                    @endif
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        {{ __('completion') }}
                                    </th>

                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                    </th>
                                </tr>
                            </thead>
                        @endif
                        <tbody id="page_list">
                            @foreach ($issues as $key => $issue)
                                <tr id="{{ $issue->id }}"
                                    :class="selectedIssue == {{ $issue->id }} ? 'bg-blue-300' : ''"
                                    class="w-full @if ($issue->is_new || $issue->count_new_children) bg-green-100 hover:bg-green-50
                                    @elseif($issue->isCanceled) bg-red-200 hover:bg-red-100
                                    @else hover:bg-gray-100 @endif">
                                    <td
                                        class="w-full border-t-0 px-6 align-middle
                                         text-left border-l-0 border-r-0
                                        text-xs whitespace-nowrap p-4">
                                        <div class="flex items-top gap-1" title="{{ $issue->label->name }}">

                                            <div style="color:{{ $issue->label->color }}">

                                                {{ $issue->id }}.
                                                {!! $issue->label->icon !!}
                                            </div>
                                            <div class="font-bold text-blueGray-600">
                                                <div class="flex items-top gap-1">
                                                    <a
                                                        href="{{ route('admin.projects.issues.show', [$project->id, $issue->id, 'userId' => request('userId'), 'sprintId' => request('sprintId')]) }}">
                                                        {{ $issue->title }}
                                                    </a>
                                                    @if ($issue->sprint_id && !$parentId)
                                                        <span
                                                            class="relative bg-blue-500 px-1 py-1 text-[0.7rem] rounded-lg text-gray-100">
                                                            @if (auth()->user()->isSuperAdmin)
                                                                <i wire:loading.class='opacity-5'
                                                                    wire:target='removeSprint({{ $issue->id }})'
                                                                    wire:click="removeSprint({{ $issue->id }})"
                                                                    class="fa-solid fa-circle-xmark absolute cursor-pointer text-red-500 -right-1 -top-1"
                                                                    title="remove from sprint"></i>
                                                            @endif
                                                            {{ $issue->sprint->name }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="text-[10px]">
                                                    {{ $issue->children_count && $issue->count_new_children != $issue->children_count ? '[' . $issue->children_count . ' ' . \Str::of('subtask')->plural($issue->children_count) . ']' : '' }}
                                                    <span class="text-red-400">
                                                        {{ $issue->count_new_children ? '[' . $issue->count_new_children . ' unviewed' . ']' : '' }}
                                                    </span>
                                                </div>

                                            </div>
                                        </div>

                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle capitalize border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <span
                                            style="color:{{ $issue->priority?->color }}">{{ $issue->priority?->name }}</span>
                                    </td>
                                    @if (!$parentId)
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            @if ($issue->bftb)
                                                {{ number_format($issue->bftb, 2) }}
                                            @endif

                                        </td>
                                    @endif

                                    <td
                                        class="border-t-0 px-6 align-middle  capitalize border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <div style="color:{{ $issue->status?->color }}">
                                            @if ($issue->isStory)
                                                <div class="flex flex-col text-[10px] text-gray-500 capitalize">
                                                    @if ($issue->verified_count)
                                                        <div>
                                                            <span class="font-semibold">{{ __('verified') }}</span>:
                                                            <span
                                                                class="text-blue-500">[{{ $issue->verified_count }}]</span>
                                                        </div>
                                                    @endif
                                                    @if ($issue->completed_count)
                                                        <div>
                                                            <span class="font-semibold">{{ __('completed') }}:</span>
                                                            <span
                                                                class="text-blue-500">[{{ $issue->completed_count }}]</span>
                                                        </div>
                                                    @endif
                                                    @if ($issue->todo_count)
                                                        <div>
                                                            <span class="font-semibold">{{ __('To do') }}:</span>
                                                            <span
                                                                class="text-blue-500">[{{ $issue->todo_count }}]</span>
                                                        </div>
                                                    @endif
                                                    @if ($issue->inprogress_count)
                                                        <div>
                                                            <span
                                                                class="font-semibold">{{ __('In progress') }}:</span>
                                                            <span
                                                                class="text-blue-500">[{{ $issue->inprogress_count }}]</span>
                                                        </div>
                                                    @endif
                                                    @if ($issue->pending_count)
                                                        <div>
                                                            <span class="font-semibold">{{ __('pending') }}:</span>
                                                            <span
                                                                class="text-blue-500">[{{ $issue->pending_count }}]</span>
                                                        </div>
                                                    @endif
                                                    @if ($issue->canceled_count)
                                                        <div>
                                                            <span class="font-semibold">{{ __('canceled') }}:</span>
                                                            <span
                                                                class="text-blue-500">[{{ $issue->canceled_count }}]</span>
                                                        </div>
                                                    @endif
                                                    @if ($issue->active_issues_count)
                                                        <div>
                                                            {{ $issue->active_issues_count }}/{{ $issue->children_count - $issue->canceled_count }}
                                                        </div>
                                                    @endif

                                                </div>
                                            @else
                                                {{ $issue->status ? $issue->status?->name : __('new') }}
                                                @if ($issue->isCompleted && auth()->user()->isSuperAdmin)
                                                    <a wire:loading.class='opacity-50'
                                                        wire:target='verify({{ $issue->id }})'
                                                        wire:click.prevent="verify({{ $issue->id }})"
                                                        href="#"
                                                        class="p-1 text-[10px] bg-blue-500 text-gray-200 rounded-lg">{{ __('verify') }}</a>
                                                @endif
                                            @endif

                                        </div>
                                    </td>
                                    @if (auth()->user()->isSuperAdmin)
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            <div class="flex flex-col flex-wrap text-[10px] gap-1 items-center">
                                                <i wire:click="$emitTo('admin.user.assign', 'show', {{ $issue->id }})"
                                                    class="fa-solid fa-people-arrows-left-right text-sm cursor-pointer hover:text-green-500"
                                                    title="assign to"></i>
                                                @if ($issue->isStory && $issue->children)
                                                    <div class="flex flex-wrap">
                                                        @foreach ($issue->children->unique('assignedTo') as $child)
                                                            <span>- {{ $child->assignedTo?->name }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    {{ $issue->assignedTo?->name }}
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <div class="flex items-center">
                                            @if ($issue->isStory)
                                                @if (auth()->user()->isSuperAdmin)
                                                    @php
                                                        $avg = $issue->active_issues_count ? (int) (100 * ($issue->verified_count / $issue->active_issues_count)) : 0;
                                                    @endphp
                                                @else
                                                    @php
                                                        $avg = $issue->active_issues_count ? (int) (100 * (($issue->verified_count + $issue->completed_count) / $issue->active_issues_count)) : 0;
                                                    @endphp
                                                @endif
                                            @else
                                                @php
                                                    $avg = $issue->completion_perc;
                                                @endphp
                                            @endif

                                            <x-avg :avg="$avg" />

                                        </div>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                                        <div class="flex flex-wrap items-center justify-end md:flex-nowrap gap-3">

                                            @if ($issue->label_id != 1 && auth()->user()->isSuperAdmin)
                                                <a wire:click.prevent="$emitTo('admin.issue.story-list-modal', 'show', {{ $issue->id }})"
                                                    href="#">
                                                    <i class="fa-solid fa-folder-tree hover:text-blue-500"
                                                        title="put into story"></i>
                                                </a>
                                            @endif

                                            @if (auth()->user()->isSuperAdmin)
                                                <a @click="showTaskForm=true;selectedIssue={{ $issue->id }}"
                                                    wire:click="$emitTo('admin.issue.issue-form-modal', 'show', {{ $issue->id }})"
                                                    href="#top" class="hover:text-blue-500">
                                                    <i class="fa-solid fa-square-pen text-sm" title="edit"></i>
                                                </a>

                                                <a wire:loading.class='opacity-10'
                                                    wire:target="showDeleteModal({{ $issue->id }})"
                                                    wire:click.prevent="showDeleteModal({{ $issue->id }})"
                                                    href="#">
                                                    <i class="fa-solid fa-trash text-sm text-red-600" title="delete">
                                                    </i>
                                                </a>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (!$parentId)
                    <a name="top"></a>
                    <div x-show="showTaskForm" x-cloak x-transition>
                        @livewire('admin.issue.issue-form-modal', ['project' => $project])
                    </div>
                @endif
            </div>

        </div>

    </div>


    @if (!count($issues))
        <div class="bg-gray-50 rounded-lg py-5 px-6 mb-3 text-base text-gray-500 inline-flex items-center w-full"
            role="alert">
            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="grin-hearts"
                class="w-4 h-4 mr-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 496 512">
                <path fill="currentColor"
                    d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zM90.4 183.6c6.7-17.6 26.7-26.7 44.9-21.9l7.1 1.9 2-7.1c5-18.1 22.8-30.9 41.5-27.9 21.4 3.4 34.4 24.2 28.8 44.5L195.3 243c-1.2 4.5-5.9 7.2-10.5 6l-70.2-18.2c-20.4-5.4-31.9-27-24.2-47.2zM248 432c-60.6 0-134.5-38.3-143.8-93.3-2-11.8 9.2-21.5 20.7-17.9C155.1 330.5 200 336 248 336s92.9-5.5 123.1-15.2c11.4-3.6 22.6 6.1 20.7 17.9-9.3 55-83.2 93.3-143.8 93.3zm133.4-201.3l-70.2 18.2c-4.5 1.2-9.2-1.5-10.5-6L281.3 173c-5.6-20.3 7.4-41.1 28.8-44.5 18.6-3 36.4 9.8 41.5 27.9l2 7.1 7.1-1.9c18.2-4.7 38.2 4.3 44.9 21.9 7.7 20.3-3.8 41.9-24.2 47.2z">
                </path>
            </svg>
            {{ __('No records!') }}
        </div>
    @endif
    @push('scripts')
        <script>
            $(document).ready(function() {
                $("#page_list").sortable({
                    placeholder: "ui-state-highlight",
                    update: function(event, ui) {
                        var sortableIds = new Array();
                        $('#page_list tr').each(function() {
                            sortableIds.push($(this).attr("id"));
                        });
                        $.ajax({
                            url: '{{ route('admin.issues.sort') }}',
                            method: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                sortableIds: sortableIds
                            },
                            success: function(data) {
                                $('#ajax-msg').fadeIn();
                                $('#ajax-msg').html(data);
                            }
                        });
                    }
                });

            });
        </script>
    @endpush

</div>
