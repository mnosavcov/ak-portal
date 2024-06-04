<section x-data="{
        confirmRemove: true,
        confirmAdd: true,
        change: false,
        investor: @js((bool)$user['investor']),
        advertiser: @js((bool)$user['advertiser']),
        real_estate_broker: @js((bool)$user['real_estate_broker']),
        showBtn() {
            return !(this.investor && this.advertiser && this.real_estate_broker)
        },
        removeType(type) {
            if(this.confirmRemove) {
                if(!confirm('Pokud budete chtít znovu nastavit tento typ účtu, bude potřeba znovu ověřit účet. Opravdu chcete tento typ účtu zrušit?')) {
                    return;
                }
            }

            this.confirmRemove = false;
            this[type] = false;
            this.change = true;
        },
        addType(type) {
            if(this.confirmAdd) {
                if(!confirm('Po přidání nového typu účtu bude potřeba znovu ověřit účet. Opravdu chcete tento účet přidat?')) {
                    return;
                }
            }

            this.confirmAdd = false;
            this[type] = true;
            this.change = true;

            if(!this.showBtn()) {
                $nextTick(() => {
                    this.$dispatch('close-modal', 'add-account-types')
                });
            }
        },
        async setAccountTypes() {
            await fetch('/profil/set-account-types', {
                method: 'POST',
                body: JSON.stringify({
                    data: {
                        investor: String(this.investor ? 1 : 0),
                        advertiser: String(this.advertiser ? 1 : 0),
                        real_estate_broker: String(this.real_estate_broker ? 1 : 0),
                    }
                }),
                headers: {
                    'Content-type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
            }).then((response) => response.json())
                .then((data) => {
                    if (data.status === 'ok') {
                        window.location.href = '/nastaveni-uctu';
                    }

                    this.errors = data.errors;
                })
                .catch((error) => {
                    alert('Chyba odeslání dat')
                });
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
                    @click.prevent="$dispatch('open-modal', 'add-account-types')"
                    class="mb-[50px] col-span-full leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"
            >
                + Přidat typ účtu
            </button>
        </div>

        <div class="h-[50px] leading-[50px] bg-[#5E6468] text-white px-[25px] font-Spartan-Bold text-[13px] mb-[25px]">
            Typ účtu
        </div>

        <div class="grid gap-y-[25px]">
            @foreach(\App\Services\AccountTypes::TYPES as $index => $type)
                <div x-cloak x-show="{{ $index }}" x-collapse>
                    <div
                        class="grid grid-cols-[minmax(100px,900px)_1fr] gap-x-[50px] border border-[#D9E9F2] rounded-[3px] px-[25px] py-[20px]">
                        <div class="font-Spartan-Regular text-[13px] text-[#454141] leading-[22px]">
                            {{ $type['title'] }}
                            {{ $type['short'] }}
                        </div>
                        <div class="text-right w-full">
                            <button @click="removeType('{{ $index }}')"
                                    class="inline-block font-Spartan-SemiBold text-[13px] text-app-red leading-[22px]">
                                Zrušit&nbsp;typ&nbsp;účtu
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" x-cloak x-show="change" x-collapse
                @click="setAccountTypes()"
                class="mt-[30px] col-span-full leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"
        >
            Uložit změny
        </button>
    </div>

    <x-modal name="add-account-types">
        <div class="relative">
            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
                 class="cursor-pointer w-[20px] h-[20px] float-right relative top-[-20px] tablet:right-[-15px] right-[5px]">

            @foreach(\App\Services\AccountTypes::TYPES as $index => $type)
                <div x-cloak x-show="!{{ $index }}" x-collapse>
                    <div
                        class="mb-[25px] grid grid-cols-[minmax(100px,900px)_1fr] gap-x-[50px] border border-[#D9E9F2] rounded-[3px] px-[25px] py-[20px]">
                        <div class="font-Spartan-Regular text-[13px] text-[#454141] leading-[22px]">
                            {{ $type['title'] }}
                            {{ $type['short'] }}
                        </div>
                        <div class="text-right w-full">
                            <button @click="addType('{{ $index }}')"
                                    class="inline-block font-Spartan-SemiBold text-[13px] text-app-green leading-[22px]">
                                Přidat&nbsp;typ&nbsp;účtu
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-modal>
</section>
