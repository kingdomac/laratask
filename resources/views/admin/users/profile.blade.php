<x-app-layout>
    <div class="flex flex-wrap">
        <div class="w-full lg:w-8/12 px-4">
            <div
                class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
                <div class="rounded-t bg-white mb-0 px-6 py-6">
                    <div class="text-center flex justify-between">
                        <h6 class=" text-blueGray-700 text-xl font-bold">
                            {{ __('My account') }}
                            <span style="color:{{ $user->role->color }}"
                                class="text-sm capitalize">{{ $user->role->name }}</span>
                            <span style="color:{{ $user->role->color }}">{!! $user->role->icon !!}</span>

                        </h6>
                        @if (auth()->user()->isSuperAdmin)
                            <a href="{{ route('admin.setup') }}"
                                class="bg-pink-500 text-white active:bg-pink-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                                type="button">
                                {{ __('Settings') }}
                            </a>
                        @endif
                    </div>
                </div>
                <x-message type="success" />
                <div class="flex-auto px-4 lg:px-10 py-10 pt-0">
                    <form action="{{ route('admin.profile.update') }}" method="post">
                        @method('put')
                        @csrf
                        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                            {{ __('User Information') }}
                        </h6>
                        <div class="flex flex-wrap">
                            <div class="w-full lg:w-6/12 px-4">
                                <div class="relative w-full mb-3">
                                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        htmlFor="grid-password">
                                        {{ __('name') }}
                                    </label>
                                    <input name="name" type="text"
                                        class="border-0 px-3 py-3 capitalize placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        value="{{ $user->name }}" />
                                </div>
                                <x-alert name="name" />
                            </div>
                            <div class="w-full lg:w-6/12 px-4">
                                <div class="relative w-full mb-3">
                                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        htmlFor="grid-password">
                                        {{ __('Email address') }}
                                    </label>
                                    <input type="email" name="email"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        value="{{ $user->email }}" />
                                </div>
                                <x-alert name="email" />
                            </div>
                            <div class="w-full px-4">
                                <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                    htmlFor="grid-password">
                                    <i class="fa-solid fa-lock"></i> {{ __('job title') }}
                                    <x-alert name="job_title" />
                                </label>
                                <input type="text" name="job_title"
                                    class="border-0 px-3 py-3 uppercase placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                    value="{{ $user->job_title }}"
                                    @if (!auth()->user()->isSuperAdmin) disabled @endif />
                            </div>

                        </div>

                        <hr class="mt-6 border-b-1 border-blueGray-300" />

                        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                            {{ __('Contact Information') }}
                        </h6>
                        <div class="flex flex-wrap">
                            <div class="w-full lg:w-12/12 px-4">
                                <div class="relative w-full mb-3">
                                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        htmlFor="grid-password">
                                        {{ __('Address') }}
                                    </label>
                                    <input type="text" name="address"
                                        class="border-0 px-3 py-3 capitalize placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        value="{{ $user->address }}" />
                                </div>
                                <x-alert name="address" />
                            </div>
                            <div class="w-full px-4">
                                <div class="relative w-full mb-3">
                                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        htmlFor="grid-password">
                                        {{ __('phone') }}
                                    </label>
                                    <input type="text" name="phone"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        value="{{ $user->phone }}" />
                                </div>
                                <x-alert name="phone" />
                            </div>

                        </div>

                        <hr class="mt-6 border-b-1 border-blueGray-300" />

                        <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                            {{ __('Security') }}
                        </h6>
                        <div class="flex flex-wrap">
                            <div class="w-full lg:w-12/12 px-4">
                                <div class="relative w-full mb-3">
                                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        htmlFor="grid-password">
                                        <i class="fa-solid fa-lock"></i> {{ __('password') }}
                                        <x-alert name="password" />
                                    </label>
                                    <input type="password" name="password"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        value="" />
                                </div>

                                <div class="relative w-full mb-3">
                                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                        htmlFor="grid-password">
                                        <i class="fa-solid fa-arrow-up-long"></i> {{ __('confirm password') }}
                                    </label>
                                    <input type="password" name="password_confirmation"
                                        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                                        value="" />
                                </div>
                            </div>
                        </div>
                        <hr class="mt-6 border-b-1 border-blueGray-300" />
                        <!-- form footer -->
                        <div class="flex flex-wrap">
                            <div class="w-full lg:w-12/12 px-4">
                                <div
                                    class="flex justify-start items-start pt-2 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                                    <button wire:loading.class="opacity-10" wire:loading.attr="disabled"
                                        wire:target='store' data-modal-toggle="defaultModal" type="submit"
                                        class="text-white capitalize  bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        {{ __('save') }}</button>
                                    <button @click="showTaskForm=false;" wire:loading.class="opacity-10"
                                        wire:loading.attr="disabled" wire:click="hide" type="reset"
                                        class="text-gray-500 capitalize bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">{{ __('decline') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="w-full lg:w-4/12 px-4">
            <div
                class="relative flex flex-col min-w-0 break-words bg-white w-full md:h-screen mb-6 shadow-xl rounded-lg mt-16">
                <div class="px-6">
                    <div class="flex flex-wrap justify-center">
                        <div class="w-full px-4 flex justify-center">
                            <div class="relative">
                                <img alt="..." src="../../assets/img/team-2-800x800.jpg"
                                    class="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px" />
                            </div>
                        </div>
                        <div class="w-full px-4 text-center mt-20">
                            <div class="flex justify-center py-4 lg:pt-4 pt-8">
                                <div class="mr-4 p-3 text-center">
                                    <span class="text-xl font-bold block uppercase tracking-wide text-blueGray-600">
                                        {{ $projectsCount }}
                                    </span>
                                    <span class="capitalize text-sm text-blueGray-400">
                                        {{ \Str::of(__('project'))->plural($projectsCount) }}
                                    </span>
                                </div>
                                <div class="mr-4 p-3 text-center">
                                    <span class="text-xl font-bold block uppercase tracking-wide text-blueGray-600">
                                        {{ $user->issues_count }}
                                    </span>
                                    <span class="capitalize text-sm text-blueGray-400">
                                        {{ \Str::of(__('issue'))->plural($user->issues_count) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-12">
                        <h3 class="text-xl font-semibold leading-normal mb-2 text-blueGray-700">
                            {{ $user->name }}
                        </h3>
                        <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold">
                            <i class="fa-solid fa-envelope mr-2 text-lg text-blueGray-400"></i>
                            {{ $user->email }}
                        </div>
                        <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold">
                            <i class="fas fa-phone mr-2 text-lg text-blueGray-400"></i>
                            {{ $user->phone }}
                        </div>
                        <div class="text-sm leading-normal mt-0 mb-2 text-blueGray-400 font-bold capitalize">
                            <i class="fas fa-map-marker-alt mr-2 text-lg text-blueGray-400"></i>
                            {{ $user->address }}
                        </div>
                        <div class="mb-2 text-blueGray-600 mt-10 uppercase">
                            <i class="fas fa-briefcase mr-2 text-lg text-blueGray-400"></i>
                            {{ $user->job_title }}
                        </div>
                        {{-- <div class="mb-2 text-blueGray-600">
                            <i class="fas fa-university mr-2 text-lg text-blueGray-400"></i>
                            University of Computer Science
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
