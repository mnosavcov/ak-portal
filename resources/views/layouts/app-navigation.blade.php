<nav x-data="{ open: false }" class="bg-white border-b border-gray-100" :class="{'!border-transparent': open}">
    @php
        $projectCategories = (new \App\Services\AdminService())->getProjectCategory();

        $overviewTitle = __('profil.Přehled_účtu');
        $usersService = new \App\Services\UsersService();
        if ($usersService->isInvestorOnly()) {
            $overviewTitle = __('menu.Přehled_investora');
        } elseif ($usersService->isAdvertiserOnly()) {
            $overviewTitle = __('menu.Přehled_nabízejícího');
        } elseif ($usersService->isRealEstateBrokerOnly()) {
            $overviewTitle = __('menu.Přehled_realitního_makléře');
        }
    @endphp
            <!-- Primary Navigation Menu -->
    <div class="max-w-[1230px] mx-auto px-[15px] relative z-50 bg-white">
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
                    <x-dropdown align="left-185" width="">
                        <x-slot name="trigger">
                            <button
                                    class="{{ $classes }}">
                                <div>{{ __('menu.Projekty') }}</div>

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
                            <div
                                    class="px-[35px] pt-[15px] pb-[40px] grid gap-x-[24px] min-[1120px]:gap-x-[49px] min-[1200px]:gap-x-[69px]"
                                    style="grid-template-columns: repeat({{ count(\App\Models\Category::getCATEGORIES()) }}, max-content)"
                            >

                                @foreach(\App\Models\Category::getCATEGORIES() as $category)
                                    <div>
                                        <x-dropdown-content :href="route('projects.index.category', [
                                    'category' => $category['url'],
                                    ])">
                                            {{ __($category['title']) }}
                                        </x-dropdown-content>

                                        @foreach($projectCategories[$category['id']] as $nav)
                                            <x-dropdown-link :href="route('projects.index.category', [
                                                'category' => $category['url'],
                                                'subcategory' => $nav['url'],
                                            ])">
                                                {{ $nav['subcategory'] }}
                                            </x-dropdown-link>
                                        @endforeach
                                    </div>
                                @endforeach

                                <div class="mt-[40px] col-span-full text-center">
                                    <a href="{{ route('projects.index') }}"
                                       class="font-Spartan-Bold text-[15px] text-app-blue underline hover:no-underline">
                                        {{ __('menu.ZobrazitVšechnyKategorie') }}
                                    </a>
                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="hidden space-x-8 laptop:-my-px laptop:ml-10 laptop:flex">
                    <x-nav-link :href="route('jak-to-funguje')" :active="request()->routeIs('jak-to-funguje')">
                        {{ __('menu.Jak_to_funguje') }}
                    </x-nav-link>
                </div>

                {{--                <div class="hidden space-x-8 laptop:-my-px laptop:ml-10 laptop:flex">--}}
                {{--                    <x-nav-link :href="route('o-nas')" :active="request()->routeIs('o-nas')">--}}
                {{--                        {{ __('O nás') }}--}}
                {{--                    </x-nav-link>--}}
                {{--                </div>--}}

                <div class="hidden space-x-8 laptop:-my-px laptop:ml-10 laptop:flex">
                    <x-nav-link :href="route('kontakt')" :active="request()->routeIs('kontakt')">
                        {{ __('menu.Kontakt') }}
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
                    <x-dropdown align="right" width="56" :contentClasses="'py-[10px] bg-white'">
                        <x-slot name="trigger">
                            <button
                                    class="{{ $classes }}">
                                <div class="grid grid-cols-[30px_1fr] gap-x-[15px]">
                                    <div class="self-center">
                                        <img src="{{ Vite::asset('resources/images/ico-avatar.svg') }}">
                                    </div>
                                    <div class="self-center mt-[8px]">{{ __('menu.Váš_účet') }}</div>
                                </div>

                                <div class="ml-1 mt-[8px]">
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
                                <x-dropdown-link :href="route('admin.projects')" class="px-[30px]">
                                    {{ __('menu.Administrace') }}
                                </x-dropdown-link>
                            @endif

                            @if(!auth()->user()->superadmin && !auth()->user()->advisor)
                                @if(
                                    (auth()->user()->investor && !auth()->user()->isDeniedInvestor())
                                    || (auth()->user()->advertiser && !auth()->user()->isDeniedAdvertiser())
                                    || (auth()->user()->real_estate_broker && !auth()->user()->isDeniedRealEstateBrokerStatus())
                                )
                                    <x-dropdown-link :href="route('profile.overview')" class="px-[30px]"
                                                     :active="request()->routeIs('profile.overview')">
                                        {{ $overviewTitle }}
                                    </x-dropdown-link>
                                @endif

                                @if(auth()->user()->investor && !auth()->user()->isDeniedInvestor())
                                    <x-dropdown-link :href="route('profile.investor')" class="px-[30px]"
                                                     :active="request()->routeIs('profile.investor')">
                                        {{ __('menu.Profil_investora') }}
                                    </x-dropdown-link>
                                @endif

                                @if(auth()->user()->advertiser && !auth()->user()->isDeniedAdvertiser())
                                    <x-dropdown-link :href="route('profile.advertiser')" class="px-[30px]"
                                                     :active="request()->routeIs('profile.advertiser')">
                                        {{ __('menu.Profil_nabízejícího') }}
                                    </x-dropdown-link>
                                @endif

                                @if(auth()->user()->real_estate_broker && !auth()->user()->isDeniedRealEstateBrokerStatus())
                                    <x-dropdown-link :href="route('profile.real-estate-broker')" class="px-[30px]"
                                                     :active="request()->routeIs('profile.real-estate-broker')">
                                        {{ __('menu.Profil_realitního_makléře') }}
                                    </x-dropdown-link>
                                @endif
                            @endif

                            <x-dropdown-link :href="route('profile.edit')" class="px-[30px]"
                                             :active="request()->routeIs('profile.edit')">
                                {{ __('menu.Nastavení_účtu') }}
                            </x-dropdown-link>

                            @if(auth()->user()->superadmin || auth()->user()->investor || auth()->user()->advertiser || auth()->user()->real_estate_broker)
                                <div class="h-[1px] bg-[#D9E9F2] mx-[30px] mt-[10px]"></div>
                            @endif

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" class="px-[30px]"
                                                 onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    <div class="inline-grid grid-cols-[20px_1fr] gap-x-[15px] mt-[5px]">
                                        <img src="{{ Vite::asset('resources/images/ico-logout.svg') }}">
                                        <div class="!font-Spartan-SemiBold !text-[13px] underline">
                                            {{ __('menu.Odhlásit_Se') }}
                                        </div>
                                    </div>
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
                                    {{ __('menu.PřihlásitSe') }}
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

                    <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" width="20.999" height="21"
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden laptop:hidden">
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-gray-500/75 z-10"></div>
        <div class="absolute z-10 bg-[#F8F8F8] rounded-[0_0_10px_10px] left-0 right-0 border-t border-gray-100">
            @guest
                <x-responsive-nav-link :href="route('login')"
                                       class="py-[25px] px-[15px] !font-Spartan-Bold !text-[15px] underline">
                    {{ __('menu.PřihlásitSe') }}
                </x-responsive-nav-link>
            @endguest
            @auth
                <div class="px-[15px] grid grid-cols-[30px_1fr]">
                    <div class="self-center">
                        <img src="{{ Vite::asset('resources/images/ico-avatar.svg') }}">
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                               class="py-[25px] text-right"
                                               onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            <div class="inline-grid grid-cols-[20px_1fr] gap-x-[15px]">
                                <img src="{{ Vite::asset('resources/images/ico-logout.svg') }}">
                                <div class="!font-Spartan-Bold !text-[15px] underline">
                                    {{ __('menu.Odhlásit_Se') }}
                                </div>
                            </div>
                        </x-responsive-nav-link>
                    </form>
                </div>
            @endauth

            @auth
                @if(auth()->user()->superadmin || auth()->user()->investor || auth()->user()->advertiser || auth()->user()->real_estate_broker)
                    <div class="space-y-[25px] py-[25px] px-[15px] bg-white rounded-[3px] mx-[15px] mt-[5px] mb-[25px]">
                        @if(auth()->user()->superadmin)
                            <x-responsive-nav-link :href="route('admin.projects')">
                                {{ __('menu.Administrace') }}
                            </x-responsive-nav-link>
                        @endif

                        @if(!auth()->user()->superadmin)
                            @if(auth()->user()->investor || auth()->user()->advertiser || auth()->user()->real_estate_broker)
                                <x-responsive-nav-link :href="route('profile.overview')"
                                                       :active="request()->routeIs('profile.overview')">
                                    {{ $overviewTitle }}
                                </x-responsive-nav-link>
                            @endif

                            @if(auth()->user()->investor)
                                <x-responsive-nav-link :href="route('profile.investor')"
                                                       :active="request()->routeIs('profile.investor')">
                                    {{ __('menu.Profil_investora') }}
                                </x-responsive-nav-link>
                            @endif

                            @if(auth()->user()->advertiser)
                                <x-responsive-nav-link :href="route('profile.advertiser')"
                                                       :active="request()->routeIs('profile.advertiser')">
                                    {{ __('menu.Profil_nabízejícího') }}
                                </x-responsive-nav-link>
                            @endif

                            @if(auth()->user()->real_estate_broker)
                                <x-responsive-nav-link :href="route('profile.real-estate-broker')"
                                                       :active="request()->routeIs('profile.real-estate-broker')">
                                    {{ __('menu.Profil_realitního_makléře') }}
                                </x-responsive-nav-link>
                            @endif
                        @endif

                        <x-responsive-nav-link :href="route('profile.edit')"
                                               :active="request()->routeIs('profile.edit')">
                            {{ __('menu.Nastavení_účtu') }}
                        </x-responsive-nav-link>
                    </div>
                @else
                    <div class="h-[25px]"></div>
                @endif
            @endauth

            <div class="mx-[15px]">
                <div x-data="{open: false}">
                    <div class="rounded-[3px] transition px-[15px]" :class="{'bg-white' : open}">
                        <x-responsive-nav-link :active="request()->routeIs('projects.*')"
                                               @click="open = !open"
                                               class="!font-Spartan-SemiBold !text-[14px] h-[50px] leading-[50px] mr-[15px] cursor-pointer">
                            <div class="grid grid-cols-[1fr_10px] pr-[15px]">
                                {{ __('menu.Projekty') }}
                                <div class="self-center">
                                    <img src="{{ Vite::asset('resources/images/arrow-down-black-10x6.svg') }}"
                                         class="transition"
                                         :class="{'rotate-180': open}">
                                </div>
                            </div>
                        </x-responsive-nav-link>

                        <div x-show="open" x-collapse>
                            <div class="pt-[15px] pb-[20px] grid">
                                @foreach(\App\Models\Category::getCATEGORIES() as $category)
                                    <x-dropdown-content :href="route('projects.index.category', [
                                    'category' => $category['url'],
                                    ])" class="!text-[13px] !font-WorkSans-SemiBold !p-0 !pb-[25px]"
                                                        :active="
                                                        (request()->routeIs('projects.index') || request()->routeIs('projects.index.category'))
                                                        && request()->route()->parameter('category') === $category['url']
                                                        ">
                                        {{ __($category['title']) }}
                                    </x-dropdown-content>

                                    @foreach($projectCategories[$category['id']] as $nav)
                                        <x-responsive-nav-link :href="route('projects.index.category', [
                                                'category' => $category['url'],
                                                'subcategory' => $nav['url'],
                                            ])" class="pb-[20px]"
                                                               :active="
                                                        (request()->routeIs('projects.index') || request()->routeIs('projects.index.category'))
                                                        && request()->route()->parameter('category') === $category['url']
                                                        && request()->route()->parameter('subcategory') === $nav['url']
                                                        ">
                                            {{ $nav['subcategory'] }}
                                        </x-responsive-nav-link>
                                    @endforeach
                                @endforeach

                                <a href="{{ route('projects.index') }}"
                                   class="!font-Spartan-Bold !text-[15px] text-app-blue underline hover:no-underline mt-[20px]">
                                    {{ __('menu.ZobrazitVšechnyKategorie') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-[5px] border-b border-[#D9E9F2]"></div>
                <div>
                    <x-responsive-nav-link :href="route('jak-to-funguje')"
                                           :active="request()->routeIs('jak-to-funguje')"
                                           class="!font-Spartan-SemiBold !text-[14px] h-[50px] leading-[50px] pt-[5px] mx-[15px]">
                        {{ __('menu.Jak_to_funguje') }}
                    </x-responsive-nav-link>
                </div>
                {{--                <div class="pt-[5px] border-b border-[#D9E9F2]"></div>--}}
                {{--                <div>--}}
                {{--                    <x-responsive-nav-link :href="route('o-nas')" :active="request()->routeIs('o-nas')"--}}
                {{--                                           class="!font-Spartan-SemiBold !text-[14px] h-[50px] leading-[50px] pt-[5px] mx-[15px]">--}}
                {{--                        {{ __('O nás') }}--}}
                {{--                    </x-responsive-nav-link>--}}
                {{--                </div>--}}
                <div class="pt-[5px] border-b border-[#D9E9F2]"></div>
                <div class="pb-[25px]">
                    <x-responsive-nav-link :href="route('kontakt')" :active="request()->routeIs('kontakt')"
                                           class="!font-Spartan-SemiBold !text-[14px] h-[50px] leading-[50px] pt-[5px] mx-[15px]">
                        {{ __('menu.Kontakt') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>
