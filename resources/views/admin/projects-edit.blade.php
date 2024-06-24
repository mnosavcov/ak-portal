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
                    <div x-init="
                            projectFiles.data = @js($project->files->pluck(null, 'id'));
                            projectFolders = @js($project->files()->whereNotNull('folder')->pluck('folder', 'folder'));
                            console.log(projectFolders);
                        "></div>
                @endif
                @if($project->galleries->count())
                    <div x-init="projectGalleries.data = @js($project->galleries->pluck(null, 'id'));"></div>
                @endif
                @if($project->images->count())
                    <div x-init="projectImages.data = @js($project->images->pluck(null, 'id'));"></div>
                @endif
                @if($project->tags->count())
                    <div x-init="projectTags.data = @js($project->tags->pluck(null, 'id'));"></div>
                @endif
                @csrf

                @include('admin.projects-edit.@projekt')
                @include('admin.projects-edit.@states')
                @include('admin.projects-edit.@details')
                @include('admin.projects-edit.@files')
                @include('admin.projects-edit.@galleries')
                @include('admin.projects-edit.@settings')

                <div x-data="{ open: true }" class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'"
                         @click="open = !open"
                         x-show="Object.entries(projectDetails.data).length"></div>
                    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">
                        Režim našeho smluvního vztahu s nabízejícím
                    </div>
                    <div x-data="{exclusive: @js($project->exclusive_contract ? 1 : 0)}">
                        <input type="hidden" x-model="exclusive" name="exclusive_contract">
                        <div
                            @click="exclusive = (exclusive ? 0 : 1)"
                            class="p-[15px_50px] inline-block max-tablet:text-center font-Spartan-Bold text-[18px] text-white bg-app-red whitespace-nowrap rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[10px] cursor-pointer"
                            x-text="exclusive ? 'Exkluzivní smlouva' : 'Neexkluzivní smlouva'"
                            :class="{
                            'bg-app-green': exclusive,
                            'bg-app-red': !exclusive,
                        }">
                        </div>
                        <div class="font-Spartan-SemiBold text-[15px]"
                             x-text="exclusive ? 'Nabízející uvidí identifikaci kupujícího' : 'Nabízející neuvidí identifikaci kupujícího'">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="bg-app-green p-[15px_25px] rounded-[3px] font-WorkSans-SemiBold text-white">
                    Uložit změny
                </button>

                <div x-data="{ enable: false }" class="mt-[-15px]">
                    <div
                        class="z-10 relative bg-white left-[20px] top-[35px] tablet:top-[40px] cursor-pointer w-[20px] h-[20px] border border-[#E2E2E2] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.05)]"
                        :class="{'after:absolute after:bg-app-green after:w-[14px] after:h-[14px] after:left-[2px] after:top-[2px] after:rounded-[3px]': enable}"
                        @click="enable = !enable"
                    >
                    </div>
                    <button type="button" @click="deleteProject({{ $project->id }})"
                            class="h-[50px] leading-[50px] tablet:h-[60px] tablet:leading-[60px] max-tablet:w-full max-tablet:text-center tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-red whitespace-nowrap rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[70px]"
                            :disabled="!enable">Smazat&nbsp;projekt
                    </button>
                </div>
            </form>

        </section>
    </main>
</x-admin-layout>
