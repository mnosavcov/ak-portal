<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[1230px] mx-auto px-[15px]">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('homepage') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                @php
                    $active=request()->routeIs('projects.*');
                        $classeW = ($active ?? false)
                                    ? 'hidden laptop:flex laptop:items-center laptop:ml-6 relative top-[1px] border-b-2 border-app-blue'
                                    : 'hidden laptop:flex laptop:items-center laptop:ml-6 relative top-[1px] border-b-2 border-transparent hover:border-gray-300 transition duration-150 ease-in-out';

                        $classes = ($active ?? false)
                                    ? '!text-[15px] inline-flex items-center px-1 text-sm font-medium leading-5 text-[#31363A] transition duration-150 ease-in-out font-Spartan-SemiBold'
                                    : '!text-[15px] inline-flex items-center px-1 text-sm font-medium leading-5 text-[#31363A]/80 hover:text-[#31363A] transition duration-150 ease-in-out font-Spartan-SemiBold';
                @endphp
                <div class="{{ $classeW }}">
                    <x-dropdown align="left-170" width="">
                        <x-slot name="trigger">
                            <button
                                    class="{{ $classes }}">
                                <div>Projekty</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-[35px] pt-[15px] pb-[40px] grid grid-cols-[max-content_max-content_max-content] gap-x-[69px]">
                                <div>
                                    <x-dropdown-content>
                                        {{ __('Cenu navrhuje kupující') }}
                                    </x-dropdown-content>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Pozemky k pronájmu') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Pozemky na prodej') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Kapacita v síti distributora') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Postoupení práv k projektu') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')" class="mb-[5px]">
                                        {{ __('Výstavba FVE na klíč') }}
                                    </x-dropdown-link>
                                </div>
                                <div class="relative">
                                    <div class="absolute h-full w-[1px] top-0 bg-[#D9E9F2] left-[-35px]"></div>
                                    <x-dropdown-content>
                                        {{ __('Cenu navrhuje prodávající') }}
                                    </x-dropdown-content>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Pozemky k pronájmu') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Pozemky na prodej') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Kapacita v síti distributora') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Postoupení práv k projektu') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')" class="mb-[5px]">
                                        {{ __('Výstavba FVE na klíč') }}
                                    </x-dropdown-link>
                                </div>
                                <div class="relative">
                                    <div class="absolute h-full w-[1px] top-0 bg-[#D9E9F2] left-[-35px]"></div>
                                    <x-dropdown-content>
                                        {{ __('Aukce') }}
                                    </x-dropdown-content>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Pozemky k pronájmu') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Pozemky na prodej') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Kapacita v síti distributora') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Postoupení práv k projektu') }}
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('profile.edit')" class="mb-[5px]">
                                        {{ __('Výstavba FVE na klíč') }}
                                    </x-dropdown-link>
                                </div>
                                <div class="mt-[20px] col-span-3 text-center">
                                    <a href="{{ route('projects.index') }}" class="font-Spartan-Bold text-[15px] text-app-blue underline hover:no-underline">Zobrazit všechny kategorie</a>
                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="hidden space-x-8 laptop:-my-px laptop:ml-10 laptop:flex">
                    <x-nav-link :href="route('jak-to-funguje')" :active="request()->routeIs('jak-to-funguje')">
                        {{ __('Jak to funguje') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 laptop:-my-px laptop:ml-10 laptop:flex">
                    <x-nav-link :href="route('o-nas')" :active="request()->routeIs('o-nas')">
                        {{ __('O nás') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 laptop:-my-px laptop:ml-10 laptop:flex">
                    <x-nav-link :href="route('kontakt')" :active="request()->routeIs('kontakt')">
                        {{ __('Kontakt') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
                @php
                    $active=request()->routeIs('projects.*');
                        $classeW = ($active ?? false)
                                    ? 'hidden laptop:flex laptop:items-center laptop:ml-6 relative top-[1px] border-b-2 border-app-blue'
                                    : 'hidden laptop:flex laptop:items-center laptop:ml-6 relative top-[1px] border-b-2 border-transparent hover:border-gray-300 transition duration-150 ease-in-out';

                        $classes = ($active ?? false)
                                    ? '!text-[15px] inline-flex items-center px-1 text-sm font-medium leading-5 text-[#31363A] transition duration-150 ease-in-out font-Spartan-SemiBold'
                                    : '!text-[15px] inline-flex items-center px-1 text-sm font-medium leading-5 text-[#31363A]/80 hover:text-[#31363A] transition duration-150 ease-in-out font-Spartan-SemiBold';
                @endphp
                <div class="{{ $classeW }}">
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button
                                    class="{{ $classes }}">
                                <div>Váš účet</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(auth()->user()->superadmin)
                                <x-dropdown-link :href="route('admin.projects')">
                                    {{ __('Administrace') }}
                                </x-dropdown-link>
                            @endif

                            @if(auth()->user()->investor || auth()->user()->advertiser || auth()->user()->real_estate_broker)
                                <x-dropdown-link :href="route('profile.overview')">
                                    {{ __('Přehled účtu') }}
                                </x-dropdown-link>
                            @endif

                            @if(auth()->user()->investor)
                                <x-dropdown-link :href="route('profile.investor')">
                                    {{ __('Profil investora') }}
                                </x-dropdown-link>
                            @endif

                            @if(auth()->user()->advertiser)
                                <x-dropdown-link :href="route('profile.advertiser')">
                                    {{ __('Profil nabízejícího') }}
                                </x-dropdown-link>
                            @endif

                            @if(auth()->user()->real_estate_broker)
                                <x-dropdown-link :href="route('profile.real-estate-broker')">
                                    {{ __('Profil realitního makléře') }}
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Nastavení účtu') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Odhlásit se') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            @guest
                @php
                    $active=request()->routeIs('login');
                        $classeW = ($active ?? false)
                                    ? 'hidden laptop:flex laptop:items-center laptop:ml-6 relative top-[1px] border-b-2 border-app-blue'
                                    : 'hidden laptop:flex laptop:items-center laptop:ml-6 relative top-[1px] border-b-2 border-transparent hover:border-gray-300 transition duration-150 ease-in-out';

                        $classes = ($active ?? false)
                                    ? '!text-[15px] inline-flex items-center text-sm font-medium leading-5 text-[#31363A] transition duration-150 ease-in-out font-Spartan-SemiBold'
                                    : '!text-[15px] inline-flex items-center text-sm font-medium leading-5 text-[#31363A]/80 hover:text-[#31363A] hover:border-gray-300 transition duration-150 ease-in-out font-Spartan-SemiBold';
                @endphp
                <div class="{{ $classeW }}">
                    <div class="relative">
                        @if (Route::has('login'))
                            <div class="laptop:top-0 laptop:right-0 pl-0 p-6 pr-0 text-right z-10">
                                <a href="{{ route('login') }}"
                                   class="{{ $classes }}">
                                    Přihlásit se
                                </a>

                                {{--                                @if (Route::has('register'))--}}
                                {{--                                    <a href="{{ route('register') }}"--}}
                                {{--                                       class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>--}}
                                {{--                                @endif--}}
                            </div>
                        @endif
                    </div>
                </div>
            @endguest

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center laptop:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg x-show="!open" id="Component_7" data-name="Component 7" xmlns="http://www.w3.org/2000/svg"
                         width="30" height="21" viewBox="0 0 30 21">
                        <rect id="Rectangle_1396" data-name="Rectangle 1396" width="30" height="3" rx="1"
                              fill="#414141"/>
                        <rect id="Rectangle_1397" data-name="Rectangle 1397" width="30" height="3" rx="1"
                              transform="translate(0 9)" fill="#414141"/>
                        <rect id="Rectangle_1398" data-name="Rectangle 1398" width="16" height="3" rx="1"
                              transform="translate(14 18)" fill="#414141"/>
                    </svg>

                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" width="20.999" height="21"
                         viewBox="0 0 20.999 21">
                        <g id="Group_20638" data-name="Group 20638" transform="translate(-442.333 -32.832)">
                            <rect id="Rectangle_1397" data-name="Rectangle 1397" width="26.997" height="2.7" rx="1"
                                  transform="translate(444.242 32.832) rotate(45)" fill="#414141"/>
                            <rect id="Rectangle_1776" data-name="Rectangle 1776" width="26.997" height="2.7" rx="1"
                                  transform="translate(442.333 51.923) rotate(-45)" fill="#414141"/>
                        </g>
                    </svg>

                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    @auth
        <div :class="{'block': open, 'hidden': ! open}" class="hidden laptop:hidden">
            <div class="pt-2 space-y-1">
                <x-responsive-nav-link :href="route('homepage')" :active="request()->routeIs('homepage')">
                    {{ __('Hlavní stránka') }}
                </x-responsive-nav-link>
            </div>
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.index')">
                    {{ __('Projekty') }}
                </x-responsive-nav-link>
            </div>
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('jak-to-funguje')" :active="request()->routeIs('jak-to-funguje')">
                    {{ __('Jak to funguje') }}
                </x-responsive-nav-link>
            </div>
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('o-nas')" :active="request()->routeIs('o-nas')">
                    {{ __('O nás') }}
                </x-responsive-nav-link>
            </div>
            <div class="pb-3 space-y-1">
                <x-responsive-nav-link :href="route('kontakt')" :active="request()->routeIs('kontakt')">
                    {{ __('Kontakt') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">Váš účet</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    @if(auth()->user()->superadmin)
                        <x-responsive-nav-link :href="route('admin.projects')">
                            {{ __('Administrace') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(auth()->user()->investor || auth()->user()->advertiser || auth()->user()->real_estate_broker)
                        <x-responsive-nav-link :href="route('profile.overview')">
                            {{ __('Přehled účtu') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(auth()->user()->investor)
                        <x-responsive-nav-link :href="route('profile.investor')">
                            {{ __('Profil investora') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(auth()->user()->advertiser)
                        <x-responsive-nav-link :href="route('profile.advertiser')">
                            {{ __('Profil nabízejícího') }}
                        </x-responsive-nav-link>
                    @endif

                    @if(auth()->user()->real_estate_broker)
                        <x-responsive-nav-link :href="route('profile.real-estate-broker')">
                            {{ __('Profil realitního makléře') }}
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Nastavení účtu') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Odhlásit se') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    @guest
        <div :class="{'block': open, 'hidden': ! open}" class="hidden laptop:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('homepage')" :active="request()->routeIs('homepage')">
                    {{ __('Hlavní stránka') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="space-y-1">
                    <div class="space-y-1">
                        <x-responsive-nav-link :href="route('projects.index')"
                                               :active="request()->routeIs('projects.index')">
                            {{ __('Projekty') }}
                        </x-responsive-nav-link>
                    </div>
                    <div class="space-y-1">
                        <x-responsive-nav-link :href="route('jak-to-funguje')"
                                               :active="request()->routeIs('jak-to-funguje')">
                            {{ __('Jak to funguje') }}
                        </x-responsive-nav-link>
                    </div>
                    <div class="space-y-1">
                        <x-responsive-nav-link :href="route('o-nas')" :active="request()->routeIs('o-nas')">
                            {{ __('O nás') }}
                        </x-responsive-nav-link>
                    </div>
                    <div class="pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('kontakt')" :active="request()->routeIs('kontakt')">
                            {{ __('Kontakt') }}
                        </x-responsive-nav-link>
                    </div>
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Přihlásit se') }}
                    </x-responsive-nav-link>

                    {{--                    @if (Route::has('register'))--}}
                    {{--                        <x-responsive-nav-link :href="route('register')">--}}
                    {{--                            {{ __('Register') }}--}}
                    {{--                        </x-responsive-nav-link>--}}
                    {{--                    @endif--}}
                </div>
            </div>
        </div>
    @endguest
</nav>
