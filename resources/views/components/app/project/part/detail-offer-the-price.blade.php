<div class="w-full border-[3px] border-[#2872B5] rounded-[3px]
    mt-[20px] p-[20px_10px]
    laptop:p-[50px_30px] laptop:mt-[50px]
    ">
    <div class="grid mb-[30px] gap-y-[10px]
        tablet:grid-cols-2 tablet:gap-x-[25px]
        laptop:flex laptop:flex-wrap laptop:gap-x-[50px]
        ">
        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-status.svg')] after:w-[15px] after:h-[15px]">
            {!! $project->status_text !!}
        </div>

        <div
            class="relative pl-[30px] font-Spartan-Regular text-[14px] text-[#31363A] after:absolute after:top-[3px] after:left-0 after:bg-no-repeat after:bg-[url('/resources/images/ico-price_offer.svg')] after:w-[15px] after:h-[15px]">
            cenu nabídnete
        </div>
    </div>

    <div class="grid mb-[20px] gap-x-[25px]
        grid-cols-1
        tablet:grid-cols-2
        "
         x-data="{countDownDate: false}"
         x-init="
        countDownDate = @js($project->use_countdown_date_text_long);
        if(countDownDate !== false) {
            window.onload = function() {
                window.countdown(countDownDate);
            }
        }">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]
            order-1
            ">Aktuální cena
        </div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]
            order-3 tablet:order-2
            ">Zbývá
        </div>
        <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-orange
            order-2 tablet:order-3
            ">nabídne investor
        </div>
        <div class="font-Spartan-Bold text-[18px] leading-[30px] text-app-green
            order-4
            " id="projectEndDate">{{ $project->end_date_text_long }}
        </div>
    </div>

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[20px] tablet:mb-[30px]"></div>

    {{--    jsem zadavatel --}}
    @if(auth()->user() && auth()->user()->isSuperadmin())
        <div class="font-Spartan-Regular text-[#414141]
                text-[15px] leading-[20px] mb-[15px]
                tablet:text-[17px] tablet:leading-[24px] tablet:mb-[20px]
                laptop:text-[20px] laptop:leading-[30px]">
            Obdržené nabídky
        </div>

        @if($project->offers()->count())
            <div x-data="{offersOpen: false}">
                <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-blue underline relative {{ $project->offers()->count() ? 'cursor-pointer' : '' }}
                    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat"
                     @if($project->offers()->count())
                         @click="offersOpen = !offersOpen"
                    @endif
                >
                    {{ $project->offers()->count() }}

                    @if($project->offers()->count() > 0 && $project->offers()->count() < 5)
                        nabízející
                    @else
                        nabízejících
                    @endif
                </div>

                <div x-show="offersOpen" x-cloak x-collapse>
                    @foreach($project->offers() as $offer)
                        @include(
                            'components.app.project.part.@nabidka',
                            [
                                'title' => 'Nabídka ' . $loop->iteration,
                                'type' => 'superadmin',
                                'offer' => $offer,
                                'user' => \App\Models\User::find($offer->user_id)
                            ]
                        )
                    @endforeach
                </div>
            </div>

        @else
            <div class="font-Spartan-Regular text-[#414141] bg-[#F8F8F8] rounded-[3px]
                text-[13px] leading-[24px] mb-[25px] p-[15px]
                laptop:text-[15px] laptop:leading-[26px] laptop:mb-[30px] laptop:p-[20px]
            ">
                U projektu zatím nemáte žádné nabídky.
            </div>
        @endif
    @elseif($project->isMine())
        <div class="font-Spartan-Regular text-[#414141]
                text-[15px] leading-[20px] mb-[15px]
                tablet:text-[17px] tablet:leading-[24px] tablet:mb-[20px]
                laptop:text-[20px] laptop:leading-[30px]">
            Obdržené nabídky
        </div>

        @if($project->offers()->count())
            <div x-data="{offersOpen: false}">
                <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-blue underline relative {{ $project->offers()->count() ? 'cursor-pointer' : '' }}
                    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat"
                     @if($project->offers()->count())
                         @click="offersOpen = !offersOpen"
                    @endif
                >
                    {{ $project->offers()->count() }}

                    @if($project->offers()->count() > 0 && $project->offers()->count() < 5)
                        nabízející
                    @else
                        nabízejících
                    @endif
                </div>

                <div x-show="offersOpen" x-cloak x-collapse x-data="{winner: null}">
                    @foreach($project->offers() as $offer)
                        <div
                            @if($offer->winner)
                                x-init="winner = @js($offer->id);"
                            @endif
                        >
                            @include(
                                'components.app.project.part.@nabidka',
                                [
                                    'title' => 'Nabídka ' . $loop->iteration,
                                    'type' => 'advertiser',
                                    'offer' => $offer,
                                    'user' => ($project->exclusive_contract ? \App\Models\User::find($offer->user_id) : false)
                                ]
                            )
                        </div>
                    @endforeach
                </div>
            </div>

        @else
            <div class="font-Spartan-Regular text-[#414141] bg-[#F8F8F8] rounded-[3px]
                text-[13px] leading-[24px] mb-[25px] p-[15px]
                laptop:text-[15px] laptop:leading-[26px] laptop:mb-[30px] laptop:p-[20px]
            ">
                U projektu zatím nemáte žádné nabídky.
            </div>
        @endif
        {{--    nejsem zadavatel--}}
    @else
        {{--        NEverifikovaný--}}
        @if(!$project->isVerified())
            <div class="grid gap-x-[20px] mb-[25px] grid-cols-1 laptop:grid-cols-2">

                @if(auth()->guest())
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">Pro zaslání nabídky a
                            zobrazení
                            všech údajů se musíte přihlásit a mít ověřený účet.
                        </div>
                        <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                            Nemáte účet?
                            <a href="{{ route('register') }}" class="font-Spartan-Bold text-app-blue">Registrujte se</a>
                        </div>
                    </div>
                    <div class="text-center">
                        <a type="button" href="{{ route('login') }}"
                           class="self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                            Přihlásit se
                        </a>
                    </div>
                @else
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">Pro zaslání nabídky a
                            zobrazení všech údajů musíte ověřit účet.
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                            Ověřit účet
                        </a>
                    </div>
                @endif
            </div>
            {{--            VERIFIKOVANÝ--}}
        @else
            @if(auth()->user()->investor)
                @if($project->myOffer())

                    @include(
                        'components.app.project.part.@nabidka',
                        [
                            'title' => 'Vaše nabídka',
                            'type' => 'investor',
                            'offer' => $project->myOffer(),
                            'user' => auth()->user()
                        ]
                    )

                @else
                    <div class="grid grid-cols-1" x-data="{minPrice: @js($project->price), offerPrice: '',
                    formatMoney(value, decimalSeparator = '.', thousandSeparator = ' ', decimalPlaces = 0) {
                    let [integer, decimal] = parseFloat(parseInt(value.replace(/\s+/g, ''))).toFixed(decimalPlaces).split('.');
                    integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
                    if(integer === 'NaN') {
                        return 0;
                    }
                    return decimalPlaces ? `${integer}${decimalSeparator}${decimal}` : integer;
                    }}">
                        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">
                            Zadejte částku své nabídky
                        </div>
                        <div>
                            <div class="relative inline-block">
                                <x-text-input id="nabidka" x-model="offerPrice"
                                              class="mb-[25px] relative block mt-1 w-[250px] pr-[60px]"
                                              x-mask:dynamic="$money($input, '.', ' ', 0)"
                                              type="text"/>
                                <div
                                    class="absolute text-[#A7A4A4] right-[40px] text-[13px] top-0 leading-[52px] font-Spartan-Regular">
                                    Kč
                                </div>
                            </div>
                        </div>

                        <div class="text-left">
                            <button x-data type="button" @click.next="
                                        let offer = parseInt(offerPrice.replace(/\s+/g, ''))
                                        let offerFormated = offerPrice
                                        if(Number.isNaN(offer) || offer < minPrice) {
                                            alert('Nabídněte cenu minimálně {!! $project->price_text_offer !!}')
                                            return;
                                        }
                                    $dispatch('open-modal', {name: 'send-offer', offer: offer, offerFormated: formatMoney(offerPrice)})
                                "
                                    class="font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[350px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px] disabled:grayscale"
                                    :disabled="(Number.isNaN(parseInt(offerPrice.replace(/\s+/g, ''))) || parseInt(offerPrice.replace(/\s+/g, '')) < minPrice)">
                                Nabídnout
                            </button>
                        </div>
                    </div>

                    <x-modal name="send-offer">
                        <div class="p-[40px_10px] tablet:p-[50px_40px] text-center" x-data="{
                            loaderShow: false,
                            projectId: @js($project->id),
                            async addOffer(offer) {
                                this.loaderShow = true;
                                await fetch('/projects/add-offer', {
                                    method: 'POST',
                                    body: JSON.stringify({
                                        offer: offer,
                                        projectId: this.projectId,
                                    }),
                                    headers: {
                                        'Content-type': 'application/json; charset=UTF-8',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    },
                                }).then((response) => response.json())
                                    .then((data) => {
                                    console.log(data.status);
                                        if(data.status === 'ok') {
                                            window.location.reload()
                                            return;
                                        }

                                        alert('Nepodařilo se vložit nabídku')
                                        this.loaderShow = false;
                                    })
                                    .catch((error) => {
                                        alert('Chyba vložení nabídky')
                                        this.loaderShow = false;
                                    });
                            }
                        }">

                            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
                                 class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

                            <div class="text-center mb-[30px]">
                                <h1>Podání nabídky</h1>
                            </div>

                            <div
                                class="p-[25px] rounded-[7px] bg-[#F4FAFE] text-[#414141] text-center tablet:text-left mb-[20px] space-y-[15px]">
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
                                        Nabídková cena:
                                    </div>
                                    <div
                                        class="font-Spartan-Regular text-[16px] tablet:text-[20px] leading-[30px]"
                                        x-text="inputData.offerFormated + ' Kč'">
                                    </div>
                                </div>
                            </div>

                            <div class="max-w-[1200px] mx-auto">
                                <div class="relative w-full max-w-[900px] p-[15px] pl-[50px] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px] bg-[#F8F8F8]
                                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
                                    <div class="text-left">
                                        <p class="mb-[10px]">
                                            <span class="font-Spartan-SemiBold">Podáním nabídky se určuje pořadí projeveného zájmu o koupi.</span>Aby
                                            vaše nabídka byla platná, je třeba uhradit <span
                                                class="font-Spartan-SemiBold">jistinu</span>,
                                            jejíž výše je uvedena u projektu.
                                        </p>
                                        <p class="mb-[10px]">
                                            Po potvrzení vašeho zájmu podáním nabídky vás budeme kontaktovat a zašleme
                                            vám
                                            instrukce k úhradě jistiny.
                                        </p>
                                        <p>
                                            Jistina musí být připsána na náš účet nejpozději do dvou pracovních dní od
                                            zaslání instrukcí k úhradě. V opačném případě může být vaše nabídka z
                                            pořadníku
                                            vyloučena.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <button
                                class="mt-[15px] cursor-pointer text-center font-Spartan-Bold text-[18px] text-white h-[60px] leading-[60px] w-full max-w-[350px] bg-app-green rounded-[3px] disabled:grayscale"
                                @click="addOffer(inputData.offer)"
                            >Podat nabídku
                            </button>

                            <div id="loader" x-show="loaderShow" x-cloak>
                                <span class="loader"></span>
                            </div>
                        </div>
                    </x-modal>
                @endif
                {{--                nemam investorsky ucet--}}
            @else
                <div class="grid gap-x-[20px] mb-[25px]
                grid-cols-1
                laptop:grid-cols-2
                ">
                    <div>
                        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                            Pro zaslání nabídky a musíte nastavit typ účty jako investor.
                        </div>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                            Nastavit účet
                        </a>
                    </div>
                </div>
            @endif
        @endif
    @endif

    <div class="h-[1px] bg-[#D9E9F2] w-full mb-[30px]"></div>

    <div class="grid tablet:grid-cols-2">
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Konec příjmu nabídek</div>
        <div
            class="font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
             justify-self-start
            laptop:justify-self-end
            ">{{ $project->end_date_text_normal }}</div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Minimální výše nabídky</div>
        <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
            {!! $project->price_text_offer !!}
            @if(auth()->guest())
                <div
                    class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[20px] right-auto bg-left
                     laptop:left-auto laptop:right-[20px] laptop:bg-right">
                </div>
            @endif
        </div>
        <div class="font-Spartan-Bold text-[13px] leading-[29px] text-[#414141]">Požadovaná jistina</div>
        <div class="relative font-Spartan-Regular text-[13px] leading-[29px] text-[#414141]
            justify-self-start
            laptop:justify-self-end">
            {!! $project->minimum_principal_text !!}
            @if(!$project->isVerified())
                <div
                    class="absolute bg-[url('/resources/images/ico-private.svg')] bg-no-repeat w-full h-full top-0
                     left-[20px] right-auto bg-left
                     laptop:left-auto laptop:right-[20px] laptop:bg-right">
                </div>
            @endif
        </div>
    </div>
</div>
