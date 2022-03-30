<x-app-layout>
    <div class="flex flex-wrap md:mt-4">
        <div class="w-full mb-12 px-4">
            <x-projects.info :project="$project" />
            @livewire('admin.issue.issue-details', ['project' => $project, 'issueId' => $issue->id])
        </div>
        @push('scripts')
            <script src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
        @endpush
</x-app-layout>
