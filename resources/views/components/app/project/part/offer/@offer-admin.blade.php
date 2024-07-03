@if(($type ?? null) === 'superadmin')
    <div class="col-span-2 text-center pt-[15px] tablet:pt-[20px]">
        <div class="col-span-2 text-center pt-[15px] tablet:pt-[20px]" x-cloak
             x-show="winner === @js($offer->id)">
                <span
                        class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px] inline-block">
                    Nabídka je zvolena jako vítězná
                </span>
        </div>

        @if($project->type==='fixed-price' && $project->isStateEvaluation())
            <button
                    x-show="winner === null && principal_paid" x-cloak x-collapse
                    x-data="{
                    async pickAWinner() {
                        if(!confirm('Opravdu si přejete vybrat tohoho investora?')) {
                            return;
                        }

                        await fetch('/projekty/pick-a-winner', {
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
                         laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px] mb-[15px]
                         "
                    @click="pickAWinner()"
            >
                Akceptovat nabídku
            </button>
        @endif

        <button
                x-data="{
                        async setPrincipalPaid() {
                            await fetch('/admin/projekty/{{ $offer->id }}/set-principal-paid', {
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

                                    alert('Chyba nastavení zaplacenní jistoty')
                                })
                                .catch((error) => {
                                    alert('Chyba nastavení zaplacenní jistoty')
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
                x-text="principal_paid ? 'Jistota je uhrazena' : 'Jistota není uhrazena'"
        >
        </button>
    </div>
@endif
