<div x-data="data" class="px-4 py-3">
    <!----- DELETE MODAL ------>
    <x-delete-modal />
    <!------ END MODAL ----->


    <!---- Issues Modal ---->
    @livewire('admin.issue.issues-modal');
    <!------ END MODAL ----->


    <div class="relative w-full  max-w-full flex">
        <div class="relative w-full px-2 max-w-full flex-grow flex-1">
            <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                <i class="fa-solid fa-arrow-rotate-left"></i> {{ __('sprints') }}
            </h3>
        </div>

        @if (auth()->user()->isSuperAdmin)
            <a x-show="!showForm" @click="showForm=true" href="#" title="new sprint" class="cursor-pointer">
                <i class="fa-solid  fa-square-plus text-lg cursor-pointer"></i>
            </a>
        @endif
    </div>
    <div x-show="showForm" x-transition x-transition:enter.duration.500ms x-cloak class="mt-5 p-4 bg-gray-100">
        <div class="flex flex-col">
            <x-alert name="name" />
            <x-alert name="start_date" />
            <x-alert name="end_date" />
        </div>
        <form wire:submit.prevent='create' id="sprintForm">
            <div class="flex flex-wrap items-end gap-2">
                <div>
                    <x-label for="name" class="capitalize">{{ __('name') }}</x-label>
                    <x-input wire:model.defer="name" id="name" class="mt-2" />

                </div>
                <div class="flex flex-wrap gap-3">
                    <div>
                        <x-label for="start_date" class="capitalize">{{ __('start at') }}</x-label>
                        <x-input wire:model="start_date" id="start_date" class="mt-2" type="datetime-local" />

                    </div>
                    <div>
                        <x-label for="end_date" class="capitalize">{{ __('end at') }}</x-label>
                        <x-input wire:model.defer="end_date" id="end_date" class="mt-2"
                            type="datetime-local" />

                    </div>
                </div>
                <div class="flex gap-3 items-end">
                    <x-button>save</x-button>
                    <x-button @click="showForm=false;document.getElementById('sprintForm').reset()" type="button"
                        class="bg-gray-200 text-gray-400">cancel</x-button>
                </div>

            </div>
        </form>
    </div>
    <div class="mt-10 px-4">
        <x-message type="success" />
        <div class="flex justify-between overflow-x-auto gap-5 w-full p-2">
            <table class="items-center w-full bg-transparent border-collapse">
                <thead>
                    <tr>
                        <th
                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">

                        </th>
                        <th
                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                            {{ __('issues') }}
                        </th>
                        <th
                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                            {{ __('start date') }}
                        </th>
                        <th
                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                            {{ __('end date') }}
                        </th>
                        <th
                            class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                            {{ __('Completion') }}
                        </th>
                        @if (auth()->user()->isSuperAdmin)
                            <th
                                class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sprints as $sprint)
                        <tr class="hover:bg-gray-200

                        ">
                            <td
                                class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center capitalize">
                                {{ $sprint->name }}
                                <span class="text-blue-500 ml-2">[{{ $sprint->issues_count }}]</span>
                            </td>
                            <td
                                class=" border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                <div class="flex gap-2">
                                    @if (auth()->user()->isSuperAdmin)
                                        <a wire:loading.class='opacity-10'
                                            wire:target='showIssuesModal({{ $sprint->id }})'
                                            wire:click.prevent="showIssuesModal({{ $sprint->id }})" href="#"
                                            class="text-gray-200 bg-blue-500 border rounded-lg px-2 py-1 shadow-md hover:shadow-blue-400">
                                            <i class="fa-solid fa-folder-tree" title="add issues to sprint"></i>
                                            {{ __('issues') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.projects.issues.index', [$project->id, 'sprintId' => $sprint->id]) }}"
                                        class="text-gray-200 bg-blue-500 border rounded-lg px-2 py-1 shadow-md hover:shadow-blue-400">
                                        <i class="fa-solid fa-binoculars" title="view sprints issues"></i>
                                        {{ __('issues') }}
                                    </a>
                                </div>
                            </td>
                            <td
                                class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                {{ $sprint->start_date ? date('d-m-Y h:i A', strtotime($sprint->start_date)) : '' }}
                            </td>
                            <td
                                class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                {{ $sprint->end_date ? date('d-m-Y h:i A', strtotime($sprint->end_date)) : '' }}
                            </td>
                            <td
                                class="border-t-0 px-6  align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                <div class="flex items-center">
                                    @php
                                        $avg = $sprint->active_issues_count ? (100 * $sprint->verified_issues_count) / $sprint->active_issues_count : 0;
                                    @endphp
                                    <x-avg :avg="$avg" />
                                </div>
                            </td>

                            @if (auth()->user()->isSuperAdmin)
                                <td
                                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                                    <div class="flex flex-wrap items-center gap-3">

                                        <a
                                            href="{{ route('admin.projects.sprints.edit', [$project->id, $sprint->id]) }}">
                                            <i class="fa-solid fa-square-pen text-sm hover:text-blue-500"></i>
                                        </a>

                                        <a @click.prevent='showDeleteModal("{{ route('admin.projects.sprints.destroy', [$project->id, $sprint->id]) }}")'
                                            href="#">
                                            <i class="fa-solid fa-trash text-sm text-red-600 hover:text-red-500"></i>
                                        </a>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr class="mt-3 border-b-1 border-blueGray-300" />
    </div>
    @push('scripts')
        <script>
            const data = {
                isShow: false,
                url: null,
                showForm: false,
                hideDeleteModal() {
                    this.isShow = false
                    this.url = null
                },
                showDeleteModal(url) {
                    this.isShow = true
                    this.url = url

                },

            }
        </script>
    @endpush
</div>
