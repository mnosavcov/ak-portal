<x-admin-layout>
    <main class="flex-1 h-screen overflow-y-scroll overflow-x-hidden">
        <div class="md:hidden justify-between items-center bg-black text-white flex">
            <h1 class="text-2xl font-bold px-4">{{ env('APP_NAME') }}</h1>
            <button @click="navOpen = !navOpen" class="btn p-4 focus:outline-none hover:bg-gray-800">
                <svg class="w-6 h-6 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <section class="max-w-7xl mx-auto py-4 px-5">
            <div class="flex justify-between items-center border-b border-gray-300 mb-[25px]">
                <h1 class="text-2xl font-semibold pt-2 pb-6">{{ __('admin.Projekty') }}</h1>
            </div>

            @foreach($projects as $projectsIndex => $projectList)
                @continue(!count($projectList))

                <h2 class="p-[5px] bg-gray-200 mt-[25px]">{{ $statuses[$projectsIndex]['title'] ?? $projectsIndex }}
                    ({{ count($projectList) }})</h2>

                @foreach($projectList as $project)
                    <div class="mb-[0px] border-b py-[15px] px-[5px] hover:bg-gray-200">
                        <h3 class="font-bold mb-[5px]">{{ $project->title }}</h3>
                        @if($project->offers()->count())
                            <a href="{{ $project->url_detail }}" class="text-app-red font-bold" target="_blank">(nabídek: {{ $project->offers()->count() }})</a>
                        @endif

                        <div class="flex flex-row gap-x-[10px] divide-x divide-gray-400 mb-[5px]">
                            <div>{{ $user_account_type[$project->user_account_type] ?? $project->user_account_type }}</div>
                            <div class="pl-[10px]">{{ $project->end_date_text }}</div>
                            <div class="pl-[10px]">{{ $project->price_text }}</div>
                        </div>
                        @if($project->status === 'reminder')
                            <div class="bg-app-red text-white p-[10px] rounded-[3px] mb-[5px]">
                                {{ $project->user_reminder }}
                            </div>
                        @endif
                        <div>
                            <a href="{{ $project->url_detail }}"
                               class="text-app-orange underline hover:no-underline">{{ __('admin.Náhled_projektu') }}</a>
                            <a href="{{ route('admin.projects.edit', ['project' => $project]) }}"
                               class="text-app-orange underline hover:no-underline">{{ __('admin.Editace_projektu') }}</a>
                        </div>
                    </div>
                @endforeach
            @endforeach

        </section>
    </main>
</x-admin-layout>
