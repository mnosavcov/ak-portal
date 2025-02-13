<x-admin-layout>
    <script>
        window.changedAll = false;
    </script>
    <main class="flex-1 h-screen overflow-y-scroll overflow-x-auto" x-data="adminUser"
          x-init="
            lang['admin.Vsichni'] = @js(__('admin.Vsichni'));
            lang['admin.Neovereni'] = @js(__('admin.Neovereni'));
            lang['admin.Investori'] = @js(__('admin.Investori'));
            lang['admin.Nabizejici'] = @js(__('admin.Nabizejici'));
            lang['admin.Realitni_makleri'] = @js(__('admin.Realitni_makleri'));
            lang['admin.Administratori'] = @js(__('admin.Administratori'));
            lang['admin.Advisori'] = @js(__('admin.Advisori'));
            lang['admin.Prekladatele'] = @js(__('admin.Překladatelé'));
            lang['admin.Zabanovani'] = @js(__('admin.Zabanovani'));
            lang['admin.Smazani'] = @js(__('admin.Smazani'));
            lang['admin.Uzivatel_ma_aktivni_projekt'] = @js(__('admin.Uzivatel_ma_aktivni_projekt'));
            lang['admin.Smazani_je_nevratne_opravu_smazat'] = @js(__('admin.Smazani_je_nevratne_opravu_smazat'));
            lang['admin.ZAMITNUTO'] = @js(__('admin.ZAMITNUTO'));
            lang['admin.CEKA_NA_OVERENI'] = @js(__('admin.CEKA_NA_OVERENI'));
            lang['admin.OVERENO'] = @js(__('admin.OVERENO'));
            lang['admin.CEKA_NA_OPAKOVANE_OVERENI'] = @js(__('admin.CEKA_NA_OPAKOVANE_OVERENI'));
            lang['admin.NEZADANE_OSOBNI_UDAJE'] = @js(__('admin.NEZADANE_OSOBNI_UDAJE'));
            lang['admin.neznamy_stav'] = @js(__('admin.neznamy_stav'));
            lang['admin.Chyba'] = @js(__('admin.Chyba'));
            lang['admin.Chyba_ulozeni_uzivatele'] = @js(__('admin.Chyba_ulozeni_uzivatele'));
            lang['admin.Opravdu_si_prejete_nastavit_upresneni_adresy_jako_zpracovane'] = @js(__('admin.Opravdu_si_prejete_nastavit_upresneni_adresy_jako_zpracovane'));
            lang['admin.Pro_tento_typ_uctu_uzivatel_nevypnil_ucel_vyuziti_Prejete_si_zmenit_stav'] = @js(__('admin.Pro_tento_typ_uctu_uzivatel_nevypnil_ucel_vyuziti_Prejete_si_zmenit_stav'));
            lang['admin.Nejprve_zkontrolujte_upresneni_adresy_a_potvrdte_zpracovani'] = @js(__('admin.Nejprve_zkontrolujte_upresneni_adresy_a_potvrdte_zpracovani'));
            init();

          setData(@js($users));
            window.onload = function() {
                window.addEventListener('beforeunload', function (event) {
                    if(window.changedAll > 0) {
                        event.preventDefault();
                        event.returnValue = '';
                    }
                })
            };
            actualTab = @js(($_COOKIE['admin_user_tab'] ?? 'all'));
            ">
        <div class="md:hidden justify-between items-center bg-black text-white flex">
            <h1 class="text-2xl font-bold px-4">{{ env('APP_NAME') }}</h1>
            <button @click="navOpen = !navOpen" class="btn p-4 focus:outline-none hover:bg-gray-800">
                <svg class="w-6 h-6 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <section class="mx-auto py-4">
            <div class="flex justify-between items-center border-b border-gray-300 mb-[25px]">
                <h1 class="text-2xl font-semibold pt-2 pb-6">{{ __('admin.Uživatelé') }}</h1>
                <a href="{{ route('admin.new-advisor') }}"
                   x-cloak x-show="actualTab === 'advisor'" target="_blank"
                   class="bg-[#d8d8d8] text-white p-[10px_15px] rounded-[3px] relative text-[15px] font-Spartan-SemiBold bg-app-red"
                   @click=""
                >
                    {{ __('admin.Přidat_advisora') }}
                </a>

                <a href="{{ route('admin.new-translator') }}"
                   x-cloak x-show="actualTab === 'translator'" target="_blank"
                   class="bg-[#d8d8d8] text-white p-[10px_15px] rounded-[3px] relative text-[15px] font-Spartan-SemiBold bg-app-red"
                   @click=""
                >
                    {{ __('admin.Přidat_překladatele') }}
                </a>

                @if(auth()->user()->isOwner())
                    <a href="{{ route('admin.new-admin') }}"
                       x-cloak x-show="actualTab === 'superadmin'" target="_blank"
                       class="bg-[#d8d8d8] text-white p-[10px_15px] rounded-[3px] relative text-[15px] font-Spartan-SemiBold bg-app-red"
                       @click=""
                    >
                        {{ __('admin.Přidat_administrátora') }}
                    </a>
                @endif
            </div>

            <div class="flex gap-[5px] mb-[15px]">
                <template x-for="(tab, index) in tabs" :key="index">
                    <button
                        class="bg-[#d8d8d8] text-black p-[10px_15px] rounded-[3px] relative text-[15px] font-Spartan-SemiBold"
                        :class="{
                                    '!bg-app-blue text-white': actualTab === index,
                                    'pr-[35px] after:top-[11px] after:right-[7px] after:absolute after:block after:w-[20px] after:h-[20px] after:bg-app-red after:rounded-full': isChangedTab(index)
                                }"
                        @click="setActualTab(index)"
                        x-text="tab + ' (' + (Object.keys(getDataFor(index)).length) + ')'"
                    >
                    </button>
                </template>
            </div>

            <div class="grid grid-cols-[repeat(24,auto)] font-Spartan-SemiBold text-[15px]"
                 :class="{'grid-cols-[repeat(25,auto)]': actualTab === 'banned' || actualTab === 'deleted'}">
                <div class="pr-[10px]">ID</div>
                <div class="min-w-[400px] text-app-blue">
                    {{ __('admin.Poznámka_ke_kontaktu_(interní_informace,_není_vidět_veřejně)') }}
                </div>
                <div class="min-w-[400px] text-app-red" x-show="actualTab === 'banned'"
                     x-cloak>{{ __('admin.Důvod') }}</div>
                <div class="min-w-[200px]">{{ __('admin.E-mail') }}</div>
                <div class="whitespace-nowrap">{{ __('admin.E-mail') }}</div>
                <div class="whitespace-nowrap" x-show="actualTab === 'deleted'" x-cloak>{{ __('admin.Smazáno') }}</div>
                <div class="whitespace-nowrap">{{ __('admin.Osobní_údaje') }}</div>
                <div class="whitespace-nowrap">{{ __('admin.Investor') }}</div>
                <div class="whitespace-nowrap">{{ __('admin.Nabízející') }}</div>
                <div class="whitespace-nowrap">{{ __('admin.Makléř') }}</div>
                <div class="min-w-[300px]">{{ __('admin.Upřesnení_adresy') }}</div>
                <div class="min-w-[100px]">{{ __('admin.Titul(y)_před') }}</div>
                <div class="min-w-[200px]">{{ __('admin.Jméno') }}</div>
                <div class="min-w-[200px]">{{ __('admin.Příjmení') }}</div>
                <div class="min-w-[100px]">{{ __('admin.Titul(y)_za') }}</div>
                <div class="min-w-[200px]">{{ __('admin.Ulice') }}</div>
                <div class="min-w-[100px]">{{ __('admin.ČP') }}</div>
                <div class="min-w-[200px]">{{ __('admin.Obec') }}</div>
                <div class="min-w-[100px]">{{ __('admin.PSČ') }}</div>
                <div class="min-w-[250px]">{{ __('admin.Občanství') }}</div>
                <div class="min-w-[200px]">{{ __('admin.Telefon') }}</div>
                <div class="min-w-[400px]">{{ __('admin.Informace_o_investorovi') }}</div>
                <div class="min-w-[400px]">{{ __('admin.Účel_investor') }}</div>
                <div class="min-w-[400px]">{{ __('admin.Účel_zadavatel') }}</div>
                <div class="min-w-[400px]">{{ __('admin.Účel_makléř') }}</div>
                <div class="whitespace-nowrap"></div>

                <template x-for="(user, index) in getDataFor(actualTab)"
                          :key="index">
                    <div class="group hover:bg-gray-300 contents" x-data="{data: {country: null}}"
                         :class="{'bg-app-red/50': isChanged(user.id)}"
                         x-modelable="data.country" x-model="user.country"
                    >
                        <div class="align-top" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div x-text="user.id"
                                 class="self-center font-semibold pr-[10px] leading-[50px]"></div>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-textarea-input id="notice"
                                              class="block w-full h-[6rem] leading-[1.45] min-w-[250px]"
                                              x-bind:class="{'bg-[#F3E2E4] group-hover:bg-[#F3D1D3]': user.notice !== null && user.notice.trim()}"
                                              type="text"
                                              x-model="user.notice"/>
                        </div>
                        <div class="align-top pl-[2px]" x-show="actualTab === 'banned'" x-cloak
                             x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-textarea-input id="ban_info"
                                              class="block w-full h-[6rem] leading-[1.45] min-w-[250px]"
                                              x-bind:class="{'bg-[#F3E2E4] group-hover:bg-[#F3D1D3]': user.ban_info !== null && user.ban_info.trim()}"
                                              type="text"
                                              x-model="user.ban_info"/>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="email" class="block group-hover:bg-gray-200" type="text"
                                          x-model="user.email" required/>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-app-red text-white rounded-[3px] p-[5px_10px] cursor-pointer text-[13px] w-auto inline-block"
                                :class="{'!bg-app-green': user.email_verified_at}"
                                x-text="user.email_verified_at ? 'OK' : 'Neověřený'">
                            </div>
                        </div>
                        <div class="align-top pl-[2px]" x-show="actualTab === 'deleted'" x-cloak
                             x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-app-red text-white rounded-[3px] p-[5px_10px] cursor-pointer text-[13px] w-auto inline-block whitespace-nowrap text-center"
                                :class="{'!bg-app-green': !user.deleted_at}"
                                x-html="user.deleted_at ? user.deleted_at.replace(/ /g, '<br>') : 'NE'">
                            </div>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="col-span-4 bg-[#F8F8F8] rounded-[5px] p-[5px_10px] mb-[15px] cursor-pointer text-[13px] select-none inline-block text-center"
                                x-text="statusTextOsobniUdaje(user.check_status)"
                                :class="statusColorOsobniUdaje(user.check_status)"
                                @click="changeStatusOsobniUdaje(user.id, 'check_status')">
                            </div>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-app-red text-white rounded-[3px] p-[5px_10px] cursor-pointer inline-block text-[13px] select-none"
                                :class="{'!bg-app-green': (parseInt(user.investor) === 1)}"
                                @click="user.investor = (!(parseInt(user.investor) === 1)) ? 1 : 0"
                                x-text="user.investor ? 'ANO' : 'NE'"
                            >
                            </div>
                            <div x-show="user.investor"
                                 class="col-span-4 bg-[#F8F8F8] rounded-[5px] p-[5px_10px] mb-[15px] cursor-pointer text-[13px] select-none inline-block text-center"
                                 x-text="statusText(user.investor_status)"
                                 :class="statusColor(user.investor_status)"
                                 @click="changeStatus(user.id, 'investor_status')">
                            </div>
                            <x-revalidate-column column="investor" :yesNo="true"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-app-red text-white rounded-[3px] p-[5px_10px] cursor-pointer inline-block text-[13px] select-none"
                                :class="{'!bg-app-green': (parseInt(user.advertiser) === 1)}"
                                @click="user.advertiser = (!(parseInt(user.advertiser) === 1)) ? 1 : 0"
                                x-text="user.advertiser ? 'ANO' : 'NE'"
                            >
                            </div>
                            <div x-show="user.advertiser"
                                 class="col-span-4 bg-[#F8F8F8] rounded-[5px] p-[5px_10px] mb-[15px] cursor-pointer text-[13px] select-none inline-block text-center"
                                 x-text="statusText(user.advertiser_status)"
                                 :class="statusColor(user.advertiser_status)"
                                 @click="changeStatus(user.id, 'advertiser_status')">
                            </div>
                            <x-revalidate-column column="advertiser" :yesNo="true"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-app-red text-white rounded-[3px] p-[5px_10px] cursor-pointer inline-block text-[13px] select-none"
                                :class="{'!bg-app-green': (parseInt(user.real_estate_broker) === 1)}"
                                @click="user.real_estate_broker = (!(parseInt(user.real_estate_broker) === 1)) ? 1 : 0"
                                x-text="user.real_estate_broker ? 'ANO' : 'NE'"
                            >
                            </div>
                            <div x-show="user.real_estate_broker"
                                 class="col-span-4 bg-[#F8F8F8] rounded-[5px] p-[5px_10px] mb-[15px] cursor-pointer text-[13px] select-none inline-block text-center"
                                 x-text="statusText(user.real_estate_broker_status)"
                                 :class="statusColor(user.real_estate_broker_status)"
                                 @click="changeStatus(user.id, 'real_estate_broker_status')">
                            </div>
                            <x-revalidate-column column="real_estate_broker" :yesNo="true"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-gray-50 text-gray-500 rounded-[5px] p-[8px_12px] mb-[5px] text-[13px] overflow-y-auto h-[6rem] leading-[1.45] border border-[#e2e2e2]"
                                x-html="(user?.userverifyservice?.appendix ?? '').trim().replace(/\n/g, '<br>')"
                                :class="{'bg-red-400/50': (user?.userverifyservice?.appendix ?? '').trim().length && !user?.userverifyservice?.appendix_ok}"
                            >
                            </div>
                            <template
                                x-if="(user?.userverifyservice?.appendix ?? '').trim().length && !user?.userverifyservice?.appendix_ok">
                                <button class="text-app-green" @click="appendixOk(user.id)">označit jako zpracováno</button>
                            </template>
                            <x-revalidate-column column="more_info_investor" :br="true"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="title_before" class="block w-full group-hover:bg-gray-200"
                                          type="text"
                                          x-model="user.title_before" required/>
                            <x-revalidate-column column="title_before"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="name" class="block w-full group-hover:bg-gray-200" type="text"
                                          x-model="user.name" required/>
                            <x-revalidate-column column="name"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="surname" class="block w-full group-hover:bg-gray-200" type="text"
                                          x-model="user.surname" required/>
                            <x-revalidate-column column="surname"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="title_after" class="block w-full group-hover:bg-gray-200"
                                          type="text"
                                          x-model="user.title_after" required/>
                            <x-revalidate-column column="title_after"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="street" class="block w-full group-hover:bg-gray-200" type="text"
                                          x-model="user.street" required/>
                            <x-revalidate-column column="street"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="street_number" class="block w-full group-hover:bg-gray-200"
                                          type="text"
                                          x-model="user.street_number" required/>
                            <x-revalidate-column column="street_number"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="city" class="block w-full group-hover:bg-gray-200" type="text"
                                          x-model="user.city" required/>
                            <x-revalidate-column column="city"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="psc" class="block w-full group-hover:bg-gray-200" type="text"
                                          x-model="user.psc" required/>
                            <x-revalidate-column column="psc"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div class="bg-white group-hover:bg-gray-200 min-w-[235px]">
                                <x-countries-select id="country"
                                                    class="block mt-1 w-full group-hover:bg-gray-200"
                                                    type="text"/>
                            </div>
                            <x-revalidate-column column="country"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-text-input id="phone_number"
                                          class="block x-full group-hover:bg-gray-200"
                                          type="text"
                                          x-model="user.phone_number" required/>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <x-textarea-input id="investor_info"
                                              class="block w-full h-[6rem] leading-[1.45] min-w-[250px] group-hover:bg-gray-200"
                                              type="text"
                                              x-model="user.investor_info"/>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-gray-50 text-gray-500 rounded-[5px] p-[8px_12px] mb-[5px] text-[13px] overflow-y-auto h-[6rem] leading-[1.45] border border-[#e2e2e2]"
                                x-html="user.more_info_investor === null ? '' : user.more_info_investor.trim().replace(/\n/g, '<br>')">
                            </div>
                            <x-revalidate-column column="more_info_investor" :br="true"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-gray-50 text-gray-500 rounded-[5px] p-[8px_12px] mb-[5px] text-[13px] overflow-y-auto h-[6rem] leading-[1.45] border border-[#e2e2e2]"
                                x-html="user.more_info_advertiser === null ? '' : user.more_info_advertiser.trim().replace(/\n/g, '<br>')">
                            </div>
                            <x-revalidate-column column="more_info_advertiser" :br="true"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px]" x-bind:class="{'bg-app-red/50': isChanged(user.id)}">
                            <div
                                class="bg-gray-50 text-gray-500 rounded-[5px] p-[8px_12px] mb-[5px] text-[13px] overflow-y-auto h-[6rem] leading-[1.45] border border-[#e2e2e2]"
                                x-html="user.more_info_real_estate_broker === null ? '' : user.more_info_real_estate_broker.trim().replace(/\n/g, '<br>')">
                            </div>
                            <x-revalidate-column column="more_info_real_estate_broker" :br="true"></x-revalidate-column>
                        </div>
                        <div class="align-top pl-[2px] ">
                            <button type="button" x-show="actualTab !== 'deleted'" x-cloak
                                    class="font-Spartan-SemiBold text-[15px] text-white bg-app-red p-[5px] rounded-[3px] disabled:grayscale"
                                    :disabled="!user.deletable"
                                    @click="deleteUser(user.id)"
                            >
                                smazat
                            </button>
                            <button type="button" x-cloak
                                    x-show="(actualTab !== 'deleted' && actualTab !== 'banned') || (actualTab === 'banned' && user.banned_at === 'NEW')"
                                    class="font-Spartan-SemiBold text-[15px] text-white bg-app-red p-[5px] rounded-[3px]"
                                    @click="$dispatch('open-modal', {name: 'set-ban', user: user})"
                            >
                                BAN
                            </button>
                            <button type="button" x-cloak
                                    x-show="(actualTab === 'banned' || (actualTab !== 'banned' && user.banned_at === 'NEW'))"
                                    class="font-Spartan-SemiBold text-[15px] text-white bg-app-red p-[5px] rounded-[3px]"
                                    @click="user.banned_at = 'REMOVE'; user.ban_info = '';"
                            >
                                ZRUŠIT BAN
                            </button>
                            <button type="button" x-show="isChanged(user.id)"
                                    class="font-Spartan-SemiBold text-[15px] text-app-red bg-white p-[5px] rounded-[3px]"
                                    @click="removeChanges(user.id)"
                            >
                                zrušit&nbsp;změny
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <button type="button"
                    @click.prevent="saveUsers();"
                    class="mt-[15px] text-center max-tablet:w-full leading-[60px] tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
                    :disabled="!isChangedTab('ultimate')"
            >
                {{ __('admin.Uložit') }}
            </button>
        </section>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </main>

    <x-modal name="set-ban">
        <div class="p-[40px_10px] tablet:p-[50px_40px] text-center"
             x-init="$watch('show', value => {
                    if (value) {
                        banInfo = inputData.user.ban_info ?? '';
                    }
                })"
             x-data="{
                    banInfo: '',
                }">

            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
                 @click="$dispatch('close')"
                 class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

            <div class="text-center mb-[30px]">
                <h1>Ban</h1>
            </div>

            <template x-if="typeof inputData.user !== 'undefined'">
                <div>
                    <div class="text-left">
                        <x-input-label for="ban_info" :value="__('Důvod') . ':'"/>
                        <x-textarea-input id="ban_info" class="block mt-1 w-full" type="text" name="ban_info"
                                          x-model="banInfo"
                        />
                    </div>
                </div>
            </template>

            <button
                @click="
                        inputData.user.ban_info = banInfo;
                        inputData.user.banned_at = 'NEW';
                        $dispatch('close-modal', 'set-ban')
                    "
                class="mt-[15px] cursor-pointer text-center font-Spartan-Bold text-[18px] text-white h-[60px] leading-[60px] w-full max-w-[350px] bg-app-green rounded-[3px] disabled:grayscale"
                :disabled="!banInfo.trim().length"
            >
                {{ __('admin.Nastavit_ban') }}
            </button>
            <br>

            <button
                @click="
                        inputData.user.ban_info = '';
                        inputData.user.banned_at = null;
                        $dispatch('close-modal', 'set-ban')
                    "
                class="mt-[13px] cursor-pointer text-center font-Spartan-Regular text-[15px] text-app-red"
            >
                {{ __('admin.Zrušit_ban') }}
            </button>
        </div>
    </x-modal>
</x-admin-layout>
