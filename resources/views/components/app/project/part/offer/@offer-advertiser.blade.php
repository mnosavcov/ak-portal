@if(
    ($type ?? null) === 'advertiser'
    && ($offer->winner || $project->isStateEvaluation())
)
    <div class="col-span-2 text-center pt-[15px] tablet:pt-[20px]"
         x-show="winner === @js($offer->id) || winner === null">
        @if($offer->winner)
            <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green max-w-[350px] inline-block">
                @if($project->type === 'offer-the-price')
                    {{ __('Nabídku jste zvolili jako vítěznou') }}
                @endif
                @if($project->type === 'fixed-price')
                    {{ __('Investor zaplatil jistotu a jeho nabídka byla akceptována') }}
                @endif
            </span>
        @elseif($offer->principal_paid && $project->isStateEvaluation())
            <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green" x-cloak
                  x-show="winner === @js($offer->id)">{{ __('Nabídku jste zvolili jako vítěznou') }}</span>
            <button
                    x-show="winner === null"
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
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "
                    @click="pickAWinner()"
            >
                {{ __('Akceptovat nabídku') }}
            </button>
        @elseif($project->isStateEvaluation())
            <button class="cursor-text font-Spartan-Regular bg-[#ACACAC] text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mt-[-10px]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             ">
                {{ __('Akceptovat nabídku') }}
            </button>
            <div class="font-Spartan-Regular text-[11px] text-app-orange mt-[20px]">
                {{ __('Nabídku bude možné akceptovat, až investor složí jistotu.') }}
            </div>
        @endif
    </div>
@endif
