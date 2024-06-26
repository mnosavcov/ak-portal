@if($project->isVerified())
    <div class="contents" x-show="projectShow === 'dokumentace'" x-cloak>
        <div class="col-span-full" x-data="{folderOpen: []}">
            @foreach($files as $file)
                @if(($actualFolder ?? '') !== trim($file->folder ?? ''))
                    @if(strlen($actualFolder ?? ''))
        </div>
        <hr class="my-4 border-t border-gray-300">
        @endif

        @php
            $actualFolder = trim($file->folder ?? '')
        @endphp

        @if(strlen($actualFolder ?? ''))
            <div
                class="cursor-pointer grid grid-cols-[80px_1fr] py-[20px] font-WorkSans-SemiBold text-app-blue text-[15px]"
                x-init="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}'] = false;"
                @click="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}'] = !folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}']"
            >
                <img src="{{ Vite::asset('resources/images/ico-folder.svg') }}">
                {{ trim($file->folder ?? '') }}
            </div>
            <div
                x-show="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}']" x-cloak
                x-collapse
            >
                @endif
                @endif

                <a href="{{ $file->url }}"
                    class="underline hover:no-underline font-WorkSans-Regular text-app-blue text-[15px] my-[10px] inline-block  {{ strlen($actualFolder ?? '') ? 'ml-[80px]' : '' }}">{{ $file->filename }}</a>
                <div></div>
                @endforeach
                @if(strlen($actualFolder ?? ''))
            </div>
        @endif
    </div>
@else
    @php
        $actualFolderCount = 0;
        $actualFilenameCount = 0;
        $actualFolderText = '';
    @endphp
    <div class="contents" x-show="projectShow === 'dokumentace'" x-cloak>
        <div class="col-span-full" x-data="{folderOpen: []}">
            @foreach($files as $file)
                @if(($actualFolder ?? '') !== trim($file->folder ?? ''))
                    @if(strlen($actualFolder ?? ''))
        </div>
        <hr class="my-4 border-t border-gray-300">
        @endif

        @php
            $actualFolder = trim($file->folder ?? '');
            $actualFolderCount++;
            $actualFolderText = 'Složka ' . $actualFolderCount;
        @endphp

        @if(strlen($actualFolder ?? ''))
            <div
                class="cursor-pointer grid grid-cols-[80px_1fr] py-[20px] font-WorkSans-SemiBold text-app-blue text-[15px]"
                x-init="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}'] = false;"
                @click="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}'] = !folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}']"
            >
                <img src="{{ Vite::asset('resources/images/ico-folder.svg') }}">
                {{ $actualFolderText }}
            </div>
            <div
                x-show="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}']" x-cloak
                x-collapse
            >
                @endif
                @endif

                @php
                    $filename = trim($file->filename ?? '');
                    $filename = explode('.', $filename);
                    $actualFilenameCount++;
                    if(count($filename) > 1) {
                        $filename = 'Dokument ' . $actualFilenameCount .  '.' . $filename[count($filename) - 1];
                    }
//                    $actualFolderCount++;
//                    $actualFolderText = 'Složka ' . $actualFolderCount;
                @endphp
                <div
                   class="font-WorkSans-Regular text-[#414141] text-[15px] my-[10px] inline-block  {{ strlen($actualFolder ?? '') ? 'ml-[80px]' : '' }}">{{ $filename }}</div>
                <div></div>
                @endforeach
                @if(strlen($actualFolder ?? ''))
            </div>
        @endif
    </div>
@endif
