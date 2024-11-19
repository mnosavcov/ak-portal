@php
    $projectFileUUID = \Illuminate\Support\Str::uuid();
@endphp
<div x-show="projectShow === 'actualities'" x-cloak class="col-span-full"
     x-data="projectActuality"
     x-init="
            lang.Vyplnte_text_aktuality = @js(__('Vyplňte text aktuality'));
            lang.Chyba_vlozeni_otazky = @js(__('Chyba vložení otázky'));
            lang.Chyba_potvrzeni_odpovedi = @js(__('Chyba potvrzení odpovědi'));
            lang.Opravdu_si_prejete_editovat_obsah_aktuality = @js(__('Opravdu si přejete editovat obsah aktuality?'));
            lang.Chyba_editace_obsahu = @js(__('Chyba editace obsahu'));

            formData.actuality.actuality_file_uuid[0] = @js($projectFileUUID);
            formData.actuality.projectId = @js($project->id);
            data.list = @js($project->getActualities());
            maxActualityId = @js($project->myShow()->max('max_actuality_id') ?? 0);
        "
>

    @if($project->isVerified() && $project->isMine() && !auth()->user()->isSuperadmin() && auth()->user()->isVerified())
        <h2 class="mb-[50px]">
            {{ __('Zveřejněte aktualitu') }}
        </h2>
    @else
        <h2 class="mb-[50px]">{{ __('Aktuality') }}</h2>
    @endif

    <div class="max-w-[800px]">
        @if($project->isVerified() && $project->isMine() && !auth()->user()->isSuperadmin())
            @if(auth()->user()->isVerified())
                <div>
                    <div class="tinyBox-wrap mb-[30px]">
                        <div class="tinyBox">
                            <x-textarea-input id="actuality" name="actuality" class="block mt-1 w-full"
                                              x-model="formData.actuality.actuality"/>
                        </div>
                    </div>

                    <div class="grid grid-cols-2"
                         x-data="{itemActuality: {id: 0}}"
                         x-init="
                        formData.actuality.actuality_file_url[itemActuality.id] = @js(route('project-actualities.store-temp-file', ['uuid' => $projectFileUUID]));
                        tempFiles.fileList[formData.actuality.actuality_file_uuid[itemActuality.id]] = {};
                        tempFiles.fileListError[formData.actuality.actuality_file_uuid[itemActuality.id]] = [];
                        tempFiles.fileListProgress[formData.actuality.actuality_file_uuid[itemActuality.id]] = {};
                    ">
                        @include('app.projects.@actualities-files')

                        <button type="button"
                                class="font-Spartan-SemiBold bg-app-blue text-white text-[14px] h-[45px] justify-self-end rounded-[3px] px-[25px]"
                                @click="sendActuality()"
                        >
                            {{ __('Zveřejnit') }}
                        </button>
                    </div>
                </div>
            @endif
        @elseif($project->isVerified() && $project->isMine() && auth()->user()->isSuperadmin())
        @else
            @if(auth()->guest())
                <div
                    class="grid gap-x-[20px] grid-cols-1 laptop:grid-cols-2 border border-[#D9E9F2] rounded-[3px] p-[25px]">
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            {{ __('Pro zobrazení všech aktualit se musíte přihlásit jako investor a mít ověřený účet.') }}
                        </div>
                        <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                            {{ __('Nemáte účet?') }}
                            <a href="{{ route('register') }}" class="font-Spartan-Bold text-app-blue">
                                {{ __('Registrujte se') }}
                            </a>
                        </div>
                    </div>
                    <div class="text-center laptop:text-right">
                        <a type="button" href="{{ route('login') }}" class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                            {{ __('Přihlásit se') }}
                        </a>
                    </div>
                </div>
            @elseif (!auth()->user()->investor)
                <div>
                    <div class="grid gap-x-[20px]
                    grid-cols-1
                    laptop:grid-cols-2
                    border border-[#D9E9F2] rounded-[3px] p-[25px]
                    ">
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            {{ __('Abyste mohli vidět všechny aktuality, musíte v “Nastavení účtu” přidat typ účtu') }}
                            @if(auth()->user()->check_status !== 'verified')
                                {{ __('“Účet investora” a projít procesem ověření.') }}
                            @else
                                {{ __('“Účet investora”.') }}
                            @endif
                        </div>
                        <div class="text-center laptop:text-right">
                            <a href="{{ route('profile.edit', ['add' => 'investor']) }}" class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                                {{ __('Přidat typ účtu') }}
                            </a>
                        </div>
                    </div>

                    <div id="price-box-bid-list">
                    </div>
                </div>
            @elseif(!auth()->user()->isVerified())
                <div
                    class="grid gap-x-[20px] grid-cols-1 laptop:grid-cols-2 border border-[#D9E9F2] rounded-[3px] p-[25px] text-center laptop:text-left">
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            {{ __('Pro zobrazení aktualit musíte mít ověřený účet investora.') }}
                        </div>
                    </div>
                    <div class="text-center laptop:text-right">
                        <a type="button" href="{{ route('profile.edit') }}" class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] min-h-[60px] leading-[24px] min-w-[200px] py-[18px] w-auto rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] px-[25px]
                        mt-[15px]
                     laptop:mt-0">
                            {{ __('Ověřit účet') }}
                        </a>
                    </div>
                </div>
            @elseif(!auth()->user()->isVerifiedInvestor())
                <div
                    class="grid gap-x-[20px] grid-cols-1 laptop:grid-cols-2 border border-[#D9E9F2] rounded-[3px] p-[25px] text-center laptop:text-left">
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            {{ __('Pro zobrazení aktualit musíte mít ověřený účet investora.') }}
                        </div>
                    </div>
                    <div
                        class="font-Spartan-SemiBold text-[15px] text-app-orange text-center laptop:text-right mt-[15px] laptop:mt-0">
                        {{ __('Váš účet investora čeká na ověření') }}
                    </div>
                </div>
            @elseif (!$project->isPublicForInvestor())
                <div>
                    <div class="grid gap-x-[20px]
                    grid-cols-1
                    laptop:grid-cols-2
                    border border-[#D9E9F2] rounded-[3px] p-[25px]
                    ">
                        <div>
                            <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                                {{ __('U tohoto projektu vyžaduje nabízející vyšší stupeň ověření investorů. O zobrazení detailů o projektu, mezi které patří i aktuality, musíte nabízejícího požádat.') }}
                            </div>
                        </div>

                        @if($project->myShow()->first()->details_on_request === 0)
                            <div class="text-center laptop:text-right">
                                <a href="{{ route('projects.request-details', $project) }}"
                                   class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[14px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                                    {!! __('Chci&nbsp;zobrazit&nbsp;detaily') !!}
                                </a>
                            </div>
                        @elseif($project->myShow()->first()->details_on_request === 1)
                            <div class="font-Spartan-SemiBold text-[18px] leading-[24px] text-app-orange">
                                {{ __('Čekáte na udělení plného přístupu k projektu nabízejícím.') }}
                            </div>
                        @elseif($project->myShow()->first()->details_on_request === -1)
                            <div class="font-Spartan-SemiBold text-[18px] leading-[24px] text-app-red">
                                {{ __('Nabízející vám neschválil plný přístup k projektu.') }}
                            </div>
                        @endif
                    </div>

                    <div id="price-box-bid-list">
                    </div>
                </div>
            @endif
        @endif

        <div x-data="{isVerified: @js($project->isVerified() && auth()->user()->isVerified()),}">
            @include('app.projects.@actualities-actualitybox')
        </div>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

<x-modal name="actuality-info">
    <div class="p-[40px_10px] tablet:p-[50px_40px] text-center">

        <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
             @click="$dispatch('close')"
             class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

        <h2 class="mb-[25px]">{{ __('Aktuality') }}</h2>


        <div class="text-left font-Spartan-Regular text-[16px]">
            {{ __('Aktuality podléhají schvalování administrátorem. Jelikož provozovatel připravoval podstatnou část popisu projektu a zároveň je na úspěšném zobchodování závislé vyplacení jeho provize, má oprávněný zájem na kontrolu, zda je obsah a forma aktuality relevantní. Jakékoliv zavádějící či nevhodné informace by mohly negativně ovlivnit průběh projektu. Provozovatel si vyhrazuje právo provést úpravu aktualit, nebo je i v důvodných případech nezveřejnit.') }}
        </div>
    </div>
</x-modal>
