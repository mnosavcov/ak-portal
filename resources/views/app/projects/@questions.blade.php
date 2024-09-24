@php
    $projectFileUUID = \Illuminate\Support\Str::uuid();
@endphp
<div x-show="projectShow === 'questions'" x-cloak class="col-span-full"
     x-data="projectQuestion"
     x-init="
            formData.question.answer_file_uuid[0] = @js($projectFileUUID);
            formData.question.projectId = @js($project->id);
            data.list = @js($project->getQuestionsWithAnswers());
            maxQuestionId = @js($project->myShow()->max('max_question_id') ?? 0);
        "
>

    @if($project->isVerified() && $project->isMine() && !auth()->user()->isSuperadmin())
        <h2 class="mb-[50px]">Odpovězte na otázky investorům</h2>
    @elseif($project->isVerified() && $project->isMine() && auth()->user()->isSuperadmin())
        <h2 class="mb-[50px]">Otázky a odpovědi</h2>
    @elseif($project->isVerified())
        <h2 class="mb-[50px]">Zašlete otázku nabízejícímu</h2>
    @else
        <h2 class="mb-[50px]">Otázky a odpovědi</h2>
    @endif

    <div class="max-w-[800px]">
        @if ($project->isMine())
        @elseif($project->isVerified() && auth()->user()->isVerified())
            <div>
                <div class="tinyBox-wrap mb-[30px]">
                    <div class="tinyBox">
                        <x-textarea-input id="question" name="question" class="block mt-1 w-full"
                                          x-model="formData.question.question"/>
                    </div>
                </div>

                <div class="grid grid-cols-2"
                     x-data="{itemAnswer: {id: 0}}"
                     x-init="
                        formData.question.answer_file_url[itemAnswer.id] = @js(route('project-questions.store-temp-file', ['uuid' => $projectFileUUID]));
                        tempFiles.fileList[formData.question.answer_file_uuid[0]] = {};
                        tempFiles.fileListError[formData.question.answer_file_uuid[0]] = [];
                        tempFiles.fileListProgress[formData.question.answer_file_uuid[0]] = {};
                    ">
                    @include('app.projects.@questions-files')

                    <button type="button"
                            class="font-Spartan-SemiBold bg-app-blue text-white text-[14px] h-[45px] justify-self-end rounded-[3px] px-[25px]"
                            @click="sendQuestion()"
                    >
                        Odeslat otázku
                    </button>
                </div>
            </div>
        @else
            @if(auth()->guest())
                <div
                    class="grid gap-x-[20px] grid-cols-1 laptop:grid-cols-2 border border-[#D9E9F2] rounded-[3px] p-[25px]">
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            Pro zaslání otázky a zobrazení všech otázek a odpovědí se musíte přihlásit jako investor a
                            mít ověřený
                            účet.
                        </div>
                        <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                            Nemáte účet?
                            <a href="{{ route('register') }}" class="font-Spartan-Bold text-app-blue">Registrujte
                                se</a>
                        </div>
                    </div>
                    <div class="text-center laptop:text-right">
                        <a type="button" href="{{ route('login') }}" class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                            Přihlásit se
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
                            Abyste mohli vidět všechny otázky a odpovědi, musíte v “Nastavení účtu” přidat typ účtu
                            @if(auth()->user()->check_status !== 'verified')
                                “Účet investora” a projít procesem ověření.
                            @else
                                “Účet investora”.
                            @endif
                        </div>
                        <div class="text-center laptop:text-right">
                            <a href="{{ route('profile.edit', ['add' => 'investor']) }}" class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                                Přidat typ účtu
                            </a>
                        </div>
                    </div>
                </div>
            @elseif(!auth()->user()->isVerified())
                <div
                    class="grid gap-x-[20px] grid-cols-1 laptop:grid-cols-2 border border-[#D9E9F2] rounded-[3px] p-[25px] text-center laptop:text-left">
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            Pro zaslání otázky nebo pro zobrazení otázek a odpovědí musíte mít ověřený účet investora.
                        </div>
                    </div>
                    <div class="text-center laptop:text-right">
                        <a type="button" href="{{ route('profile.edit') }}" class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] min-h-[60px] leading-[24px] min-w-[200px] py-[18px] w-auto rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] px-[25px]
                        mt-[15px]
                     laptop:mt-0">
                            Ověřit účet
                        </a>
                    </div>
                </div>
            @elseif(!auth()->user()->isVerifiedInvestor())
                <div
                    class="grid gap-x-[20px] grid-cols-1 laptop:grid-cols-2 border border-[#D9E9F2] rounded-[3px] p-[25px] text-center laptop:text-left">
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            Pro zaslání otázky nebo pro zobrazení otázek a odpovědí musíte mít ověřený účet investora.
                        </div>
                    </div>
                    <div class="font-Spartan-SemiBold text-[15px] text-app-orange text-center laptop:text-right mt-[15px] laptop:mt-0">
                        Váš účet investora čeká na ověření
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
                                U tohoto projektu vyžaduje nabízející vyšší stupeň ověření investorů. O zobrazení
                                detailů o projektu, mezi které patří otázky a odpovědi, musíte nabízejícího požádat.
                            </div>
                        </div>

                        @if($project->myShow()->first()->details_on_request === 0)
                            <div class="text-center laptop:text-right">
                                <a href="{{ route('projects.request-details', $project) }}"
                                   class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[14px] h-[60px] leading-[60px] w-[300px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                                    Chci&nbsp;zobrazit&nbsp;detaily
                                </a>
                            </div>
                        @elseif($project->myShow()->first()->details_on_request === 1)
                            <div class="font-Spartan-SemiBold text-[18px] leading-[24px] text-app-orange">
                                Čekáte na udělení plného přístupu k projektu nabízejícím.
                            </div>
                        @elseif($project->myShow()->first()->details_on_request === -1)
                            <div class="font-Spartan-SemiBold text-[18px] leading-[24px] text-app-red">
                                Nabízející vám neschválil plný přístup k projektu.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif

        <template x-if="!data.list.length">
            <div>
                <div class="h-[1px] bg-[#D9E9F2] mt-[30px]"></div>
                <div class="mt-[30px]">
                    Zatím nejsou vložené žádné otázky a odpovědi.
                </div>
            </div>
        </template>

        <div x-data="{answerContent: @js($answerboxRenderView), isVerified: @js($project->isVerified() && auth()->user()->isVerified()),}">
            @include('app.projects.@questions-questionbox')
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

<x-modal name="question-info">
    <div class="p-[40px_10px] tablet:p-[50px_40px] text-center">

        <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
             @click="$dispatch('close')"
             class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

        <h2 class="mb-[25px]">Otázky a odpovědi</h2>


        <div class="text-left font-Spartan-Regular text-[16px]">
            Otázky a odpovědi podléhají schvalování administrátorem. Jelikož jsou viditelné i pro ostatní oprávněné
            uživatele, musí být jejich obsah a forma zcela relevantní. Jakékoliv zavádějící či nevhodné informace by
            mohly negativně ovlivnit průběh projektu. Provozovatel si vyhrazuje právo provést úpravu otázek i odpovědí,
            nebo je i v důvodných případech nezveřejnit.
        </div>
    </div>
</x-modal>
