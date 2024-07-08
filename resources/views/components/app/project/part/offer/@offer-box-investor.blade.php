@if(auth()->guest())
    <div class="grid gap-x-[20px] mb-[25px] grid-cols-1 laptop:grid-cols-2">
        <div>
            <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                Pro zaslání nabídky a zobrazení všech údajů se musíte přihlásit jako investor a mít ověřený
                účet.
            </div>
            <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                Nemáte účet?
                <a href="{{ route('register') }}" class="font-Spartan-Bold text-app-blue">Registrujte
                    se</a>
            </div>
        </div>
        <div class="text-center laptop:text-right">
            <a type="button" href="{{ route('login') }}"
               class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                Přihlásit se
            </a>
        </div>
    </div>
@elseif(auth()->user()->investor && !auth()->user()->isInvestorVerified())
    <div class="grid gap-x-[20px] mb-[25px] grid-cols-1 laptop:grid-cols-2">
        <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
            Pro zobrazení všech údajů nebo zaslání nabídky musíte ověřit svůj účet.
        </div>
        <div class="text-center laptop:text-right">
            <a href="{{ route('profile.edit') }}"
               class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                Ověřit účet
            </a>
        </div>
    </div>
@elseif(auth()->user()->investor)
    @if($project->myOffer())
        @include(
            'components.app.project.part.offer.@offer',
            [
                'title' => 'Vaše nabídka',
                'type' => 'investor',
                'offer' => $project->myOffer(),
                'user' => auth()->user()
            ]
        )
    @elseif(!$project->isPublicForInvestor())
        <div class="grid gap-x-[20px] mb-[25px] grid-cols-1 laptop:grid-cols-2">
            <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                U tohoto projektu se detaily projektu zobrazí jen zájemcům, kterým přístup umožní prodávající.
            </div>

            @if($project->myShow()->first()->details_on_request === 0)
                <div class="text-center laptop:text-right">
                    <a href="{{ route('projects.request-details', $project) }}"
                       class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                        Chci zobrazit detaily
                    </a>
                </div>
            @elseif($project->myShow()->first()->details_on_request === 1)
                <div class="font-Spartan-SemiBold text-[18px] leading-[24px] text-app-orange">
                    Čekáte na udělení plného přístupu k projektu nabízejícím
                </div>
            @elseif($project->myShow()->first()->details_on_request === -1)
                <div class="font-Spartan-SemiBold text-[18px] leading-[24px] text-app-red">
                    Nabízející vám neschválil plný přístup k projektu. Nabídku nemůžete učinit
                </div>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1" x-data="{minPrice: @js($project->price), offerPrice: '',
                        formatMoney(value, decimalSeparator = '.', thousandSeparator = ' ', decimalPlaces = 0) {
                        let [integer, decimal] = parseFloat(parseInt(String(value).replace(/\s+/g, ''))).toFixed(decimalPlaces).split('.');
                        integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
                        if(integer === 'NaN') {
                            return 0;
                        }
                        return decimalPlaces ? `${integer}${decimalSeparator}${decimal}` : integer;
                        }}">
            @if($project->type === 'offer-the-price')
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
            @endif
            @if($project->type === 'fixed-price')
                <div x-init="offerPrice = @js($project->price)"></div>
            @endif

            <div class="text-left">
                <button x-data type="button" @click="
                                        let offer = parseInt(String(offerPrice).replace(/\s+/g, ''))
                                        let offerFormated = offerPrice
                                        if(Number.isNaN(offer) || offer < minPrice) {
                                            alert('Nabídněte cenu minimálně {!! $project->price_text_offer !!} Kč')
                                            return;
                                        }
                                    $dispatch('open-modal', {name: 'send-offer', offer: offer, offerFormated: formatMoney(offerPrice)})
                                "
                        class="font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[350px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mb-[25px] disabled:grayscale"
                        :disabled="(Number.isNaN(parseInt(String(offerPrice).replace(/\s+/g, ''))) || parseInt(String(offerPrice).replace(/\s+/g, '')) < minPrice)">
                    @if($project->type === 'fixed-price')
                        Koupit hned
                    @elseif($project->type === 'offer-the-price')
                        Nabídnout
                    @else
                        -
                    @endif
                </button>
            </div>
        </div>

        <x-modal name="send-offer">
            <div class="p-[40px_10px] tablet:p-[50px_40px] text-center" x-data="{
                            loaderShow: false,
                            projectId: @js($project->id),
                            async addOffer(offer) {
                                this.loaderShow = true;
                                await fetch('/projekty/add-offer', {
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

                <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
                     @click="$dispatch('close')"
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
                                <span class="font-Spartan-SemiBold">Podáním nabídky se určuje pořadí projeveného zájmu o koupi.</span>
                                Aby vaše nabídka byla platná, je třeba uhradit <span class="font-Spartan-SemiBold">jistotu</span>,
                                jejíž výše je uvedena u projektu.
                            </p>
                            <p class="mb-[10px]">
                                Po potvrzení vašeho zájmu podáním nabídky vás budeme kontaktovat a
                                zašleme
                                vám
                                instrukce k úhradě jistoty.
                            </p>
                            <p>
                                Jistota musí být připsána na náš účet nejpozději do dvou pracovních dní
                                od
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
                >
                    @if($project->type === 'fixed-price')
                        Koupit hned
                    @elseif($project->type === 'offer-the-price')
                        Podat nabídku
                    @else
                        -
                    @endif
                </button>

                <div id="loader" x-show="loaderShow" x-cloak>
                    <span class="loader"></span>
                </div>
            </div>
        </x-modal>
    @endif
@else
    <div class="grid gap-x-[20px] mb-[25px]
                    grid-cols-1
                    laptop:grid-cols-2
                    ">
        <div>
            <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">
                Abyste mohli vidět všechny údaje o projektu, musíte v “Nastavení účtu” přidat typ účtu “Účet
                investora” a projít procesem ověření.
            </div>
        </div>
        <div class="text-center laptop:text-right">
            <a href="{{ route('profile.edit', ['add' => 'investor']) }}"
               class="text-center inline-block self-center font-Spartan-SemiBold bg-app-green text-white text-[18px] h-[60px] leading-[60px] w-[200px] rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                        mt-[15px]
                     laptop:mt-0">
                Přidat typ účtu
            </a>
        </div>
    </div>
@endif

@if($project->type === 'offer-the-price' || $project->type === 'auction')
    <div class="font-Spartan-SemiBold text-[13px] leading-[29px] mb-[20px] pl-[40px] text-app-[#414141] relative {{ $project->offersCountAll() ? 'cursor-pointer' : '' }}
                    after:absolute after:bg-[url('/resources/images/ico-user.svg')] after:top-[6px] after:left-0 after:w-[15px] after:h-[15px] after:bg-no-repeat"
    >
        {{ $project->offersCountAll() }}

        @if($project->type === 'auction')
            @if($project->offersCountAll() > 0 && $project->offersCountAll() < 5)
                přihazující
            @else
                přihazujících
            @endif
        @else
            @if($project->offersCountAll() > 0 && $project->offersCountAll() < 5)
                nabízející
            @else
                nabízejících
            @endif
        @endif
    </div>
@endif
