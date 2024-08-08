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
            <button type="button"
                    class="mt-[35px] justify-self-start col-span-2 font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] max-tablet:mb-[15px]
                            h-[50px] leading-[50px] w-full text-[14px]
                            tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px]
                            "
                    @click="$dispatch('open-modal', 'pay-principal')"
            >
                Zaplatit jistotu
            </button>

            @include('components.app.project.part.offer.@@pay-principal-check')
        @endif
    </div>

    @include('components.app.project.part.offer.@@pay-principal-modal')
@endif
