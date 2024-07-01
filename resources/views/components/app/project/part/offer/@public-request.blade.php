<div
    class="space-y-[10px] p-[15px] tablet:p-[20px] border border-[#D9E9F2] rounded-[3px] mb-[25px] tablet:mb-[35px]">
    <div
        class="font-Spartan-Bold text-[#31363A] text-[16px] leading-[24px] tablet:text-[18px] tablet:leading-[30px]">
        {{ $title }}
    </div>

    <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[5px]">
        <div class="font-Spartan-SemiBold text-[14px]">Čas přidání žádosti:</div>
        <div
            class="font-Spartan-Regular text-[14px]">{{ \Carbon\Carbon::parse($show->details_on_request_time)->format('d.m.Y H:i:s') }}</div>
    </div>

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

        <div class="text-center mt-[15px]"
             x-data="{
                        show: @js($show),
                        async setPublic(access) {
                            if(access && !confirm('Opravdu si přejete povolit přístup? Povolení již nelze vzít zpět.')) {
                                return;
                            }
                            if(!access && !confirm('Opravdu si přejete investorovi zamítnout plný přístup ke všem informacím o projektu? Po zamítnutí můžete své stanovisko změnit a přístup dodatečně povolit.')) {
                                return;
                            }

                            await fetch('/projects/set-public', {
                                method: 'POST',
                                body: JSON.stringify({
                                    showId: @js($show->id),
                                    access: (access ? 1 : 0),
                                }),
                                headers: {
                                    'Content-type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                            }).then((response) => response.json())
                                .then((data) => {
                                    if(data.status === 'success') {
                                        this.show = data.show;
                                        return;
                                    }

                                    alert('Chyba nastavení přístupu')
                                })
                                .catch((error) => {
                                    alert('Chyba nastavení přístupu')
                                });
                        }
                    }"
        >

             <span x-show="show.details_on_request === 999"
                 class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-green max-w-[350px] inline-block">
                 Plný přístup jste povolili
            </span>

            <button x-show="show.details_on_request === 1 || show.details_on_request === -1"
                class="font-Spartan-Regular bg-app-green text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "
                @click="setPublic(true)"
            >
                Povolit plný přístup
            </button>

            <span x-show="show.details_on_request === -1"
                  class="font-Spartan-SemiBold text-[15px] tablet:text-[18px] text-app-red max-w-[350px] inline-block mt-[15px]">
                Zamítli jste plný přístup
            </span>

            <button x-show="show.details_on_request === 1"
            class="font-Spartan-Regular bg-app-red text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] mt-[10px]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-end
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px]
                             "
                @click="setPublic(false)"
            >
                Zamítnout plný přístup
            </button>
        </div>
    </div>
</div>
