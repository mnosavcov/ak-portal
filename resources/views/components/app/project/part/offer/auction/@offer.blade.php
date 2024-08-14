<div x-init="
    winnerAuction[@js($offer->id)] = false;
    nowinnerAuctionRed[@js($offer->id)] = false;
    nowinnerAuction[@js($offer->id)] = false;
"></div>

@if($userType === 'advertiser' || $userType === 'superadmin')
    @if($iteration === 1 && $offer->project->status === 'finished')
        <div x-init="winnerAuction[@js($offer->id)] = true;"></div>
    @endif
@elseif($user)
    @if($iteration === 1)
        <div x-init="winnerAuction[@js($offer->id)] = true;"></div>
    @elseif($myFirstBid && $offer->project->status === 'finished')
        <div x-init="nowinnerAuctionRed[@js($offer->id)] = true;"></div>
    @else
        <div x-init="nowinnerAuction[@js($offer->id)] = true;"></div>
    @endif
@else
    @if($iteration === 1)
        {{--        <div x-init="winnerAuction[@js($offer->id)] = true;"></div>--}}
    @endif
@endif
<div
    x-data="{principal_paid: @js($offer->project->shows()->where('user_id', $offer->user_id)->first()->principal_paid ? true : false)}"
    class="space-y-[10px] p-[15px] tablet:p-[20px] {{ $offer->winner ? 'border-[3px] border-app-green' : 'border border-[#D9E9F2]' }} rounded-[3px] mb-[25px] tablet:mb-[35px]"
    :class="{
                '!border-[3px] !border-app-green': winnerAuction[@js($offer->id)],
                '!border-[3px] !border-app-orange': nowinnerAuction[@js($offer->id)],
                '!border-[3px] !border-app-red': nowinnerAuctionRed[@js($offer->id)],
            }">
    <div
        class="font-Spartan-Bold text-[#31363A] text-[16px] leading-[24px] tablet:text-[18px] tablet:leading-[30px]">
        {{ $title }}
    </div>

    <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
        <div class="font-Spartan-SemiBold text-[14px]">Čas uskutečnění podání:</div>
        <div
            class="font-Spartan-Regular text-[14px]">{{ \Carbon\Carbon::parse($offer->offer_time)->format('d.m.Y H:i:s') }}</div>
    </div>

    <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
        <div class="font-Spartan-SemiBold text-[14px]">Výše podání:</div>
        <div
            class="font-Spartan-Regular text-[14px]">{{ $offer->offer_amount_text }}
        </div>
    </div>

    @if(
        $userType !== 'investor'
        || ($userType === 'investor' && $user)
    )
        <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
            <div class="font-Spartan-SemiBold text-[14px]">Složena jistota:</div>
            <div class="font-Spartan-Regular text-[14px]">
                <span class="text-app-green" x-cloak x-show="principal_paid">ano</span>
                <span class="text-app-red" x-cloak x-show="!principal_paid">ne</span>
            </div>
        </div>
    @endif

    @if(!$user && $userType !== 'investor')
        <div class="pt-[10px]">
            <div
                class="p-[20px_15px] bg-[#F8F8F8] rounded-[3px]">
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    Identifikace investora je dostupná jen klientům, kteří s provozovatelem portálu PVtrusted uzavřeli
                    smlouvu o realitním zprostředkování v režimu výhradního zastoupení. Pro zobrazení těchto údajů nás
                    kontaktujte s žádostí o podepsání dodatku smlouvy ve věci změny nevýhradního zastoupení na
                    zastoupení výhradní.
                </div>
            </div>
        </div>
    @elseif($user)
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

    @if($offer->project->status === 'finished')
        @if($userType === 'advertiser' || $userType === 'superadmin')
            @if($iteration === 1)
                <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px] text-center">
                    Vítězné podání
                </div>
            @endif
        @elseif($iteration === 1 && $user)
            <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px] text-center">
                Vaše podání je vítězné, vyčkejte na výzvu k uzavření Rezervační smlouvy
            </div>
        @elseif($iteration === 1 && !$user)
            <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green mb-[15px] text-center">
                Vítězné podání
            </div>
        @elseif($user && $myFirstBid)
            <div class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-red mb-[15px] text-center">
                Vaše podání není vítězné
            </div>
        @endif
    @endif
</div>
