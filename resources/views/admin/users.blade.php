<x-admin-layout>
    <script>
        window.changedAll = false;
    </script>
    <main class="flex-1 h-screen overflow-y-scroll overflow-x-hidden" x-data="adminUser"
          x-init="setData(@js($users));
            window.onload = function() {
                window.addEventListener('beforeunload', function (event) {
                    if(window.changedAll > 0) {
                        event.preventDefault();
                        event.returnValue = '';
                    }
                })
            };">
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
        <section class="max-w-7xl mx-auto py-4 px-5">
            <div class="flex justify-between items-center border-b border-gray-300 mb-[25px]">
                <h1 class="text-2xl font-semibold pt-2 pb-6">Uživatelé</h1>
            </div>

            <template x-for="(user, index) in data.users" :key="index">
                <form class="p-[20px] rounded-[10px] bg-white mb-[15px]" x-data="{open: false, data: {country: null}}"
                      @submit.prevent="saveUser(user.id);"
                      x-modelable="data.country" x-model="user.country">
                    <div class="grid grid-cols-[1fr_12px] gap-x-[25px] cursor-pointer"
                         @click="if(isChanged(user.id)) {return} open = !open">

                        <div class="flex flex-row gap-x-[15px]">
                            <div x-text="user.id" class="self-center font-semibold"></div>
                            <div x-text="user.name" class="self-center"></div>
                            <div x-text="user.surname" class="self-center"></div>
                            <div class="bg-[#F8F8F8] rounded-[5px] p-[5px]" x-text="statusText(user.check_status)"
                                :class="statusColor(user.check_status)">
                            </div>
                        </div>

                        <img class="self-center transition"
                             src="{{ Vite::asset('resources/images/arrow-down-black-12x8.svg') }}"
                             :class="{'rotate-180': open}"
                        >
                    </div>

                    <div class="mt-[15px] bg-gray-600 text-white rounded-[5px] p-[15px] mb-[15px] cursor-pointer"
                         x-collapse
                         x-show="user.notice !== null && String(user.notice).trim().length > 0"
                         x-html="String(user.notice).trim().replace(/\n/g, '<br>')">
                    </div>

                    <div x-show="open || isChanged(user.id)" x-collapse>
                        <div class="mt-[15px] col-span-4 bg-[#F8F8F8] rounded-[5px] p-[15px] mb-[15px] cursor-pointer"
                             x-text="statusText(user.check_status)"
                             :class="statusColor(user.check_status)"
                             @click="changeStatus(user.id)">
                        </div>

                        <div class="mt-[15px] grid grid-cols-4 gap-[10px]">
                            <div class="bg-app-red text-white rounded-[3px] p-[10px] cursor-pointer"
                                 :class="{'!bg-app-green': (parseInt(user.investor) === 1)}"
                                 @click="user.investor = (!(parseInt(user.investor) === 1)) ? 1 : 0">
                                Investor
                            </div>
                            <div class="bg-app-red text-white rounded-[3px] p-[10px] cursor-pointer"
                                 :class="{'!bg-app-green': (parseInt(user.advertiser) === 1)}"
                                 @click="user.advertiser = (!(parseInt(user.advertiser) === 1)) ? 1 : 0">
                                Nabízející
                            </div>
                            <div class="bg-app-red text-white rounded-[3px] p-[10px] cursor-pointer"
                                 :class="{'!bg-app-green': (parseInt(user.real_estate_broker) === 1)}"
                                 @click="user.real_estate_broker = (!(parseInt(user.real_estate_broker) === 1)) ? 1 : 0">
                                Realitní makléř
                            </div>
                            <div></div>

                            <div>
                                <x-input-label for="title_before" :value="__('Titul(y) před')"/>
                                <x-text-input id="title_before" class="block mt-1 w-full" type="text"
                                              x-model="user.title_before"/>
                            </div>
                            <div>
                                <x-input-label for="name" :value="__('Jméno *')"/>
                                <x-text-input id="name" class="block mt-1 w-full" type="text"
                                              x-model="user.name" required/>
                            </div>
                            <div>
                                <x-input-label for="surname" :value="__('Příjmení *')"/>
                                <x-text-input id="surname" class="block mt-1 w-full" type="text"
                                              x-model="user.surname" required/>
                            </div>
                            <div>
                                <x-input-label for="title_after" :value="__('Titul(y) za')"/>
                                <x-text-input id="title_after" class="block mt-1 w-full" type="text"
                                              x-model="user.title_after"/>
                            </div>

                            <div>
                                <x-input-label for="street" :value="__('Ulice *')"/>
                                <x-text-input id="street" class="block mt-1 w-full" type="text"
                                              x-model="user.street" required/>
                            </div>
                            <div>
                                <x-input-label for="street_number" :value="__('Číslo domu / Číslo orientační *')"/>
                                <x-text-input id="street_number" class="block mt-1 w-full" type="text"
                                              x-model="user.street_number" required/>
                            </div>
                            <div>
                                <x-input-label for="city" :value="__('Obec *')"/>
                                <x-text-input id="city" class="block mt-1 w-full" type="text"
                                              x-model="user.city" required/>
                            </div>
                            <div>
                                <x-input-label for="psc" :value="__('PSČ *')"/>
                                <x-text-input id="psc" class="block mt-1 w-full" type="text"
                                              x-model="user.psc" required/>
                            </div>

                            <div>
                                <x-input-label for="country" :value="__('Státní občanství (země)')" class="mb-1"/>
                                <x-countries-select id="country" class="block mt-1 w-full" type="text"/>
                            </div>
                            <div></div>
                            <div></div>
                            <div></div>

                            <div>
                                <x-input-label for="email" :value="__('E-mail *')"/>
                                <x-text-input id="email" class="block mt-1 w-full" type="email"
                                              x-model="user.email"/>
                            </div>
                            <div>
                                <x-input-label for="phone_number" :value="__('Telefonní číslo *')"/>
                                <x-text-input id="phone_number" class="block mt-1 w-full" type="text"
                                              x-model="user.phone_number"/>
                            </div>
                            <div></div>
                            <div></div>

                            <div class="col-span-4">
                                <x-input-label for="investor_info" :value="__('Informace o investorovi')"/>
                                <x-textarea-input id="investor_info" class="block mt-1 w-full h-[5rem] leading-[1.5]" type="text"
                                              x-model="user.investor_info"/>
                            </div>

                            <div class="col-span-4">
                                <x-input-label for="notice" :value="__('Poznámka ke kontaktu (interní informace, není vidět veřejně)')" class="text-app-red"/>
                                <x-textarea-input id="notice" class="block mt-1 w-full leading-[1.5] h-[5rem]" type="text"
                                              x-model="user.notice"/>
                            </div>

                            <div class="col-span-4 bg-[#F8F8F8] rounded-[5px] p-[15px] mb-[15px]"
                                 x-text="user.more_info">
                            </div>

                            <div class="col-span-4" x-show="isChanged(user.id)" x-cloak x-collapse>
                                <button
                                    class="text-center max-tablet:w-full leading-[60px] tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block"
                                >
                                    Uložit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </template>


        </section>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </main>
</x-admin-layout>
