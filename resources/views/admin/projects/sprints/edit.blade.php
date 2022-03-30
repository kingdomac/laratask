<x-app-layout>
    <div class="flex flex-wrap mt-4">
        <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                <i class="fas fa-diagram-project mr-2 text-sm text-blueGray-300"></i>
                                {{ __('edit sprint') }}

                            </h3>
                        </div>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                    <x-message type="danger" />
                    <form action="{{ route('admin.projects.sprints.update', [$project->id, $sprint->id]) }}"
                        class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="post">
                        @method('put')
                        @csrf
                        <div>
                            <x-label for="name" class="capitalize mb-1" value="{{ __('name') }}" />
                            <x-input name="name" id="name" class="block mt-2 w-full"
                                :value="old('name', $sprint->name)" />
                            <x-alert name="name" type="error" />
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <div>
                                <x-label for="start_date" class="capitalize">{{ __('start at') }}</x-label>
                                <x-input name="start_date"
                                    :value="$sprint->start_date ? substr(date('c', strtotime($sprint->start_date)), 0, 16) : ''"
                                    id="start_date" class="mt-2" type="datetime-local" />
                                <x-alert name="start_date" />
                            </div>
                            <div>
                                <x-label for="end_date" class="capitalize">{{ __('end at') }}</x-label>
                                <x-input name="end_date"
                                    :value="$sprint->end_date ? substr(date('c', strtotime($sprint->end_date)), 0, 16) : ''"
                                    id="end_date" class="mt-2" type="datetime-local" />
                                <x-alert name="end_date" />
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div
                            class="flex justify-start items-start pt-2 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="text-white  bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                save</button>
                            <a href="{{ url()->previous() }}"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                                back
                            </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
