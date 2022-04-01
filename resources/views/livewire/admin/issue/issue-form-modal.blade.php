<div class="relative">
    @if ($show)
        <div id="task-modal"
            class="overflow-x-hidden fixed  bg-black bg-transparent bg-opacity-30 rounded-lg p-2 right-0 left-0 top-4 z-50 justify-center items-center h-full md:inset-0">
            <div class="relative w-full max-w-md ">
                <div @click.away="showTaskForm=false;selectedIssue=null"
                    class="relative bg-white rounded-lg right-0  shadow dark:bg-gray-700 " id="task-modal-form">
                    <div class="flex justify-between items-center p-2">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Create New Issue') }}</h3>
                        <button @click="showTaskForm=false;selectedIssue=null" wire:loading.class="opacity-10"
                            wire:loading.attr="disabled" wire:click="hide" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <x-message type="danger" class="bg-red-100" />
                    <form class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8"
                        @if (!$issueId) wire:submit.prevent="store()"
                        @else
                        wire:submit.prevent="update()" @endif
                        method="POST">
                        <div>
                            <x-label for="label_id" class="capitalize mb-1" :value="__('label')" />
                            <div class="flex">
                                <x-select wire:model="labelId" id="label_id">
                                    @foreach ($labels as $label)
                                        <option value="{{ $label->id }}">
                                            {{ $label->name }}
                                        </option>
                                    @endforeach
                                </x-select>

                            </div>

                        </div>

                        <!-- ----------------------------------------------------- -->
                        <div>
                            <x-label for="title" class="capitalize mb-1" :value="__('title')" />
                            <x-input wire:model.defer="title" id="title" class="block mt-1 w-full" type="text"
                                autofocus />
                            <x-alert name="title" type="error" />
                        </div>
                        <div>
                            <x-label for="description" class="capitalize mb-1" :value="__('summary')" />
                            <x-textarea wire:model.defer="summary" id="description"></x-textarea>

                        </div>
                        @if (auth()->user()->isSuperAdmin)
                            <div>
                                <x-label for="priority" class="capitalize mb-1" :value="__('priority')" />
                                <x-select wire:model="priorityId" id="priority">
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority->id }}" style="color:{{ $priority->color }}">
                                            {{ $priority->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-alert name="priorityId" type="error" />
                            </div>
                        @endif
                        @if ($labelId != App\Enums\LabelEnum::STORY->value)
                            <div>
                                <x-label for="status" class="capitalize mb-1" :value="__('status')" />
                                <x-select wire:model="statusId" id="status">
                                    <option value="">{{ __('New') }}</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->id }}" style="color:{{ $status->color }}">
                                            {{ $status->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-alert name="statusId" type="error" />
                            </div>
                        @endif

                        @if (!$parentId)
                            <div class="flex flex-wrap gap-2">
                                <div>
                                    <x-label for="story_point" class="capitalize mb-1" :value="__('story point')" />
                                    <x-input wire:model.defer="storyPoint" id="story_point" class="block mt-1 w-full"
                                        type="number" min="0" />
                                    <x-alert name="storyPoint" type="error" />
                                </div>
                                <div>
                                    <x-label for="value_point" class="capitalize mb-1" :value="__('value point')" />
                                    <x-input wire:model.defer="valuePoint" id="value_point" class="block mt-1 w-full"
                                        type="number" min="0" />
                                    <x-alert name="valuePoint" type="error" />
                                </div>
                            </div>
                        @endif
                        @if ($issue?->user_id && $issueId && (auth()->user()->id === $userId || auth()->user()->isSuperAdmin))
                            <div class="flex flex-wrap gap-2">
                                <div>
                                    <x-label for="time_spent" class="capitalize mb-1"
                                        :value="__('time spent /minute')" />
                                    <x-input wire:model.defer="timeSpent" id="time_spent" class="block mt-1 w-full"
                                        type="number" min="0" />
                                    <x-alert name="timeSpent" type="error" />
                                </div>
                                <div>
                                    <x-label for="completion_perc" class="capitalize mb-1"
                                        :value="__('completion %')" />
                                    <x-input wire:model.defer="completionPerc" id="completion_perc"
                                        class="block mt-1 w-full" type="number" min="0" />
                                    <x-alert name="completionPerc" type="error" />
                                </div>
                            </div>
                        @endif
                        @if (auth()->user()->isSuperAdmin)
                            <div>
                                <x-label for="users" class="capitalize mb-1" :value="__('assigned to')" />
                                <x-select wire:model="userId" id="users">
                                    <option value="">-- select one --</option>
                                    <option value="{{ auth()->user()->id }}">{{ __('me') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-alert name="userId" type="error" />
                            </div>
                        @endif
                        <!-- Modal footer -->
                        <div
                            class="flex justify-start items-start pt-2 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                            <button wire:loading.class="opacity-10" wire:loading.attr="disabled" wire:target='store'
                                data-modal-toggle="defaultModal" type="submit"
                                class="text-white capitalize bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                {{ __('save') }}</button>
                            <button @click="showTaskForm=false;selectedIssue=null" wire:loading.class="opacity-10"
                                wire:loading.attr="disabled" wire:click="hide" type="button"
                                class="text-gray-500 capitalize bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                                {{ __('decline') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
