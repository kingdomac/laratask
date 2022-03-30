<x-app-layout>
    <div x-data="data" class="flex flex-wrap mt-4">
        <!----- DELETE MODAL ------>
        <x-delete-modal />
        <!------ END MODAL ----->
        <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3 class="font-semibold text-lg text-blueGray-700 uppercase">
                                <i
                                    class="fas fa-diagram-project mr-2 text-sm text-blueGray-300"></i>{{ __('projects') }}
                            </h3>
                        </div>
                        <div class="mr-5">
                            <form action="">
                                <x-input-icon name="keyword" value="{{ request('keyword') }}"
                                    icon="<i class='fa-solid fa-magnifying-glass'></i>" autofocus />
                            </form>
                        </div>
                        @if (auth()->user()->isSuperAdmin)
                            <a href="{{ route('admin.projects.create') }}" title="new project" class="cursor-pointer">
                                <i class="fa-solid  fa-square-plus text-lg cursor-pointer"></i>
                            </a>
                        @endif
                    </div>
                    <div class="mt-2">
                        <x-message type="success" />
                    </div>
                </div>

                <div class="block w-full overflow-x-auto">
                    @if (count($projects))
                        <!-- Projects table -->
                        <table class="items-center w-full bg-transparent border-collapse">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        {{ __('Project') }}
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        {{ __('sprints') }}
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        {{ __('Budget') }}
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        {{ __('Due Date') }}
                                    </th>
                                    <th
                                        class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                        {{ __('Agent') }}
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
                                @foreach ($projects as $project)
                                    <tr
                                        class="hover:bg-gray-200 @if ($project->count_new_issues) bg-yellow-100 hover:bg-yellow-200 @endif ">
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center">
                                            <a href="{{ route('admin.projects.issues.index', [$project, 'userId' => request('userId')]) }}"
                                                class="relative flex items-center">
                                                <i class="fa-solid fa-suitcase"></i>
                                                <span class="ml-3 font-bold text-blueGray-600">
                                                    {{ $project->name }}
                                                    [{{ $project->issues_count }}]
                                                    @if ($project->count_new_issues)
                                                        <span
                                                            class="text-[10px] text-yellow-500">[{{ $project->count_new_issues }}
                                                            {{ __('untouched') }}]</span>
                                                    @endif

                                                    @if ($project->inDanger)
                                                        <span class="text-red-500 absolute -left-5 text-lg -top-3">
                                                            <i class="fa-solid fa-flag-checkered"></i>
                                                        </span>
                                                    @endif
                                                </span>
                                            </a>
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.projects.sprints.index', [$project->id]) }}"
                                                    class="hover:text-blue-500">
                                                    [view]
                                                </a>

                                            </div>
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            ${{ number_format($project->budget, 0, ',') }}USD
                                        </td>
                                        <td
                                            class="border-t-0 px-6  align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            <i class="fa-solid fa-calendar"></i>
                                            {{ date('d-m-Y', strtotime($project->due_date)) }}
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            <div class="flex flex-wrap gap-1 items-center">
                                                {{-- <img src="../../assets/img/team-1-800x800.jpg" alt="..."
                                                class="w-10 h-10 rounded-full border-2 border-blueGray-50 shadow" /> --}}
                                                <span>{{ $project->agent->name }} </span>
                                            </div>
                                        </td>
                                        <td
                                            class="border-t-0 px-6 align-middle border-/l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                            <div class="flex items-center">
                                                <x-avg
                                                    avg="{{ $project->active_issues_count? (int) ((100 * $project->verified_issues_count) / $project->active_issues_count): 0 }}" />
                                            </div>
                                        </td>
                                        @if (auth()->user()->isSuperAdmin)
                                            <td
                                                class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                                                <div class="flex flex-wrap items-center gap-3">
                                                    <a href="{{ route('admin.projects.charts', [$project->id]) }}">
                                                        <i class="fa-solid fa-chart-pie hover:text-blue-500"></i>
                                                    </a>
                                                    <a href="{{ route('admin.projects.edit', $project->id) }}"><i
                                                            class="fa-solid fa-square-pen text-sm hover:text-blue-500"></i></a>
                                                    <a @click.prevent='showDeleteModal("{{ route('admin.projects.destroy', $project->id) }}")'
                                                        href="#"><i
                                                            class="fa-solid fa-trash text-sm text-red-600 hover:text-red-500"></i></a>

                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    @if (!count($projects))
                        <div class="bg-gray-50 rounded-lg py-5 px-6 mb-3 text-base text-gray-500 inline-flex items-center w-full"
                            role="alert">
                            <i class="fa-solid fa-face-frown mr-2"></i>
                            {{ __('No projects!') }}
                        </div>
                    @endif
                </div>
                <div class="p-2 text-xs">{{ $projects->links() }}</div>
            </div>
        </div>

    </div>
    @push('scripts')
        <script>
            const data = {
                isShow: false,
                url: null,

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
    @endpush()

</x-app-layout>
