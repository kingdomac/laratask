<x-app-layout>
    <div x-data="data" class="flex flex-wrap mt-4">
        <!----- DELETE MODAL ------>
        <x-delete-modal />
        <!------ END MODAL ----->
        <div class="w-full mb-12 px-4">
            <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-white">
                <div class="rounded-t mb-0 px-4 py-3 border-0">
                    <div class="flex flex-wrap items-center w-full justify-between">
                        <div class="relative px-4">
                            <h3 class="font-semibold text-lg text-blueGray-700 uppercase">
                                <i class="fas fa-users mr-2 "></i>users
                            </h3>
                        </div>
                        <div class="flex items-center gap-5 mr-5 flex-auto justify-end">
                            <form action="{{ route('admin.users.index') }}" method="get">
                                <div class="flex  gap-2 items-center">
                                    <x-select class="grow-0 w-1/2" name="role_id" onchange="this.form.submit()">
                                        <option value="">-- {{ __('all roles') }} --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                    <div class="relative grow ">
                                        <x-input-icon name="keyword" value="{{ request('keyword') }}"
                                            icon="<i class='fa-solid fa-magnifying-glass'></i>" autofocus />
                                    </div>

                                </div>
                            </form>
                            @if (auth()->user()->isSuperAdmin)
                                <a href="{{ route('admin.users.create') }}" title="new project"
                                    class="cursor-pointer">
                                    <i class="fa-solid  fa-square-plus text-lg cursor-pointer"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2">
                        <x-message type="success" />
                    </div>
                </div>
                <div class="block w-full overflow-x-auto">
                    <!-- Users table -->
                    <table class="items-center w-full bg-transparent border-collapse">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                    {{ __('name') }}
                                </th>
                                <th
                                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                    {{ __('email') }}
                                </th>
                                <th
                                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                    {{ __('phone') }}
                                </th>

                                <th
                                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                    {{ __('role') }}
                                </th>
                                <th
                                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                    {{ __('last seen') }}
                                </th>
                                <th
                                    class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left bg-blueGray-50 text-blueGray-500 border-blueGray-100">
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr class=" hover:bg-gray-200">
                                    <th
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left flex items-center capitalize">
                                        <a href="#" class="relative ml-3 font-bold text-blueGray-600">
                                            <img src="../../assets/img/team-1-800x800.jpg" alt="..."
                                                class="w-10 h-10 inline-block rounded-full border-2 border-blueGray-50 shadow" />
                                            <div class="absolute left-0 top-0">
                                                <x-users.status :online="$user->isOnline" />
                                            </div>
                                            {{ $user->name }}
                                        </a>
                                    </th>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <i class="fa-solid fa-envelope"></i>
                                        {{ $user->email }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <i class="fa-solid fa-mobile-screen-button"></i>
                                        {{ $user->phone }}
                                    </td>

                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs uppercase whitespace-nowrap p-4"
                                        style="color:{{ $user->role->color }}">
                                        {!! $user->role->icon !!} {{ $user->role->name }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                                        <i class="fa-solid fa-mobile-screen-button"></i>
                                        {{ $user->last_seen }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                                        <div class="flex flex-wrap gap-3">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"><i
                                                    class="fa-solid fa-square-pen text-sm"></i></a>
                                            <a @click.prevent='showDeleteModal("{{ route('admin.users.destroy', $user->id) }}")'
                                                href="#"><i class="fa-solid fa-trash text-sm text-red-600"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if (!count($users))
                    <div class="bg-gray-50 rounded-lg py-5 px-6 mb-3 text-base text-gray-500 inline-flex items-center w-full"
                        role="alert">
                        <i class="fa-solid fa-face-frown mr-2"></i>
                        {{ __('No users!') }}
                    </div>
                @endif
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
