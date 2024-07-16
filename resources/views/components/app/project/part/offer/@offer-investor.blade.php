@if(($type ?? null) === 'investor')
    <div class="col-span-2 text-center pt-[15px] tablet:pt-[20px]">
        @if($offer->winner)
            @if($project->type === 'offer-the-price')
                <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">
                    Nabízející akceptoval vaši nabídku
                </span>
            @endif
            @if($project->type === 'fixed-price')
                <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px]">
                    Jistotu jste zaplatili
                </div>
                <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">
                    Vaše nabídka je vítězná, vyčkejte na vyzvání k uzavření Rezervační smlouvy
                </div>
            @endif
        @elseif($offer->principal_paid)
            @if($project->type === 'offer-the-price')
                <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">
                    Jistotu jste zaplatili
                </span>
            @endif
            @if($project->type === 'fixed-price')
                <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px]">
                    Jistotu jste zaplatili
                </div>
                @if(\App\Models\ProjectShow::where('project_id', $project->id)->where('winner', 1)->get()->count())
                    <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-red">
                        Vaše nabídka nebyla akceptována, jelikož jistotu zaplatil i investor, který projevil zájem před
                        vámi.
                    </div>
                @elseif(\App\Models\ProjectShow::where('project_id', $project->id)
                        ->where('offer', 1)
                        ->where('user_id', '!=', auth()->user()->id)
                        ->where('offer_time', '<', $project->myShow()->first()->offer_time)
                        ->get()
                        ->count())
                    <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-orange">
                        Čeká se, zda jistotu zaplatí investor, který projevil zájem o projekt před vámi.
                    </div>
                @endif
            @endif
        @else
            <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-orange">
                Čeká se, až uhradíte jistotu
            </span>
            <br>
            <button type="submit"
                    class="mt-[35px] justify-self-start col-span-2 font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] max-tablet:mb-[15px]
                            h-[50px] leading-[50px] w-full text-[14px]
                            tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px]
                            "
                    @click="$dispatch('open-modal', 'pay-principal')"
            >
                Zaplatit jitotu
            </button>

            @if($project->myShow()->first()->variable_symbol !== null && $project->myShow()->first()->principal_sum === null)
                <form x-data="{
                        loaderShow: false,
                        seconds: @js(session('after') ?? 0),
                        display: '',
                        afterText() {
                            if(this.seconds > 0) {
                                return '(další pokus můžete provést za ' + this.display + ')'
                            } else {
                                return '';
                            }
                        },
                        countdown(data) {
                            var interval = setInterval(function() {
                                if (data.seconds <= 0) {
                                    clearInterval(interval);
                                } else {
                                    var minutes = Math.floor(data.seconds / 60); // Celé minuty
                                    var remainingSeconds = data.seconds % 60; // Zbývající sekundy

                                    // Formátování výstupu
                                    if(minutes > 0) {
                                        data.display = minutes.toString().padStart(2, '0') + ':' + remainingSeconds.toString().padStart(2, '0');
                                    } else {
                                        data.display = remainingSeconds.toString().padStart(2, '0') + 's';
                                    }

                                    data.seconds--; // Snížení sekund o jednu
                                }
                            }, 1000); // Každou sekundu
                        }
                      }"
                      x-init="countdown($data)"
                      method="post"
                      action="{{ route('projects.show', ['project' => $project->url_part]) }}"
                      @submit="loaderShow = true;">
                    @csrf
                    <input type="hidden" name="check-payment" value="1">
                    <input type="hidden" name="vs" value="{{ $project->myShow()->first()->variable_symbol }}">
                    <button type="submit"
                            class="text-[#888888] font-Spartan-Regular
                         text-[12px] mt-[15px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mt-[20px] laptop:leading-[26px]
                            "
                    >
                        zkontrolovat platbu
                        <span x-text="afterText()" class="block"></span>
                    </button>

                    <div id="loader" x-show="loaderShow" x-cloak>
                        <span class="loader"></span>
                    </div>
                </form>
            @endif

            @if($project->myShow()->first()->principal_sum !== null)
                <div type="submit"
                     class="text-[#888888] font-Spartan-Regular
                         text-[12px] mt-[15px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mt-[20px] laptop:leading-[26px]
                            "
                >
                    <span class="text-app-red">Jistota není uhrazena.</span>
                    <br>
                    Evidujeme zaplacenou
                    částku {{ number_format($project->myShow()->first()->principal_sum, 0, '.', ' ') }} Kč.
                </div>
            @endif
        @endif
    </div>

    <x-modal name="pay-principal">
        <div
            x-data="{
                    loaderShow: false,
                    loaded: false,
                    vs: '-- generuje se --',
                    qr: null,
                    projectId: @js($project->id),
                    projectUrlPart: @js($project->url_part),
                    async getVs() {
                        await fetch('/projekty/get-vs/' + this.projectUrlPart, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                        }).then((response) => response.json())
                            .then((data) => {
                                if (data.status === 'success') {
                                    this.vs = data.vs;
                                    this.qr = data.qr;
                                    this.loaded = true;
                                } else {
                                    alert('Chyba načtení dat k platbě');
                                }
                            })
                            .catch((error) => {
                                alert('Chyba načtení dat k platbě')
                            });
                    },
                }"
            x-init="$watch('show', value => {
                if (value) {
                    if(!loaded) {
                        getVs()
                    }
                }
            })"
            class="p-[40px_10px] tablet:p-[50px_40px] text-center">
            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
                 class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

            <div class="text-center mb-[30px]">
                <h1>Zaplacení jistoty</h1>
            </div>

            <div
                class="p-[25px] rounded-[7px] bg-[#F4FAFE] text-[#414141] text-center tablet:text-left space-y-[15px] mb-[25px]">
                <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                    <div class="font-Spartan-Bold text-[16px] tablet:text-[20px] leading-[30px]">
                        Projekt:
                    </div>
                    <div
                        class="font-Spartan-Regular text-[16px] tablet:text-[20px] leading-[30px]">
                        {{ $project->title }}
                    </div>
                </div>

                <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                    <div class="font-Spartan-Bold text-[16px] tablet:text-[20px] leading-[30px]">
                        Výše jistoty:
                    </div>
                    <div
                        class="font-Spartan-Regular text-[16px] tablet:text-[20px] leading-[30px]">
                        {!! $project->minimum_principal_text !!}
                    </div>
                </div>
            </div>

            <div
                class="p-[25px] rounded-[7px] bg-[#F8F8F8] text-[#414141] text-center tablet:text-left mb-[25px] tablet:mb-[50px] space-y-[15px]">
                <div class="col-span-full font-Spartan-Bold text-[16px] tablet:text-[20px] leading-[30px] mb-[20px]">
                    Zaplaťte jistotu
                </div>

                <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                    <div class="font-Spartan-Bold text-[16px] tablet:text-[14px] leading-[30px]">
                        Číslo bankovního účtu:
                    </div>
                    <div
                        class="font-Spartan-Regular text-[16px] tablet:text-[14px] leading-[30px]">
                        {{ env('BANK_ACCOUNT') }} / {{ env('BANK_CODE') }}
                    </div>
                </div>

                <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                    <div class="font-Spartan-Bold text-[16px] tablet:text-[14px] leading-[30px]">
                        Variabilní symbol:
                    </div>
                    <div>
                        <div
                            class="font-Spartan-Regular text-[16px] tablet:text-[14px] leading-[30px]"
                            x-text="vs" x-show="loaded" x-cloak
                        >
                        </div>
                        <div class="inline-loader mt-[4px] w-[16px] h-[16px]" x-show="!loaded" x-cloak>
                            <span class="loader"></span>
                        </div>
                    </div>
                </div>

                <div class="col-span-full text-center" x-cloak x-show="qr && loaded">
                    <img :src="qr" class="inline-block">
                </div>
            </div>

            <form method="post" action="{{ route('projects.show', ['project' => $project->url_part]) }}"
                  @submit="loaderShow = true;">
                @csrf
                <input type="hidden" name="check-payment" value="1">
                <input type="hidden" name="vs" :value="vs">
                <button type="submit"
                        class="justify-self-start col-span-2 font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] max-tablet:mb-[15px]
                            h-[50px] leading-[50px] w-full text-[14px]
                            tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px] inline-block disabled:grayscale
                            "
                        :disabled="!loaded"
                >
                    Hotovo
                </button>
            </form>

            <div id="loader" x-show="loaderShow" x-cloak>
                <span class="loader"></span>
            </div>
        </div>
    </x-modal>
@endif
