<nav
    class="absolute md:relative w-64 transform -translate-x-full md:translate-x-0 h-screen overflow-y-scroll bg-black transition-all duration-300"
    :class="{'-translate-x-full': !navOpen}">
    <div class="flex flex-col justify-between h-full">
        <div class="p-4">
            <!-- LOGO -->
            <a href="/">
                <img src="{{ Vite::asset('resources/images/pvtrusted-invert.svg') }}" width="185" height="39">
            </a>

            <!-- SEARCH BAR -->
            <div class="border-gray-700 py-5 text-white border-b rounded">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <form action="" method="GET">
                        <input type="search"
                               class="w-full py-2 rounded pl-10 bg-gray-800 border-none focus:outline-none focus:ring-0"
                               placeholder="Search">
                    </form>
                </div>
                <!-- SEARCH RESULT -->
            </div>

            <!-- NAV LINKS -->
            <div class="py-4 text-gray-400 space-y-1">
                <!-- BASIC LINK -->
                <a href="{{ route('admin.index') }}"
                   class="py-2.5 px-4 flex items-center space-x-2 bg-gray-800 text-white hover:bg-gray-800 hover:text-white rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.projects') }}"
                   class="py-2.5 px-4 flex items-center space-x-2 bg-gray-800 text-white hover:bg-gray-800 hover:text-white rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Projekty</span>
                </a>
                <a href="{{ route('admin.users') }}"
                   class="py-2.5 px-4 flex items-center space-x-2 bg-gray-800 text-white hover:bg-gray-800 hover:text-white rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="#ffffff" class="h-[24px] w-[24px]">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z"/>
                    </svg>
                    <span>Uživatelé</span>
                </a>
                <!-- DROPDOWN LINK -->
{{--                <div class="block" x-data="{open: false}">--}}
{{--                    <div @click="open = !open"--}}
{{--                         class="flex items-center justify-between hover:bg-gray-800 hover:text-white cursor-pointer py-2.5 px-4 rounded">--}}
{{--                        <div class="flex items-center space-x-2">--}}
{{--                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"--}}
{{--                                 xmlns="http://www.w3.org/2000/svg">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>--}}
{{--                            </svg>--}}
{{--                            <span>Content</span>--}}
{{--                        </div>--}}
{{--                        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"--}}
{{--                             xmlns="http://www.w3.org/2000/svg">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                  d="M5 15l7-7 7 7"></path>--}}
{{--                        </svg>--}}
{{--                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"--}}
{{--                             xmlns="http://www.w3.org/2000/svg">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"--}}
{{--                                  d="M19 9l-7 7-7-7"></path>--}}
{{--                        </svg>--}}
{{--                    </div>--}}
{{--                    <div x-show="open"--}}
{{--                         class="text-sm border-l-2 border-gray-800 mx-6 my-2.5 px-2.5 flex flex-col gap-y-1">--}}
{{--                        <a href="#" class="block py-2 px-4 hover:bg-gray-800 hover:text-white rounded">--}}
{{--                            Categories--}}
{{--                        </a>--}}
{{--                        <a href="#" class="block py-2 px-4 hover:bg-gray-800 hover:text-white rounded">--}}
{{--                            Courses--}}
{{--                        </a>--}}
{{--                        <a href="#" class="block py-2 px-4 hover:bg-gray-800 hover:text-white rounded">--}}
{{--                            Instruction--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>

        <!-- PROFILE -->
        <div class="text-gray-200 border-gray-800 rounded flex items-center justify-between p-2">
            <div class="flex items-center space-x-2">
                <!-- AVATAR IMAGE BY FIRST LETTER OF NAME -->
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&size=128&background=ff4433&color=fff"
                     class="w-7 w-7 rounded-full" alt="Profile">
                <h1>{{ auth()->user()->name }}</h1>
            </div>
            <a onclick="event.preventDefault(); document.getElementById('logoutForm').submit()" href="#"
               class="hover:bg-gray-800 hover:text-white p-2 rounded">
                <form id="logoutForm" action="{{ route('logout') }}" method="post">
                    @csrf
                </form>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                </form>
            </a>
        </div>

    </div>
</nav>
