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

            <template x-for="(tab, indexTab) in tabs" :key="indexTab">
                <table x-show="actualTab === indexTab" x-cloak
                       class="table w-auto"
                >
                    <tr class="font-Spartan-SemiBold text-[15px] text-left">
                        <th class="pr-[10px]">ID</th>
                        <th class="min-w-[400px] text-app-blue ">
                            {{ __('admin.Poznámka_ke_kontaktu_(interní_informace,_není_vidět_veřejně)') }}
                        </th>
                        <th class="min-w-[400px] text-app-red" x-show="indexTab === 'banned'">{{ __('admin.Důvod') }}</th>
                        <th class="min-w-[200px]">{{ __('admin.E-mail') }}</th>
                        <th class="whitespace-nowrap">{{ __('admin.E-mail') }}</th>
                        <th class="whitespace-nowrap" x-show="indexTab === 'deleted'">{{ __('admin.Smazáno') }}</th>
                        <th class="whitespace-nowrap">{{ __('admin.Osobní_údaje') }}</th>
                        <th class="whitespace-nowrap">{{ __('admin.Investor') }}</th>
                        <th class="whitespace-nowrap">{{ __('admin.Nabízející') }}</th>
                        <th class="whitespace-nowrap">{{ __('admin.Makléř') }}</th>
                        <th class="min-w-[100px]">{{ __('admin.Titul(y)_před') }}</th>
                        <th class="min-w-[200px]">{{ __('admin.Jméno') }}</th>
                        <th class="min-w-[200px]">{{ __('admin.Příjmení') }}</th>
                        <th class="min-w-[100px]">{{ __('admin.Titul(y)_za') }}</th>
                        <th class="min-w-[200px]">{{ __('admin.Ulice') }}</th>
                        <th class="min-w-[100px]">{{ __('admin.ČP') }}</th>
                        <th class="min-w-[200px]">{{ __('admin.Obec') }}</th>
                        <th class="min-w-[100px]">{{ __('admin.PSČ') }}</th>
                        <th class="min-w-[250px]">{{ __('admin.Občanství') }}</th>
                        <th class="min-w-[200px]">{{ __('admin.Telefon') }}</th>
                        <th class="min-w-[400px]">{{ __('admin.Informace_o_investorovi') }}</th>
                        <th class="min-w-[400px]">{{ __('admin.Účel_investor') }}</th>
                        <th class="min-w-[400px]">{{ __('admin.Účel_zadavatel') }}</th>
                        <th class="min-w-[400px]">{{ __('admin.Účel_makléř') }}</th>
                        <th class="whitespace-nowrap"></th>
                    </tr>

                    <template x-for="(user, index) in getDataFor(indexTab)"
                              :key="index">
                        <tr class="group hover:bg-gray-300" x-data="{data: {country: null}}"
                            :class="{'bg-app-red/50 hover:bg-app-red': isChanged(user.id)}"
                            x-modelable="data.country" x-model="user.country"
                        >
                            <td class="align-top">
                                <div x-text="user.id"
                                     class="self-center font-semibold pr-[10px] leading-[50px]"></div>
                            </td>
                            <td class="align-top">
                                <x-textarea-input id="notice"
                                                  class="block w-full h-[6rem] leading-[1.45] min-w-[250px]"
                                                  x-bind:class="{'bg-[#F3E2E4] group-hover:bg-[#F3D1D3]': user.notice !== null && user.notice.trim()}"
                                                  type="text"
                                                  x-model="user.notice"/>
                            </td>
                            <td class="align-top" x-show="indexTab === 'banned'">
                                <x-textarea-input id="ban_info"
                                                  class="block w-full h-[6rem] leading-[1.45] min-w-[250px]"
                                                  x-bind:class="{'bg-[#F3E2E4] group-hover:bg-[#F3D1D3]': user.ban_info !== null && user.ban_info.trim()}"
                                                  type="text"
                                                  x-model="user.ban_info"/>
                            </td>
                            <td class="align-top">
                                <x-text-input id="email" class="block group-hover:bg-gray-200" type="text"
                                              x-model="user.email" required/>
                            </td>
                            <td class="align-top">
                                <div
                                    class="bg-app-red text-white rounded-[3px] p-[5px_10px] cursor-pointer text-[13px] w-auto inline-block"
                                    :class="{'!bg-app-green': user.email_verified_at}"
                                    x-text="user.email_verified_at ? 'OK' : 'Neověřený'">
                                </div>
                            </td>
                            <td class="align-top" x-show="indexTab === 'deleted'">
                                <div
                                    class="bg-app-red text-white rounded-[3px] p-[5px_10px] cursor-pointer text-[13px] w-auto inline-block whitespace-nowrap text-center"
                                    :class="{'!bg-app-green': !user.deleted_at}"
                                    x-html="user.deleted_at ? user.deleted_at.replace(/ /g, '<br>') : 'NE'">
                                </div>
                            </td>
                            <td class="align-top">
                                <div
                                    class="col-span-4 bg-[#F8F8F8] rounded-[5px] p-[5px_10px] mb-[15px] cursor-pointer text-[13px] select-none inline-block text-center"
                                    x-text="statusTextOsobniUdaje(user.check_status)"
                                    :class="statusColorOsobniUdaje(user.check_status)"
                                    @click="changeStatusOsobniUdaje(user.id, 'check_status')">
                                </div>
                            </td>
                            <td class="align-top">
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
                            </td>
                            <td class="align-top">
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
                            </td>
                            <td class="align-top">
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
                            </td>
                            <td class="align-top">
                                <x-text-input id="title_before" class="block w-full group-hover:bg-gray-200"
                                              type="text"
                                              x-model="user.title_before" required/>
                                <x-revalidate-column column="title_before"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="name" class="block w-full group-hover:bg-gray-200" type="text"
                                              x-model="user.name" required/>
                                <x-revalidate-column column="name"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="surname" class="block w-full group-hover:bg-gray-200" type="text"
                                              x-model="user.surname" required/>
                                <x-revalidate-column column="surname"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="title_after" class="block w-full group-hover:bg-gray-200"
                                              type="text"
                                              x-model="user.title_after" required/>
                                <x-revalidate-column column="title_after"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="street" class="block w-full group-hover:bg-gray-200" type="text"
                                              x-model="user.street" required/>
                                <x-revalidate-column column="street"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="street_number" class="block w-full group-hover:bg-gray-200"
                                              type="text"
                                              x-model="user.street_number" required/>
                                <x-revalidate-column column="street_number"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="city" class="block w-full group-hover:bg-gray-200" type="text"
                                              x-model="user.city" required/>
                                <x-revalidate-column column="city"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="psc" class="block w-full group-hover:bg-gray-200" type="text"
                                              x-model="user.psc" required/>
                                <x-revalidate-column column="psc"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <div class="bg-white group-hover:bg-gray-200 min-w-[235px]">
                                    <x-countries-select id="country"
                                                        class="block mt-1 w-full group-hover:bg-gray-200"
                                                        type="text"/>
                                </div>
                                <x-revalidate-column column="country"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <x-text-input id="phone_number"
                                              class="block x-full group-hover:bg-gray-200"
                                              type="text"
                                              x-model="user.phone_number" required/>
                            </td>
                            <td class="align-top">
                                <x-textarea-input id="investor_info"
                                                  class="block w-full h-[6rem] leading-[1.45] min-w-[250px] group-hover:bg-gray-200"
                                                  type="text"
                                                  x-model="user.investor_info"/>
                            </td>
                            <td class="align-top">
                                <div
                                    class="bg-gray-50 text-gray-500 rounded-[5px] p-[8px_12px] mb-[5px] text-[13px] overflow-y-auto h-[6rem] leading-[1.45] border border-[#e2e2e2]"
                                    x-html="user.more_info_investor === null ? '' : user.more_info_investor.trim().replace(/\n/g, '<br>')">
                                </div>
                                <x-revalidate-column column="more_info_investor" :br="true"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <div
                                    class="bg-gray-50 text-gray-500 rounded-[5px] p-[8px_12px] mb-[5px] text-[13px] overflow-y-auto h-[6rem] leading-[1.45] border border-[#e2e2e2]"
                                    x-html="user.more_info_advertiser === null ? '' : user.more_info_advertiser.trim().replace(/\n/g, '<br>')">
                                </div>
                                <x-revalidate-column column="more_info_advertiser" :br="true"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <div
                                    class="bg-gray-50 text-gray-500 rounded-[5px] p-[8px_12px] mb-[5px] text-[13px] overflow-y-auto h-[6rem] leading-[1.45] border border-[#e2e2e2]"
                                    x-html="user.more_info_real_estate_broker === null ? '' : user.more_info_real_estate_broker.trim().replace(/\n/g, '<br>')">
                                </div>
                                <x-revalidate-column column="more_info_real_estate_broker" :br="true"></x-revalidate-column>
                            </td>
                            <td class="align-top">
                                <button type="button" x-show="indexTab !== 'deleted'"
                                        class="font-Spartan-SemiBold text-[15px] text-white bg-app-red p-[5px] rounded-[3px] disabled:grayscale"
                                        :disabled="!user.deletable"
                                        @click="deleteUser(user.id)"
                                >
                                    smazat
                                </button>
                                <button type="button"
                                        x-show="(indexTab !== 'deleted' && indexTab !== 'banned') || (indexTab === 'banned' && user.banned_at === 'NEW')"
                                        class="font-Spartan-SemiBold text-[15px] text-white bg-app-red p-[5px] rounded-[3px]"
                                        @click="$dispatch('open-modal', {name: 'set-ban', user: user})"
                                >
                                    BAN
                                </button>
                                <button type="button"
                                        x-show="(indexTab === 'banned' || (indexTab !== 'banned' && user.banned_at === 'NEW'))"
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
                            </td>
                        </tr>
                    </template>
                </table>
            </template>

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
