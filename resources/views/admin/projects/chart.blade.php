<x-app-layout>
    <div class="flex flex-wrap mt-4">

        <div class="w-full mb-12 px-4">
            <x-projects.info :project="$project" />

            <div class="relative flex flex-wrap w-full mb-6 shadow-lg rounded bg-white">
                <div class="flex-1 m-5 h-72">
                    <livewire:livewire-pie-chart key="{{ $pieChartModel->reactiveKey() }}"
                        :pie-chart-model="$pieChartModel" />
                </div>
                @if (count($issuescolumnChart))
                    <div class="flex-1 m-5 max-w-screen-sm h-80">
                        <livewire:livewire-column-chart :column-chart-model="$columnChartModel" />
                    </div>
                @endif
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-app-layout>
