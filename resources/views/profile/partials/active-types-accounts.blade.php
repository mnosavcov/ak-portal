<section x-data="{
        confirmRemove: true,
        confirmAdd: true,
        change: false,
        types: @js(\App\Services\AccountTypes::getTypes()),
        user: @js($user),
        showBtn() {
            return !(this.user.investor && this.user.advertiser && this.user.real_estate_broker)
        },
        removeType(type) {
            exists = false;
            Object.entries(this.types).forEach(([key, value]) => {
                if(type === key) {
                    return;
                }
                if(this.user[key] === 1) {
                    exists = true;
                }
            });

            if(!exists) {
                alert(@js(__('Tento typ účtu nemůžete zrušit, jelikož musíte mít vždy aktivní alespoň jeden. Pokud nechcete využívat žádný typ účtu, můžete zrušit celý účet v oddílu &quot;Přihlašovací a kontaktní údaje&quot;.')));
                return;
            }

            if(this.confirmRemove) {
                if(!confirm(@js(__('Pokud budete chtít znovu nastavit tento typ účtu, bude potřeba znovu ověřit účet. Opravdu chcete tento typ účtu zrušit?')))) {
                    return;
                }
            }

            this.confirmRemove = false;
            this.user[type] = false;
            this.setAccountTypes(type);
{{--            this.change = true;--}}
        },
        addType(type) {
            if(this.confirmAdd) {
                if(!confirm(@js(__('Po přidání nového typu účtu budeme ověřovat, zda je váš zájem o jeho využívání oprávněný. Opravdu chcete tento typ účtu přidat?')))) {
                    return;
                }
            }

            this.confirmAdd = false;
            this.user[type] = true;
            this.setAccountTypes(type);
{{--            this.change = true;--}}

            if(!this.showBtn()) {
                $nextTick(() => {
                    this.$dispatch('close-modal', 'add-account-types')
                });
            }
        },
        async setAccountTypes(type) {
            await fetch('/profil/set-account-types', {
                method: 'POST',
                body: JSON.stringify({
                    data: {
                        type: type,
                        investor: (this.user.investor ? 1 : 0),
                        more_info_investor: (this.user.investor ? this.user.more_info_investor : ''),
                        advertiser: (this.user.advertiser ? 1 : 0),
                        more_info_advertiser: (this.user.advertiser ? this.user.more_info_advertiser : ''),
                        real_estate_broker: (this.user.real_estate_broker ? 1 : 0),
                        more_info_real_estate_broker: (this.user.real_estate_broker ? this.user.more_info_real_estate_broker : ''),
                    }
                }),
                headers: {
                    'Content-type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
            }).then((response) => response.json())
                .then((data) => {
                    if (data.status === 'ok') {
                        this.user = data.user
                        this.$nextTick(() => {
                            this.$dispatch('close-modal', 'add-account-types')
                        });
                        return;
                    }

                    alert(@js(__('Chyba nastavení typu účtu')))
                })
                .catch((error) => {
                    alert(@js(__('Chyba nastavení typu účtu')))
                });
        },
        typeStatusClass(index, item) {
            if(this.user.check_status === 'not_verified') {
                if(item === 'color') {
                    return 'text-app-orange'
                }
                if(item === 'text') {
                    return @js(__('Čekáme, až ověříte svou totožnost a obhájíte oprávněnost svého zájmu o využití tohoto typu účtu.'))
                }
            } else if(this.user[index + '_status'] === 'verified') {
                if(item === 'color') {
                    return 'text-app-green'
                }
                if(item === 'text') {
                    return @js(__('Ověřený typ účtu'))
                }
            } else if(this.user[index + '_status'] === 'denied') {
                if(item === 'bool') {
                    return false;
                }
                if(item === 'color') {
                    return 'text-app-red'
                }
                if(item === 'text') {
                    return @js(__('Účet nebyl úspěšně ověřen a neobdrželi jste přístup'))
                }
            } else if(this.user[index + '_status'] === 're_verified') {
                if(item === 'color') {
                    return 'text-app-orange'
                }
                if(item === 'text') {
                    return @js(__('Některé funkce účtu mohou být omezeny – čekáte na ověření po aktualizaci osobních údajů'))
                }
            } else {
                if(item === 'color') {
                    return 'text-app-orange'
                }
                if(item === 'text') {
                    return @js(__('Čekáte na ověření účtu'))
                }
            }

            if(item === 'bool') {
                return true;
            }
        }
    }">
    <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">
        <header id="aktivni-typy-uctu">
            <h2 class="mb-[30px]">{{ __('Aktivní typy účtů') }}</h2>
        </header>

        <div x-cloak x-collapse x-show="showBtn()">
            <button type="submit"
                    @click.prevent="
                        user.open_investor = false;
                        user.open_advertiser = false;
                        user.open_real_estate_broker = false;
                        user.more_info_investor = '';
                        user.more_info_advertiser = '';
                        user.more_info_real_estate_broker = '';
                        $dispatch('open-modal', 'add-account-types');
                    "
                    class="mb-[30px] tablet:mb-[40px] laptop:mb-[50px] col-span-full leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"
            >
                {{ __('+ Přidat typ účtu') }}
            </button>
        </div>

        <div
            class="h-[50px] leading-[50px] bg-[#5E6468] text-white px-[25px] font-Spartan-Bold text-[13px] mb-[25px] grid tablet:grid-cols-[2fr_2fr_1fr]">
            <div>{{ __('Typ účtu') }}</div>
            <div>{{ __('Stav ověření') }}</div>
        </div>

        <div class="grid gap-y-[25px]">
            <template x-for="(type, index) in types" :key="index">
                <div x-cloak x-show="user[index]" x-collapse>
                    <div
                        class="grid laptop:grid-cols-[2fr_2fr_1fr] gap-y-[15px] gap-x-[50px] border border-[#D9E9F2] rounded-[3px] px-[25px] py-[20px]">
                        <div
                            class="font-Spartan-Regular text-[13px] max-laptop:order-1 text-[#454141] leading-[22px] max-laptop:text-center"
                            x-text="type.title + ' ' + type.short"
                        >
                        </div>

                        <div
                            class="font-Spartan-Regular text-[13px] max-laptop:order-1 leading-[22px] max-laptop:text-center"
                            :class="typeStatusClass(index, 'color')"
                            x-text="typeStatusClass(index, 'text')"
                        >
                        </div>

                        <div class="text-right w-full max-laptop:text-center"
                             :class="{'order-last': !typeStatusClass(index, 'bool')}"
                        >
                            <template x-if="typeStatusClass(index, 'bool')">
                                <button @click="removeType(index)"
                                        class="inline-block font-Spartan-SemiBold text-[13px] text-app-red leading-[22px]">
                                    {!! __('Zrušit&nbsp;typ&nbsp;účtu') !!}
                                </button>
                            </template>
                            <template x-if="!typeStatusClass(index, 'bool')">
                                <div
                                    class="text-center laptop:text-left inline-block font-Spartan-Regular text-[13px] text-[#454141] leading-[22px]">
                                    {{ __('Pokud s rozhodnutím nesouhlasíte, můžete se vůči němu písemně odvolat na') }} <a
                                        href="mailto:{{ __('info@pvtrusted_cz') }}" class="text-app-blue">{{ __('info@pvtrusted_cz') }}</a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <x-modal name="add-account-types">
        <div class="relative p-[50px_15px_30px]">
            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
                 class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

            <div class="grid gap-y-[15px]">
                <h2 class="mb-[15px] text-center">{{ __('Přidání typu účtu') }}</h2>

                <template x-for="(type, index) in types" :key="index">
                    <div x-cloak x-show="!user[index]" x-collapse>
                        <div
                            class="grid tablet:grid-cols-[minmax(100px,900px)_1fr] gap-y-[15px] gap-x-[50px]
                         border border-[#D9E9F2] rounded-[3px] px-[25px] py-[20px]">
                            <div
                                class="font-Spartan-Regular text-[13px] max-tablet:order-0 text-[#454141] leading-[22px] max-tablet:text-center"
                                x-text="type.title + ' ' + type.short"
                            >
                            </div>
                            <div class="text-right w-full max-tablet:text-center">
                                <button
                                    @click="user['open_' + index] = !user['open_' + index]"
                                    class="inline-block font-Spartan-SemiBold text-[13px] text-app-green leading-[22px]"
                                    :class="{'text-app-red': user['open_' + index]}"
                                    x-html="user['open_' + index] ? @js(__('Zrušit')) : @js(__('Přidat&nbsp;typ&nbsp;účtu'))">
                                </button>
                            </div>

                            <div x-show="index === 'investor' && user.open_investor" class="contents">
                                <x-input-label for="more_info_investor" class="col-span-full">
                                    {{ __('Za jakým účelem či účely chcete náš portál využívat jako') }} <span
                                        class="text-app-orange">{{ __('investor') }}</span>
                                    {{ __('(jste zájemce o koupi, nebo ho zastupujete)? Upřesněte své záměry.') }}
                                </x-input-label>
                                <x-textarea-input class="col-span-full h-[7rem]"
                                                  x-model="user.more_info_investor"></x-textarea-input>
                                <button
                                    @click="
                                    if(user.more_info_investor === null || user.more_info_investor.trim().length < 5) {
                                        alert(@js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako &quot;investor&quot; alespoň 5 znaků.')));
                                    } else {
                                        addType(index)
                                    }"
                                    class="justify-self-center px-[35px] col-span-full mt-[15px] text-center leading-[50px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
                                    {{ __('Přidat') }}
                                </button>
                            </div>

                            <div x-show="index === 'advertiser' && user.open_advertiser" class="contents">
                                <x-input-label for="more_info_advertiser" class="col-span-full">
                                    {{ __('Za jakým účelem či účely chcete náš portál využívat jako') }} <span
                                        class="text-app-orange">{{ __('nabízející') }}</span>
                                    {{ __('(jste vlastník projektu, nebo jednáte jeho jménem)? Upřesněte své záměry.') }}
                                </x-input-label>
                                <x-textarea-input class="col-span-full h-[7rem]"
                                                  x-model="user.more_info_advertiser"></x-textarea-input>
                                <button
                                    @click="
                                    if(user.more_info_advertiser === null || user.more_info_advertiser.trim().length < 5) {
                                        alert(@js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako &quot;nabízejí&quot; alespoň 5 znaků.')));
                                    } else {
                                        addType(index)
                                    }"
                                    class="justify-self-center px-[35px] col-span-full mt-[15px] text-center leading-[50px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
                                    {{ __('Přidat') }}
                                </button>
                            </div>

                            <div x-show="index === 'real_estate_broker' && user.open_real_estate_broker"
                                 class="contents">
                                <x-input-label for="more_info_real_estate_broker" class="col-span-full">
                                    {{ __('Za jakým účelem či účely chcete náš portál využívat jako') }} <span
                                        class="text-app-orange">{{ __('realitní makléř') }}</span>
                                    {{ __('(zprostředkováváte prodej projektu například na základě smlouvy o realitním zprostředkování)? Upřesněte své záměry.') }}
                                </x-input-label>
                                <x-textarea-input class="col-span-full h-[7rem]"
                                                  x-model="user.more_info_real_estate_broker"></x-textarea-input>
                                <button
                                    @click="
                                    if(user.more_info_real_estate_broker === null || user.more_info_real_estate_broker.trim().length < 5) {
                                        alert(@js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako &quot;realitní makléř&quot; alespoň 5 znaků.')));
                                    } else {
                                        addType(index)
                                    }"
                                    class="justify-self-center px-[35px] col-span-full mt-[15px] text-center leading-[50px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block">
                                    {{ __('Přidat') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        @if(session('add') === 'investor')
            <div x-init="
                const element = document.getElementById('aktivni-typy-uctu');
                const offsetTop = element.offsetTop;

                window.scrollTo({
                    top: offsetTop - 100,
                    behavior: 'smooth'
                });
                $dispatch('open-modal', 'add-account-types');
                user['open_investor'] = true;
                "></div>
        @endif
        @if(session('add') === 'no-investor')
            <div x-init="
                const element = document.getElementById('aktivni-typy-uctu');
                const offsetTop = element.offsetTop;

                window.scrollTo({
                    top: offsetTop - 100,
                    behavior: 'smooth'
                });
                $dispatch('open-modal', 'add-account-types');
                "></div>
        @endif
    </x-modal>
</section>
