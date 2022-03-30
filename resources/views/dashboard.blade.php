<x-app-layout>
    <div class="flex flex-wrap">
        <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-blueGray-700">
                <div class="rounded-t mb-0 px-4 py-3 bg-transparent">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full max-w-full flex-grow flex-1">
                            <h6 class="uppercase text-blueGray-100 mb-1 text-xs font-semibold">
                                {{ __('overview') }}
                            </h6>
                            <h2 class="text-white text-xl font-semibold first-letter:uppercase">
                                {{ __('sales value') }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 flex-auto">
                    <!-- Chart -->
                    <div class="relative h-350-px">
                        <canvas id="line-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full xl:w-4/12 px-4">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                <div class="rounded-t mb-0 px-4 py-3 bg-transparent">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full max-w-full flex-grow flex-1">
                            <h6 class="uppercase text-blueGray-400 mb-1 text-xs font-semibold">
                                Performance
                            </h6>
                            <h2 class="text-blueGray-700 text-xl font-semibold first-letter:capitalize">
                                {{ __('sales value') }}
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="p-4 flex-auto">
                    <!-- Chart -->
                    <div class="relative h-350-px">
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-wrap mt-4">
        <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3 class="font-semibold capitalize text-base text-blueGray-700">
                                {{ __('Latest Projects') }}
                            </h3>
                        </div>

                        <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                            <button
                                class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                type="button">
                                <a href="{{ route('admin.projects.index') }}">{{ __('See all') }}</a>
                            </button>
                        </div>

                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                    <!-- Projects table -->
                    <table class="items-center w-full bg-transparent border-collapse">
                        <thead class="thead-light">
                            <tr>
                                <th
                                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    {{ __('project') }}
                                </th>
                                <th
                                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    {{ __('sprints') }}
                                </th>
                                <th
                                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left min-w-140-px">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                <tr>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                        <a
                                            href="{{ route('admin.projects.issues.index', [$project, 'userId' => request('userId')]) }}">
                                            {{ $project->name }}
                                        </a>

                                        [{{ $project->issues_count }}]
                                        @if ($project->count_new_issues)
                                            <span
                                                class="text-[10px] text-yellow-500">[{{ $project->count_new_issues }}
                                                {{ __('untouched') }}]</span>
                                        @endif

                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <a href="{{ route('admin.projects.sprints.index', [$project->id]) }}"
                                            class="hover:text-blue-500">
                                            [view]
                                        </a>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <div class="flex items-center">
                                            <x-avg
                                                avg="{{ $project->active_issues_count? (int) ((100 * $project->verified_issues_count) / $project->active_issues_count): 0 }}" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="w-full xl:w-4/12 px-4">
            <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">

                            <h3 class="font-semibold text-base text-blueGray-700">
                                <span class="text-xs">
                                    <x-users.status online="true" />
                                </span> {{ __('online users') }}
                            </h3>
                        </div>
                        @if (auth()->user()->isSuperAdmin)
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                                <button
                                    class="bg-indigo-500 text-white active:bg-indigo-600 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                    type="button">
                                    <a href="{{ route('admin.users.index') }}">{{ __('see all') }}</a>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                    <!-- Projects table -->
                    <!-- users table -->
                    <table class="items-center w-full bg-transparent border-collapse">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    {{ __('name') }}
                                </th>
                                <th
                                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                    {{ __('contact') }}
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($onlineUsers as $user)
                                <tr>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                                        <span style="color:{{ $user->role->color }}"> {!! $user->role->icon !!}</span>
                                        {{ $user->name }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <i class="fa-solid fa-envelope"></i> {{ $user->email }}
                                        @if (auth()->user()->isSuperAdmin && $user->phone)
                                            <div>
                                                <i class="fa-solid fa-mobile-screen-button"></i>
                                                {{ $user->phone }}
                                            </div>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        @php
            $years = $projectsBudgetByMonthYear
                ->unique('year')
                ->values()
                ->pluck('year')
                ->sort();
            $labels = $projectsBudgetByMonthYear->unique('month')->pluck('month');
        @endphp
        <script>
            (function() {
                /* Chart initialisations */
                /* Line Chart */
                var config = {
                    type: "line",
                    data: {
                        labels: @json($labels),
                        datasets: [

                            @foreach ($years as $year)
                                @php
                                    $color = '#' . dechex(rand(0x000000, 0xffffff));
                                    $profitByMonthsYear = $projectsBudgetByMonthYear
                                        ->filter(function ($item) use ($year) {
                                            return $item->year == $year;
                                        })
                                        ->pluck('profit', 'month');

                                    $data = [];
                                    foreach ($labels as $month) {
                                        array_push($data, $profitByMonthsYear[$month] ?? 0);
                                    }

                                @endphp
                                {
                                label: {{ $year }},
                                backgroundColor: '{{ $color }}',
                                borderColor: '{{ $color }}',
                                data: @json($data),
                                fill: false,
                                },
                            @endforeach

                        ],

                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        title: {
                            display: false,
                            text: "Sales Charts",
                            fontColor: "white",
                        },
                        legend: {
                            labels: {
                                fontColor: "white",
                            },
                            align: "end",
                            position: "bottom",
                        },
                        tooltips: {
                            mode: "index",
                            intersect: false,
                        },
                        hover: {
                            mode: "nearest",
                            intersect: true,
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    fontColor: "rgba(255,255,255,.7)",
                                },
                                display: true,
                                scaleLabel: {
                                    display: false,
                                    labelString: "Month",
                                    fontColor: "white",
                                },
                                gridLines: {
                                    display: false,
                                    borderDash: [2],
                                    borderDashOffset: [2],
                                    color: "rgba(33, 37, 41, 0.3)",
                                    zeroLineColor: "rgba(0, 0, 0, 0)",
                                    zeroLineBorderDash: [2],
                                    zeroLineBorderDashOffset: [2],
                                },
                            }, ],
                            yAxes: [{
                                ticks: {
                                    fontColor: "rgba(255,255,255,.7)",
                                },
                                display: true,
                                scaleLabel: {
                                    display: false,
                                    labelString: "Value",
                                    fontColor: "white",
                                },
                                gridLines: {
                                    borderDash: [3],
                                    borderDashOffset: [3],
                                    drawBorder: false,
                                    color: "rgba(255, 255, 255, 0.15)",
                                    zeroLineColor: "rgba(33, 37, 41, 0)",
                                    zeroLineBorderDash: [2],
                                    zeroLineBorderDashOffset: [2],
                                },
                            }, ],
                        },
                    },
                };
                var ctx = document.getElementById("line-chart").getContext("2d");
                window.myLine = new Chart(ctx, config);

                /* Bar Chart */
                config = {
                    type: "bar",
                    data: {
                        labels: @json($labels),
                        datasets: [
                            @foreach ($years as $year)
                                @php
                                    $color = '#' . dechex(rand(0x000000, 0xffffff));
                                    $profitByMonthsYear = $projectsBudgetByMonthYear
                                        ->filter(function ($item) use ($year) {
                                            return $item->year == $year;
                                        })
                                        ->pluck('profit', 'month');

                                    $data = [];
                                    foreach ($labels as $month) {
                                        array_push($data, $profitByMonthsYear[$month] ?? 0);
                                    }

                                @endphp
                                {
                                label: {{ $year }},
                                backgroundColor: "{{ $color }}",
                                borderColor: "{{ $color }}",
                                data: @json($data),
                                fill: false,
                                barThickness: 8,
                                },
                            @endforeach
                        ],

                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        title: {
                            display: false,
                            text: "Orders Chart",
                        },
                        tooltips: {
                            mode: "index",
                            intersect: false,
                        },
                        hover: {
                            mode: "nearest",
                            intersect: true,
                        },
                        legend: {
                            labels: {
                                fontColor: "rgba(0,0,0,.4)",
                            },
                            align: "end",
                            position: "bottom",
                        },
                        scales: {
                            xAxes: [{
                                display: false,
                                scaleLabel: {
                                    display: true,
                                    labelString: "Month",
                                },
                                gridLines: {
                                    borderDash: [2],
                                    borderDashOffset: [2],
                                    color: "rgba(33, 37, 41, 0.3)",
                                    zeroLineColor: "rgba(33, 37, 41, 0.3)",
                                    zeroLineBorderDash: [2],
                                    zeroLineBorderDashOffset: [2],
                                },
                            }, ],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: false,
                                    labelString: "Value",
                                },
                                gridLines: {
                                    borderDash: [2],
                                    drawBorder: false,
                                    borderDashOffset: [2],
                                    color: "rgba(33, 37, 41, 0.2)",
                                    zeroLineColor: "rgba(33, 37, 41, 0.15)",
                                    zeroLineBorderDash: [2],
                                    zeroLineBorderDashOffset: [2],
                                },
                            }, ],
                        },
                    },
                };
                ctx = document.getElementById("bar-chart").getContext("2d");
                window.myBar = new Chart(ctx, config);
            })();
        </script>
    @endpush
</x-app-layout>
