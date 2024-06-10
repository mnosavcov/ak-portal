@if(!$project->isVerified())
    <div class="grid gap-x-[20px] mb-[25px] grid-cols-1 laptop:grid-cols-2">
        @if(auth()->guest())
            <div>
                <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">Pro zaslání nabídky
                    a
                    zobrazení
                    všech údajů se musíte přihlásit a mít ověřený účet.
                </div>
                <div class="font-Spartan-Regular text-[13px] leading-[22px] text-[#414141]">
                    Nemáte účet?
                    <a href="{{ route('register') }}" class="font-Spartan-Bold text-app-blue">Registrujte
                        se</a>
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
                <div class="font-Spartan-Bold text-[13px] leading-[22px] text-[#414141]">Pro zaslání nabídky
                    a
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
@else
    @if(auth()->user()->investor)
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
                    <button x-data type="button" @click.next="
                                        let offer = parseInt(String(offerPrice).replace(/\s+/g, ''))
                                        let offerFormated = offerPrice
                                        if(Number.isNaN(offer) || offer < minPrice) {
                                            alert('Nabídněte cenu minimálně {!! $project->price_text_offer !!}')
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
                                x-text="inputData.offerFormated">
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
                                    Po potvrzení vašeho zájmu podáním nabídky vás budeme kontaktovat a
                                    zašleme
                                    vám
                                    instrukce k úhradě jistiny.
                                </p>
                                <p>
                                    Jistina musí být připsána na náš účet nejpozději do dvou pracovních dní
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
                    Pro zaslání nabídky a musíte nastavit typ účtu jako investor.
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
