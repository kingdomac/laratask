<x-app-layout>

    <div class="flex flex-wrap mt-4">

        <div class="w-full mb-12 px-4">
            <x-projects.info :project="$project" />

            <div class="relative flex flex-col min-w-0 w-full mb-6 shadow-lg rounded bg-white">

                @livewire('admin.issue.issues-table', ['project' => $project])
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-app-layout>
