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
                <h1 class="text-2xl font-semibold pt-2 pb-6">Editace projektu</h1>
            </div>
            <a href="{{ $project->url_detail }}" target="_blank"
               class="bg-app-orange p-[10px_25px] inline-block mb-[20px] text-white font-WorkSans-Regular rounded-[3px]">Náhled</a>

            <h2 class="mb-[25px]">{{ $project->title }}</h2>

            @include('admin.projects-edit.@user-info')

            <form method="post" action="" x-data="adminProjectEdit" enctype="multipart/form-data">
                @if($project->states->count())
                    <div x-init="projectStates.data = @js($project->states->pluck(null, 'id'));"></div>
                @endif
                @if(count($projectDetails))
                    <div x-init="projectDetails.data = @js($projectDetails);"></div>
                @endif
                @if($project->files->count())
                    <div x-init="projectFiles.data = @js($project->files->pluck(null, 'id'));"></div>
                @endif
                @if($project->galleries->count())
                    <div x-init="projectGalleries.data = @js($project->galleries->pluck(null, 'id'));"></div>
                @endif
                @csrf

                @include('admin.projects-edit.@projekt')
                @include('admin.projects-edit.@states')
                @include('admin.projects-edit.@details')
                @include('admin.projects-edit.@files')
                @include('admin.projects-edit.@galleries')

                <button type="submit"
                        class="bg-app-green p-[15px_25px] rounded-[3px] font-WorkSans-SemiBold text-white">
                    Uložit změny
                </button>
            </form>

        </section>
    </main>
</x-admin-layout>
