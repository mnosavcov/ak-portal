@php
    $actualFolderCount = 0;
    $actualFilenameCount = 0;
    $actualFolderText = '';
    $classBackground = '__UNDEFINED__';
@endphp

<div class="contents" x-show="projectShow === 'dokumentace'" x-cloak>
    <div class="col-span-full" x-data="{folderOpen: []}">
        @foreach($files as $file)
            @if($actualFolder === null || ($actualFolder ?? '') !== trim($file->folder ?? ''))
                @if(strlen($actualFolder ?? ''))
                    </div>
                    <hr class="my-4 border-t border-gray-300">
                @endif

                @php
                    $actualFolder = trim($file->folder ?? '');
                    $actualFolderText = $actualFolder;
                    $classBackground = '__UNDEFINED__';
                    if(!$project->isVerified()) {
                        $actualFolderCount++;
                        $actualFolderText = 'Složka ' . $actualFolderCount;
                    }
                @endphp

                @if(strlen($actualFolder ?? ''))
                    <div
                        class="cursor-pointer grid grid-cols-[80px_1fr] py-[20px] font-WorkSans-SemiBold text-app-blue text-[15px]"
                        x-init="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}'] = false;"
                        @click="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}'] = !folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}']">
                        <img src="{{ Vite::asset('resources/images/ico-folder.svg') }}">
                        {{ $actualFolderText }}
                    </div>

                    <div x-show="folderOpen['{{ \Illuminate\Support\Str::slug($file->folder) }}']" class="grid grid-cols-[1fr_min-content]" x-cloak x-collapse>
                @else
                    <div class="grid grid-cols-[1fr_min-content]">
                @endif
                <div class="font-Spartan-Bold text-[13px] bg-[#5E6468] text-white h-[50px] leading-[50px] {{ strlen($actualFolder ?? '') ? 'pl-[80px]' : 'pl-[25px]' }}">Název&nbsp;souboru</div>
                <div class="pr-[25px] font-Spartan-Bold text-[13px] bg-[#5E6468] text-white h-[50px] leading-[50px]">Datum&nbsp;nahrání</div>
            @endif

            @php
                $filename = trim($file->filename ?? '');
                $filetime = \Carbon\Carbon::make($file->created_at);
                if($classBackground === '') {
                    $classBackground = 'bg-[#F8F8F8]';
                } else {
                    $classBackground = '';
                }
                if(!$project->isVerified()) {
                    $filename = explode('.', $filename);
                    $actualFilenameCount++;
                    if(count($filename) > 1) {
                        $filename = 'Dokument ' . $actualFilenameCount .  '.' . $filename[count($filename) - 1];
                    }
                }
            @endphp

            @if($project->isVerified())
                <a href="{{ $file->url }}"
                   class="{{ $classBackground }} pr-[20px] underline hover:no-underline font-WorkSans-Regular text-app-blue text-[15px] py-[10px] inline-block  {{ strlen($actualFolder ?? '') ? 'pl-[80px]' : 'pl-[25px]' }}">
                    {{ $file->filename }}
                </a>
            @else
                <div
                    class="{{ $classBackground }} font-WorkSans-Regular text-[#414141] text-[15px] py-[10px] inline-block  {{ strlen($actualFolder ?? '') ? 'pl-[80px]' : 'pl-[25px]' }}">
                    {{ $filename }}
                </div>
            @endif
            <div class="{{ $classBackground }} pr-[20px] font-WorkSans-Regular text-[#414141] text-[15px] py-[10px] inline-block whitespace-nowrap">
                {!! $filetime->format('d. m. Y H:i:s') !!}
            </div>

            @if($project->isVerified() && !empty(trim($file->description)))
                <div
                    class="{{ $classBackground }} mt-[-10px] col-span-full font-WorkSans-Regular text-[#414141] text-[15px] py-[10px] inline-block  {{ strlen($actualFolder ?? '') ? 'pl-[80px]' : 'pl-[25px]' }}">
                    {{ $file->description }}
                </div>
            @endif
        @endforeach

        @if($files->count())
            </div>
        @endif
    </div>
</div>
