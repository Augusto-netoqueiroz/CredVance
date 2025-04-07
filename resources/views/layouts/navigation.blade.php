<!-- navigation.blade.php completo com seletor dentro do dropdown -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                    {{ __('Usuários') }}
                </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <button id="theme-toggle" class="w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150 ease-in-out flex items-center">
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v2a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.22a1 1 0 011.415 1.415l-1.42 1.42a1 1 0 11-1.415-1.415l1.42-1.42zM10 6a4 4 0 110 8 4 4 0 010-8zm8 4a1 1 0 01-1 1h-2a1 1 0 110-2h2a1 1 0 011 1zm-4.22 5.78a1 1 0 00-1.415-1.415l-1.42 1.42a1 1 0 101.415 1.415l1.42-1.42zM10 14a1 1 0 011 1v2a1 1 0 11-2 0v-2a1 1 0 011-1zm-4.22-1.78a1 1 0 00-1.415 1.415l1.42 1.42a1 1 0 001.415-1.415l-1.42-1.42zM4 10a1 1 0 01-1 1H1a1 1 0 110-2h2a1 1 0 011 1zm1.78-4.22l1.42 1.42a1 1 0 01-1.415 1.415L4.365 7.195a1 1 0 111.415-1.415z"/>
                            </svg>
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293a1 1 0 011.414 1.414l-2 2a1 1 0 01-1.414-1.414l2-2zM10 15a5 5 0 110-10 5 5 0 010 10zm7-5a1 1 0 100 2h2a1 1 0 100-2h-2zM2 10a1 1 0 011-1h2a1 1 0 110 2H3a1 1 0 01-1-1zm3.293-6.293l2 2a1 1 0 01-1.414 1.414l-2-2a1 1 0 011.414-1.414zm9.414 0a1 1 0 010 1.414l-2 2a1 1 0 01-1.414-1.414l2-2a1 1 0 011.414 0zM10 2a1 1 0 011 1v2a1 1 0 11-2 0V3a1 1 0 011-1zM5.293 17.293a1 1 0 001.414-1.414l-2-2a1 1 0 00-1.414 1.414l2 2z"/>
                            </svg>
                            Alternar Tema
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
