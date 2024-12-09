@if($offer->winner)
    <div x-init="winner = @js($offer->id);"></div>
@endif
<div x-data="{principal_paid: @js($offer->principal_paid ? true : false)}"
     class="space-y-[10px] p-[15px] tablet:p-[20px] {{ $offer->winner ? 'border-[3px] border-app-green' : 'border border-[#D9E9F2]' }} rounded-[3px] mb-[25px] tablet:mb-[35px]"
     :class="{'!border-[3px] !border-app-green': winner === @js($offer->id)}">
    <div
        class="font-Spartan-Bold text-[#31363A] text-[16px] leading-[24px] tablet:text-[18px] tablet:leading-[30px]">
        {{ $title }}
    </div>

    @if($project->type !== 'preliminary-interest')
        <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
            <div class="font-Spartan-SemiBold text-[14px]">{{ __('Čas přidání nabídky') }}:</div>
            <div
                class="font-Spartan-Regular text-[14px]">{{ \Carbon\Carbon::parse($offer->offer_time)->format('d.m.Y H:i:s') }}</div>
        </div>

        <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
            <div class="font-Spartan-SemiBold text-[14px]">{{ __('Výše nabídky') }}:</div>
            <div
                class="font-Spartan-Regular text-[14px]">{{ number_format($offer->price ?? 0, 0, '.', ' ') }}
                {{ __('Kč') }}
            </div>
        </div>

        <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
            <div class="font-Spartan-SemiBold text-[14px]">{{ __('Složena jistota') }}:</div>
            <div class="font-Spartan-Regular text-[14px]">
                <span class="text-app-green" x-cloak x-show="principal_paid">{{ __('ano') }}</span>
                <span class="text-app-red" x-cloak x-show="!principal_paid">{{ __('ne') }}</span>
            </div>
        </div>
    @endif

    @if(!$user)
        <div class="pt-[10px]">
            <div
                class="p-[20px_15px] bg-[#F8F8F8] rounded-[3px]">
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ __('Identifikace investora je dostupná jen klientům, kteří s provozovatelem portálu PVtrusted uzavřeli smlouvu o realitním zprostředkování v režimu výhradního zastoupení. Pro zobrazení těchto údajů nás kontaktujte s žádostí o podepsání dodatku smlouvy ve věci změny nevýhradního zastoupení na zastoupení výhradní.') }}
                </div>
            </div>
        </div>
    @else
        <div class="pt-[10px]">
            <div
                class="p-[20px_15px] bg-[#F8F8F8] rounded-[3px] grid mobile:grid-cols-[max-content_1fr] gap-x-[35px] mobile:gap-y-[10px]">
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black mobile:col-span-2 mb-[5px]">
                    {{ __('Kontaktní osoba') }}
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ __('Jméno a příjmení') }}
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ $user->title_before . ' ' . $user->name . ' ' . $user->surname . ' ' . $user->title_after }}
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ __('Adresa trvalého bydliště') }}
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ $user->street . ' ' . $user->street_number . ', ' . $user->psc . ', ' . $user->city }}
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ __('Státní občanství (země)') }}
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ \App\Services\CountryServices::COUNTRIES[$user->country] ?? $user->country }}
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black mobile:col-span-2 mt-[20px] mb-[5px]">
                    {{ __('Investor') }}
                </div>
                <div
                    class="font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black mobile:col-span-2">
                    {!! nl2br($user->investor_info) !!}
                </div>

                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black mt-[10px]">
                    {{ __('E-mail') }}
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black mt-[10px]">
                    <a href="mailto:{{ $user->email }}"
                       class="underline hover:no-underline text-app-blue">{{ $user->email }}</a>
                </div>
                <div
                    class="font-Spartan-SemiBold text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ __('Telefon') }}
                </div>
                <div
                    class="max-tablet:mb-[15px] font-Spartan-Regular text-[11px] tablet:text-[13px] leading-[24px] text-black">
                    {{ $user->phone_number }}
                </div>
            </div>
        </div>
    @endif

    @include('components.app.project.part.offer.@offer-admin')
    @include('components.app.project.part.offer.@offer-advertiser')
    @include('components.app.project.part.offer.@offer-investor')
</div>
