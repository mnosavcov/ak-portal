<section x-data="{
        confirmRemove: true,
        confirmAdd: true,
        change: false,
        types: @js(\App\Services\AccountTypes::TYPES),
        user: @js($user),
        showBtn() {
            return !(this.user.investor && this.user.advertiser && this.user.real_estate_broker)
        },
        removeType(type) {
            if(this.confirmRemove) {
                if(!confirm('Pokud budete chtít znovu nastavit tento typ účtu, bude potřeba znovu ověřit účet. Opravdu chcete tento typ účtu zrušit?')) {
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
                if(!confirm('Po přidání nového typu účtu bude potřeba znovu ověřit účet. Opravdu chcete tento účet přidat?')) {
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
                        more_info_investor: this.user.more_info_investor,
                        advertiser: (this.user.advertiser ? 1 : 0),
                        more_info_advertiser: this.user.more_info_advertiser,
                        real_estate_broker: (this.user.real_estate_broker ? 1 : 0),
                        more_info_real_estate_broker: this.user.more_info_real_estate_broker,
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

                    alert('Chyba nastavení typu účtu')
                })
                .catch((error) => {
                    alert('Chyba nastavení typu účtu')
                });
        },
        typeStatusClass(index, item) {
            if(this.user.check_status === 'not_verified') {
                if(item === 'color') {
                    return 'text-app-orange'
                }
                if(item === 'text') {
                    return 'Čekáme, až zadáte údaje k ověření tohoto typu účtu'
                }
            } else if(this.user[index + '_status'] === 'verified') {
                if(item === 'color') {
                    return 'text-app-green'
                }
                if(item === 'text') {
                    return 'Ověřený typ účtu'
                }
            } else if(this.user[index + '_status'] === 'denied') {
                if(item === 'color') {
                    return 'text-app-red'
                }
                if(item === 'text') {
                    return 'Účet nebyl úspěšně ověřen a neobdrželi jste přístup'
                }
            } else if(this.user[index + '_status'] === 're_verified') {
                if(item === 'color') {
                    return 'text-app-orange'
                }
                if(item === 'text') {
                    return 'Některé funkce účtu mohou být omezeny – čekáte na ověření po aktualizaci osobních údajů'
                }
            } else {
                if(item === 'color') {
                    return 'text-app-orange'
                }
                if(item === 'text') {
                    return 'Čekáte na ověření'
                }
            }
        }
    }">
    <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">
        <header>
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
                + Přidat typ účtu
            </button>
        </div>

        <div
            class="h-[50px] leading-[50px] bg-[#5E6468] text-white px-[25px] font-Spartan-Bold text-[13px] mb-[25px] grid tablet:grid-cols-[2fr_2fr_1fr]">
            <div>Typ účtu</div>
            <div>Stav ověření</div>
        </div>

        <div class="grid gap-y-[25px]">
            <template x-for="(type, index) in types" :key="index">
                <div x-cloak x-show="user[index]" x-collapse>
                    <div
                        class="grid tablet:grid-cols-[2fr_2fr_1fr] gap-y-[15px] gap-x-[50px] border border-[#D9E9F2] rounded-[3px] px-[25px] py-[20px]">
                        <div
                            class="font-Spartan-Regular text-[13px] max-tablet:order-1 text-[#454141] leading-[22px] max-tablet:text-center"
                            x-text="type.title + ' ' + type.short"
                        >
                        </div>

                        <div
                            class="font-Spartan-Regular text-[13px] max-tablet:order-1 leading-[22px] max-tablet:text-center"
                            :class="typeStatusClass(index, 'color')"
                            x-text="typeStatusClass(index, 'text')"
                        >
                        </div>

                        <div class="text-right w-full max-tablet:text-center">
                            <button @click="removeType(index)"
                                    class="inline-block font-Spartan-SemiBold text-[13px] text-app-red leading-[22px]">
                                Zrušit&nbsp;typ&nbsp;účtu
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

{{--        <button type="submit" x-cloak x-show="change" x-collapse--}}
{{--                @click="setAccountTypes()"--}}
{{--                class="mt-[30px] col-span-full leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"--}}
{{--        >--}}
{{--            Uložit změny--}}
{{--        </button>--}}
    </div>

    <x-modal name="add-account-types">
        <div class="relative p-[50px_15px_30px]">
            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
                 class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

            <div class="grid gap-y-[15px]">
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
                            <div class="text-right w-full max-tablet:text-center" x-data="{openMoreInfo: ''}">
                                <button
                                    @click="user['open_' + index] = !user['open_' + index]"
                                    class="inline-block font-Spartan-SemiBold text-[13px] text-app-green leading-[22px]">
                                    Přidat&nbsp;typ&nbsp;účtu
                                </button>
                            </div>

                            <div x-show="index === 'investor' && user.open_investor" class="contents">
                                <x-input-label for="more_info_investor" class="col-span-full">
                                    Za jakým účelem či účely chcete náš portál využívat jako <span
                                        class="text-app-orange">investor</span>
                                    (jste zájemce o koupi, nebo ho zastupujete)? Upřesněte své záměry.
                                </x-input-label>
                                <x-textarea-input class="col-span-full h-[7rem]"
                                                  x-model="user.more_info_investor"></x-textarea-input>
                                <button
                                    @click="addType(index)"
                                    class="col-span-full inline-block font-Spartan-SemiBold text-[13px] text-app-green leading-[22px] disabled:grayscale"
                                    :disabled="user.more_info_investor === null || user.more_info_investor.trim().length < 5">
                                    Přidat
                                </button>
                            </div>

                            <div x-show="index === 'advertiser' && user.open_advertiser" class="contents">
                                <x-input-label for="more_info_advertiser" class="col-span-full">
                                    Za jakým účelem či účely chcete náš portál využívat jako <span
                                        class="text-app-orange">nabízející</span>
                                    (jste vlastník projektu, nebo jednáte jeho jménem)? Upřesněte své záměry.
                                </x-input-label>
                                <x-textarea-input class="col-span-full h-[7rem]"
                                                  x-model="user.more_info_advertiser"></x-textarea-input>
                                <button
                                    @click="addType(index)"
                                    class="col-span-full inline-block font-Spartan-SemiBold text-[13px] text-app-green leading-[22px] disabled:grayscale"
                                    :disabled="user.more_info_advertiser === null || user.more_info_advertiser.trim().length < 5">
                                    Přidat
                                </button>
                            </div>

                            <div x-show="index === 'real_estate_broker' && user.open_real_estate_broker"
                                 class="contents">
                                <x-input-label for="more_info_real_estate_broker" class="col-span-full">
                                    Za jakým účelem či účely chcete náš portál využívat jako <span
                                        class="text-app-orange">realitní makléř</span> (zprostředkováváte prodej
                                    projektu například na základě smlouvy o realitním zprostředkování)? Upřesněte své
                                    záměry.
                                </x-input-label>
                                <x-textarea-input class="col-span-full h-[7rem]"
                                                  x-model="user.more_info_real_estate_broker"></x-textarea-input>
                                <button
                                    @click="addType(index)"
                                    class="col-span-full inline-block font-Spartan-SemiBold text-[13px] text-app-green leading-[22px] disabled:grayscale"
                                    :disabled="user.more_info_real_estate_broker === null || user.more_info_real_estate_broker.trim().length < 5">
                                    Přidat
                                </button>
                            </div>

                        </div>
                    </div>
                </template>
            </div>
        </div>
    </x-modal>
</section>
