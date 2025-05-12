<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Lado Esquerdo (Logo e Links) -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('Inicio') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                    </a>
                </div>

                <!-- Menus Esquerda (versão desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Link comum a todos os perfis -->
                    <x-nav-link :href="route('Inicio')" :active="request()->routeIs('Inicio')">
                        {{ __('Início') }}
                    </x-nav-link>

                    <!-- Só mostra se for admin -->
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                            {{ __('Usuários') }}
                        </x-nav-link>
                         <x-nav-link :href="route('activity_logs.index')" :active="request()->routeIs('activity_logs.index')">
                            {{ __('Logs') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Lado Direito (Perfil e Dropdown) - versão desktop -->
            <div class="hidden sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium 
                                   rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 
                                   transition ease-in-out duration-150"
                        >
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 
                                          111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 
                                          010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>
                        
                        <!-- Alternar Tema (opcional) -->
                        <button id="theme-toggle"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 
                                       dark:hover:bg-gray-600 transition duration-150 ease-in-out flex items-center"
                        >
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 mr-2" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path d="..."/>
                            </svg>
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 mr-2" fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path d="..."/>
                            </svg>
                            {{ __('Alternar Tema') }}
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Sair') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Ícone do menu (hamburger) - versão mobile -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 
                               hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 
                               focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500
                               transition duration-150 ease-in-out"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" 
                              class="inline-flex" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" 
                              class="hidden" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" 
                              stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu suspenso (versão mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden">
        <!-- Links da esquerda em mobile -->
        <div class="pt-2 pb-3 space-y-1">
            <!-- Link comum a todos -->
            <x-responsive-nav-link :href="route('Inicio')" :active="request()->routeIs('Inicio')">
                {{ __('Início') }}
            </x-responsive-nav-link>

            <!-- Só mostra se for admin -->
            @if (auth()->check() && auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                    {{ __('Usuários') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Opções de perfil (versão mobile) -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>
                
                <!-- Alternar Tema (opcional) -->
                <button id="theme-toggle-mobile"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 
                               dark:hover:bg-gray-600 transition duration-150 ease-in-out flex items-center"
                >
                    <!-- coloque aqui os ícones do tema, se desejar -->
                    {{ __('Alternar Tema') }}
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
