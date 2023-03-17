<x-app-layout>
    <div class="flex flex-wrap mt-4">
        <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3 class="font-semibold text-sm text-blueGray-700 uppercase">
                                <i class="fa-solid fa-user"></i>
                                @if ($user->id)
                                    {{ __('edit user') }}
                                @else
                                    {{ __('new user') }}
                                @endif

                            </h3>
                        </div>
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                    <x-message type="danger" />
                    <form
                        @if ($user->id) action="{{ route('admin.users.update', $user->id) }}"
                        @else
                        action="{{ route('admin.users.store') }}" @endif
                        class="px-6 pb-4 space-y-6 lg:px-8 sm:pb-6 xl:pb-8" method="POST">
                        @if ($user->id)
                            @method('put')
                        @endif
                        @csrf
                        <div>
                            <x-label for="role_id" class="capitalize mb-1" value="{{ __('role') }}" />
                            <x-select name="role_id" id="role_id">
                                <option value="" @selected('role_id')>-- {{ __('select role') }} --
                                </option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                                        {{ $role->name }}</option>
                                @endforeach
                            </x-select>
                            <x-alert name="role_id" type="error" />
                        </div>
                        <div>
                            <x-label for="job_title" class="capitalize mb-1" value="job title" />
                            <x-input-icon :value="old('job_title', $user->job_title)" icon='<i class="fas fa-briefcase"></i>' name="job_title"
                                id="job_title" class="block mt-1 w-full" type="text" />
                            <x-alert name="job_title" type="error" />
                        </div>
                        <div>
                            <x-label for="name" class="capitalize mb-1" value="name" />
                            <x-input :value="old('name', $user->name)" name="name" id="name" class="block mt-1 w-full"
                                type="text" autofocus />
                            <x-alert name="name" type="error" />
                        </div>
                        <div>
                            <x-label for="email" class="capitalize mb-1" value="email address" />
                            <x-input-icon :value="old('email', $user->email)" icon="<i class='fa-solid fa-envelope'></i>" name="email"
                                id="email" class="block mt-1 pl-8 w-full" type="text" />
                            <x-alert name="email" type="error" />
                        </div>
                        <div>
                            <x-label for="phone" class="capitalize mb-1" value="phone" />
                            <x-input-icon :value="old('phone', $user->phone)" icon='<i class="fa-solid fa-phone"></i>' name="phone"
                                id="phone" class="block mt-1 w-full" type="text" />
                            <x-alert name="phone" type="error" />
                        </div>

                        <div>
                            <x-label for="address" class="capitalize mb-1" value="address" />
                            <x-input-icon :value="old('address', $user->address)" icon='<i class="fa-solid fa-location-pin"></i>'
                                name="address" id="address" class="block mt-1 w-full" type="text" />
                            <x-alert name="address" type="error" />
                        </div>

                        <hr class="mt-6 border-b-1 border-blueGray-300" />
                        <div>
                            <x-label for="password" class="capitalize mb-1" value="password" />
                            <x-input-icon icon='<i class="fa-solid fa-lock"></i>' name="password" id="password"
                                class="block mt-1 w-full" type="password" />
                            <x-alert name="password" type="error" />
                            <div class="mt-2">
                                <div class="flex gap-1">
                                    <i class="fa-solid fa-arrow-up"></i>
                                    <x-label for="password_confirmation" class="capitalize mb-1"
                                        value="confirm password" />
                                </div>
                                <x-input name="password_confirmation" id="password_confirmation"
                                    class="block mt-1 w-full" type="password" />
                            </div>
                        </div>


                        <!-- Modal footer -->
                        <div
                            class="flex justify-start items-start pt-2 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                            <button type="submit"
                                class="text-white  bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                save</button>
                            <a href="{{ route('admin.users.index') }}"
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
