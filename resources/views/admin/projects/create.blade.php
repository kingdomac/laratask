<x-app-layout>
    <div class="flex flex-wrap mt-4">
        <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                <i class="fas fa-diagram-project mr-2 text-sm text-blueGray-300"></i>
                                @if ($project->id)
                                    {{ __('edit project') }}
                                @else
                                    {{ __('new project') }}
                                @endif

                            </h3>
                        </div>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                    <x-message type="danger" />
                    <form
                        @if ($project->id) action="{{ route('admin.projects.update', $project->id) }}"
                        @else
                        action="{{ route('admin.projects.store') }}" @endif
                        class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="POST">
                        @if ($project->id)
                            @method('put')
                        @endif
                        @csrf
                        <div>
                            <x-label for="agnet_id" class="capitalize mb-1" value="{{ __('agent') }}" />
                            <x-select name="agent_id" id="agent_id">
                                <option value="" @selected('agent_id')>-- {{ __('select agent') }} --</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" @selected(old('agent_id', $project->agent_id) == $agent->id)>
                                        {{ $agent->name }}</option>
                                @endforeach
                            </x-select>
                            <x-alert name="agent_id" type="error" />
                        </div>
                        <div>
                            <x-label for="name" class="capitalize mb-1" value="name" />
                            <x-input :value="old('name', $project->name)" name="name" id="name"
                                class="block mt-1 w-full" type="text" autofocus />
                            <x-alert name="name" type="error" />
                        </div>
                        <div>
                            <x-label for="website" class="capitalize mb-1" value="website URL" />
                            <x-input-icon :value="old('website',$project->website)"
                                icon="<i class='fa-solid fa-link '></i>" name="website" id="website"
                                class="block mt-1 pl-8 w-full" type="text" />
                            <x-alert name="website" type="error" />
                        </div>
                        <div>
                            <x-label for="budget" class="capitalize mb-1" value="budget" />
                            <x-input-icon :value="old('budget',$project->budget)"
                                icon='<i class="fa-solid fa-circle-dollar-to-slot"></i>' name="budget" id="budget"
                                class="block mt-1 w-full" type="number" />
                            <x-alert name="budget" type="error" />
                        </div>
                        <div>
                            <x-label for="due_date" class="capitalize mb-1" value="due date" />
                            <x-input :value="old('due_date', Carbon\Carbon::parse($project->due_date)->format('Y-m-d'))"
                                name="due_date" id="due_date" class="block mt-1 w-full" type="date" />
                            <x-alert name="due_date" type="error" />
                        </div>
                        <div>
                            <x-label for="description" class="capitalize mb-1" value="description" />
                            <x-textarea name="description" id="description">
                                {{ old('description', $project->description) }}</x-textarea>
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
