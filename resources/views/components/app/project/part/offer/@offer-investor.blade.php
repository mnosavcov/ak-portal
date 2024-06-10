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
                    Zaplatili jste jistinu
                </div>
                <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">
                    Vaše nabídka je vítězná, vyčkejte na vyzvání k uzavření Kupní smlouvy
                </div>
            @endif
        @elseif($offer->principal_paid)
            @if($project->type === 'offer-the-price')
                <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green">
                    Jistinu jste zaplatili
                </span>
            @endif
            @if($project->type === 'fixed-price')
                <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px]">
                    Zaplatili jste jistinu
                </div>
                @if(\App\Models\ProjectShow::where('project_id', $project->id)->where('winner', 1)->get()->count())
                    <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-red">
                        Vaše nabídka nebyla akceptována, jelikož jistinu zaplatil i investor, který projevil zájem před
                        vámi.
                    </div>
                @else
                    <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-orange">
                        Čeká se, zda jistinu zaplatí investor, který projevil zájem o projekt před vámi.
                    </div>
                @endif
            @endif
        @else
            <span class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-orange">
                Čeká se, až uhradíte jistinu
            </span>
        @endif
    </div>
@endif
