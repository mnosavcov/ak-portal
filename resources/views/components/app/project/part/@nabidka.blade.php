<div x-data="{principal_paid: @js($offer->principal_paid ? true : false)}"
     class="space-y-[10px] p-[15px] tablet:p-[20px] {{ $offer->winner ? 'border-[3px] border-app-green' : 'border border-[#D9E9F2]' }} rounded-[3px] mb-[25px] tablet:mb-[35px]"
        :class="{'!border-[3px] !border-app-green': winner === @js($offer->id)}">
    <div
        class="font-Spartan-Bold text-[#31363A] text-[16px] leading-[24px] tablet:text-[18px] tablet:leading-[30px]">
        {{ $title }}
    </div>

    <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
        <div class="font-Spartan-SemiBold text-[14px]">Čas přidání nabídky:</div>
        <div
            class="font-Spartan-Regular text-[14px]">{{ \Carbon\Carbon::parse($offer->offer_time)->format('d.m.Y H:i:s') }}</div>
    </div>

    <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
        <div class="font-Spartan-SemiBold text-[14px]">Výše nabídky:</div>
        <div
            class="font-Spartan-Regular text-[14px]">{{ number_format($offer->price ?? 0, 0, '.', ' ') }}
            Kč
        </div>
    </div>

    <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
        <div class="font-Spartan-SemiBold text-[14px]">Složena jistina:</div>
        <div class="font-Spartan-Regular text-[14px]">
            <span class="text-app-green" x-cloak x-show="principal_paid">ano</span>
            <span class="text-app-red" x-cloak x-show="!principal_paid">ne</span>
        </div>
    </div>

    @if(!$user)
        <div class="pt-[10px]">
            <div
                class="p-[20px_15px] bg-[#F8F8F8] rounded-[3px]">
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    Identifikace investora je dostupná jen klientům, kteří s provozovatelem portálu PVtrusted uzavřeli
                    smlouvu u realitním zprostředkování v režimu výhradního zastoupení. Pro zobrazení těchto údajů nás
                    kontaktujte s žádostí o podepsání dodatku smlouvy ve věci změny nevýhradního zastoupení na
                    zastoupení výhradní.
                </div>
            </div>
        </div>
    @else
        <div class="pt-[10px]">
            <div
                class="p-[20px_15px] bg-[#F8F8F8] rounded-[3px] grid mobile:grid-cols-[max-content_1fr] gap-x-[35px] mobile:gap-y-[10x]">
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black mobile:col-span-2 mb-[5px]">
                    Kontaktní osoba
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    Jméno a příjmení
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ $user->title_before . ' ' . $user->name . ' ' . $user->surname . ' ' . $user->title_after }}
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    Adresa trvalého bydliště
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ $user->street . ' ' . $user->street_number . ', ' . $user->psc . ', ' . $user->city }}
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    Státní občanství (země)
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ \App\Services\CountryServices::COUNTRIES[$user->country] ?? $user->country }}
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black mobile:col-span-2 mt-[20px] mb-[5px]">
                    Investor
                </div>
                <div
                    class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black mobile: col-span-2">
                    {!! nl2br($user->investor_info) !!}
                </div>
            </div>
        </div>
    @endif

    @if(($type ?? null) === 'investor')
        <div class="col-span-2 text-center pt-[15px] tablet:pt-[20px]">
            @if($offer->winner)
                <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">Nabízející akceptoval vaši nabídku</span>
            @elseif($offer->principal_paid)
                <span
                    class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">Jistinu jste zaplatili</span>
            @else
                <span
                    class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-red">Čeká se, až uhradíte jistinu</span>
            @endif
        </div>
    @endif

    @if(
        ($type ?? null) === 'advertiser'
        && ($offer->winner || $project->isStateEvaluation())
    )
        <div class="col-span-2 text-center pt-[15px] tablet:pt-[20px]" x-show="winner === @js($offer->id) || winner === null">
            @if($offer->winner)
                <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">Nabídku jste zvolili jako vítěznou</span>
            @elseif($offer->principal_paid && $project->isStateEvaluation())
                <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green" x-cloak x-show="winner === @js($offer->id)">Nabídku jste zvolili jako vítěznou</span>
                <button
                    x-show="winner === null"
                    x-data="{
                        async pickAWinner() {
                            if(!confirm('Opravdu si přejete vybrat tohoho investora?')) {
                                return;
                            }

                            await fetch('/projects/pick-a-winner', {
                                method: 'POST',
                                body: JSON.stringify({
                                    offerId: @js($offer->id)
                                }),
                                headers: {
                                    'Content-type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                            }).then((response) => response.json())
                                .then((data) => {
                                    if(data.status === 'success') {
                                        winner = data.value;
                                        return;
                                    }

                                    alert('Chyba výběru vítěze')
                                })
                                .catch((error) => {
                                    alert('Chyba výběru vítěze')
                                });
                        }
                    }"

                    class="font-Spartan-Regular bg-app-green text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mt-[-10px]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "
                    @click="pickAWinner()"
                >
                    Akceptovat nabídku
                </button>
            @elseif($project->isStateEvaluation())
                <button class="cursor-text font-Spartan-Regular bg-[#ACACAC] text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mt-[-10px]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             ">
                    Akceptovat nabídku
                </button>
                <div class="font-Spartan-Regular text-[11px] text-app-orange mt-[20px]">
                    Nabídku bude možné akceptovat, až investor složí jistinu.
                </div>
            @endif
        </div>
    @endif

    @if(($type ?? null) === 'superadmin')
        <div class="col-span-2 text-center pt-[15px] tablet:pt-[20px]">
            @if($offer->winner)
                <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px] inline-block">Nabídka je zvolena jako vítězná</span>
            @endif

            <button
                x-data="{
                        async setPrincipalPaid() {
                            await fetch('/admin/projects/{{ $offer->id }}/set-principal-paid', {
                                method: 'POST',
                                body: JSON.stringify({
                                    offerId: @js($offer->id)
                                }),
                                headers: {
                                    'Content-type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                            }).then((response) => response.json())
                                .then((data) => {
                                    if(data.status === 'success') {
                                        principal_paid = (data.value ? true : false)
                                        return;
                                    }

                                    alert('Chyba nastavení zaplacenní jistiny')
                                })
                                .catch((error) => {
                                    alert('Chyba nastavení zaplacenní jistiny')
                                });
                        }
                    }"

                class="font-Spartan-Regular text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mt-[-10px]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "
                :class="{
                    'bg-app-green': principal_paid,
                    'bg-app-red': !principal_paid,
                }"
                @click="setPrincipalPaid()"
                x-text="principal_paid ? 'Jistina je uhrazena' : 'Jistina není uhrazena'"
            >
            </button>
        </div>
    @endif
</div>
