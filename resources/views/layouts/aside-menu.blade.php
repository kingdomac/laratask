<nav x-data="{ maxim: false }" x-cloak x-transition :class="maxim ? 'md:w-64' : 'md:w-20'"
    class="transition-all ease-in-out md:left-0 md:block md:fixed md:top-0 md:bottom-0
    md:overflow-y-auto md:flex-row md:flex-nowrap
    md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative
     z-10 py-4 px-6">
    <div x-show="maxim" x-cloak class="absolute z-10 right-1 top-5 hidden md:block">
        <i @click="maxim=false" class="fa-solid fa-minimize cursor-pointer" title="minimize"></i>
    </div>
    <div x-show="!maxim" x-cloak class="absolute z-10 right-1 top-5 hidden md:block">
        <i @click="maxim=true" class="fa-solid fa-maximize cursor-pointer" title="maximize"></i>
    </div>
    <div
        class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto">
        <button
            class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
            type="button" onclick="toggleNavbar('example-collapse-sidebar')">
            <i class="fas fa-bars"></i>
        </button>
        <div class="md:hidden">@livewire('admin.notification-menu')</div>
        <a x-show="maxim"
            class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
            href="{{ route('admin.dashboard') }}">
            LARATASK
        </a>
        {{-- <ul class="md:hidden items-center flex flex-wrap list-none">
            <li class="inline-block relative">
                <a class="text-blueGray-500 block py-1 px-3" href="#pablo"
                    onclick="openDropdown(event,'notification-dropdown')"><i class="fas fa-bell"></i></a>
                <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
                    id="notification-dropdown">
                    <a href="#pablo"
                        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700">

                    </a>
                    <div class="h-0 my-2 border border-solid border-blueGray-100"></div>
                    <a x-show="maxim" href="#pablo"
                        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700">Seprated
                        link</a>
                </div>
            </li>
            <li class="inline-block relative">
                <a class="text-blueGray-500 block" href="#pablo"
                    onclick="openDropdown(event,'user-responsive-dropdown')">
                    <div class="items-center flex">
                        <span
                            class="w-12 h-12 text-sm text-white bg-blueGray-200 inline-flex items-center justify-center rounded-full"><img
                                alt="..." class="w-full rounded-full align-middle border-none shadow-lg"
                                src="{{ asset('assets/img/team-1-800x800.jpg') }}" /></span>
                    </div>
                </a>
                <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
                    id="user-responsive-dropdown">
                    <a href="#pablo"
                        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700">Action</a>
                    <div class="h-0 my-2 border border-solid border-blueGray-100"></div>
                    <a href="#pablo"
                        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700">Seprated
                        link</a>
                </div>
            </li>
        </ul> --}}
        <div class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded hidden"
            id="example-collapse-sidebar">
            <div class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-blueGray-200">
                <div class="flex flex-wrap">
                    <div class="w-6/12">
                        <a class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                            href="">
                            LARATASK
                        </a>
                    </div>
                    <div class="w-6/12 flex justify-end">
                        <button type="button"
                            class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                            onclick="toggleNavbar('example-collapse-sidebar')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            {{-- <form class="mt-6 mb-4 md:hidden">
                <div class="mb-3 pt-0">
                    <input type="text" placeholder="Search"
                        class=" px-3 py-2 h-12 border border-solid border-blueGray-500 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-base leading-snug shadow-none outline-none focus:outline-none w-full font-normal" />
                </div>
            </form>
            <!-- Divider -->
            <hr x-show="maxim" class="my-4 md:min-w-full" /> --}}
            <h6
                class="md:min-w-full text-gray-500 text-xs uppercase font-bold block md:hidden text-center no-underline bg-yellow-50 p-2 mb-3">
                {{ auth()->user()->name }}
            </h6>
            <!-- Heading -->
            <h6 x-show="maxim"
                class="md:min-w-full text-blueGray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline">
                Admin Layout Pages
            </h6>
            <!-- Navigation -->

            <ul class="md:flex-col md:min-w-full flex flex-col list-none">

                <li class="items-center">
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-xs uppercase py-3 font-bold block {{ request()->routeIs('admin.dashboard') ? 'text-green-500' : 'text-blueGray-700' }} ">
                        <i
                            class="fas fa-tv mr-2 text-sm opacity-75 {{ request()->routeIs('admin.dashboard') ? 'text-green-500' : 'text-blueGray-700' }}"></i>
                        <span x-show="maxim">{{ __('Dashboard') }}</span>
                        <span class="md:hidden">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @if (auth()->user()->isSuperAdmin)
                    <li class="items-center">
                        <a href="{{ route('admin.users.index') }}"
                            class="text-xs uppercase py-3 font-bold block {{ request()->routeIs('admin.users.index') ? 'text-green-500' : 'text-blueGray-700' }}">
                            <i
                                class="fas fa-users mr-2 text-sm {{ request()->routeIs('admin.users.index') ? 'text-green-500' : 'text-blueGray-700' }}"></i>
                            <span x-show="maxim">{{ __('users') }}</span>
                            <span class="md:hidden">{{ __('users') }}</span>
                        </a>
                    </li>
                @endif
                <li class="items-center">
                    <a href="{{ route('admin.projects.index') }}"
                        class="relative text-xs uppercase py-3 font-bold block {{ request()->routeIs('admin.projects.*') ? 'text-green-500' : 'text-blueGray-700' }}">
                        <i
                            class="fas fa-diagram-project mr-2 text-sm {{ request()->routeIs('admin.projects.*') ? 'text-green-500' : 'text-blueGray-700' }}"></i>
                        @if ($countNewIssues)
                            <div
                                class="absolute z-10 top-0 -left-0 text-[10px] rounded-full w-auto h-auto px-1 text-center text-gray-50 bg-red-500">
                                {{ $countNewIssues }}
                            </div>
                        @endif

                        <span x-show="maxim">{{ __('projects') }}</span>
                        <span class="md:hidden">{{ __('projects') }}</span>
                    </a>
                </li>
            </ul>

            <!-- Divider -->
            <hr class="my-4 md:min-w-full" />
            <!-- Heading -->
            <h6 x-show="maxim"
                class="md:min-w-full text-blueGray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline">
                <span x-show="maxim">{{ __('settings') }}</span>
                <span class="md:hidden">{{ __('settings') }}</span>
            </h6>
            <!-- Navigation -->

            <ul class="md:flex-col md:min-w-full flex flex-col list-none md:mb-4">
                <li class="items-center">
                    <a href="{{ route('admin.profile.edit') }}"
                        class="text-xs uppercase py-3 font-bold block  {{ request()->routeIs('admin.profile.edit') ? 'text-green-500' : 'text-blueGray-700' }}">
                        <i
                            class="fas fa-user-circle mr-2 text-sm  {{ request()->routeIs('admin.profile.edit') ? 'text-green-500' : 'text-blueGray-700' }}"></i>
                        <span x-show="maxim">{{ __('profile') }}</span>
                        <span class="md:hidden">{{ __('profile') }}</span>
                    </a>
                </li>
                @if (auth()->user()->isSuperAdmin)
                    <li class="items-center">
                        <a href="{{ route('admin.setup') }}"
                            class="text-xs uppercase py-3 font-bold block {{ request()->routeIs('admin.setup') ? 'text-green-500' : 'text-blueGray-700' }}">
                            <i
                                class="fas fa-tools mr-2 text-sm {{ request()->routeIs('admin.setup') ? 'text-green-500' : 'text-blueGray-700' }}"></i>
                            <span x-show="maxim">{{ __('setup') }}</span>
                            <span class="md:hidden">{{ __('setup') }}</span>
                        </a>
                    </li>
                @endif
                <li class="items-left md:hidden">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="bg-blue-500 text-gray-200 rounded-lg w-full p-1 text-sm hover:bg-blue-600 hover:shadow-sm hover:-translate-y-1 ">{{ __('Log Out') }}</button>
                    </form>
                </li>
            </ul>

            <!-- Navigation -->

            <!-- Divider -->
            <hr class="my-4 md:min-w-full" />

        </div>
    </div>
</nav>
