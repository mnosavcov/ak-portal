<x-admin-layout>
    <main class="flex-1 h-screen overflow-y-scroll overflow-x-hidden" x-data="adminFaq(@js($faq))">
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
            <div class="flex justify-between items-center border-b border-gray-300">
                <h1 class="text-2xl font-semibold pt-2 pb-6">
                    {{ __('admin.FAQ') }}
                    <button @click="showEdit()" class="text-app ml-[10px]">
                        <i class="fa-solid fa-circle-plus"></i>
                    </button>
                </h1>
            </div>

            <!-- TABLE -->
            <div class="bg-white shadow rounded-sm my-2.5 overflow-x-auto">
                <template x-for="(faqData, faqIndex) in faq" :key="faqIndex">
                    <div x-data="{ open: false }" :data-prokoho="faqData.pro_koho">
                        <div
                            class="bg-gray-200 text-gray-600 text-sm leading-normal py-3 px-4 border-b border-b-gray-400 border-t-red-400"
                            :class="{'border-t-2': $el.parentElement.previousElementSibling.dataset.prokoho && $el.parentElement.dataset.prokoho !== $el.parentElement.previousElementSibling.dataset.prokoho}"
                        >
                            <div>
                                <span x-text="faqData.otazka" class="cursor-pointer" @click="open = !open"></span>
                                [<span x-text="faqData.pro_koho" class="font-semibold"></span>]
                            </div>
                            <div x-show="open" x-cloak x-collapse>
                                <div x-text="faqData.odpoved" class="mt-2 p-2 bg-white rounded-[3px] shadow-sm"></div>
                                <div class="flex mt-[10px] gap-x-[10px]">
                                    <button @click="remove(faqData.id)"
                                            class="text-app-red underline hover:no-underline">
                                        smazat
                                    </button>
                                    <button @click="showEdit(faqData.id)"
                                            class="text-app-blue underline hover:no-underline font-semibold">
                                        upravit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </section>

        <x-modal name="admin-faq-form">
            <div class="p-[30px_25px]">

                <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
                     @click="$dispatch('close')"
                     class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

                <h2 class="mt-[15px] mb-[25px] text-center">FAQ</h2>

                <div class="grid gap-y-[15px]">
                    <input type="hidden" x-model="editData.id">
                    <div class="w-full">
                        <label class="block text-left font-semibold text-[13px]">Pro koho</label>
                        <select x-model="editData.pro_koho" class="w-full">
                            <option value="">-- vyplnit nové --</option>
                            <template x-for="(faqCategoriesData, faqCategoriesIndex) in faqCategories"
                                      :key="faqCategoriesIndex">
                                <option :value="faqCategoriesData" x-text="faqCategoriesData"></option>
                            </template>
                        </select>
                        <input class="block w-full rounded-[3px] text-[14px] mt-[5px]" type="text"
                               x-model="editData.pro_koho_new" x-cloak x-show="!editData.pro_koho">
                    </div>
                    <div class="w-full">
                        <label class="block text-left font-semibold text-[13px]">Otázka</label>
                        <input class="block w-full rounded-[3px] text-[14px]" type="text" x-model="editData.otazka">
                    </div>
                    <div class="w-full">
                        <label class="block text-left font-semibold text-[13px]">Odpověď</label>
                        <textarea class="block w-full h-[250px] rounded-[3px] text-[14px]"
                                  x-model="editData.odpoved"></textarea>
                    </div>
                </div>

                <div class="text-right mt-[10px]">
                    <button
                        @click="update(editData.id)"
                        class="font-Spartan-Regular text-[15px] bg-app-blue text-white py-2 px-4 rounded-[3px] shadow-md">
                        {{ __('uložit') }}
                    </button>
                </div>
            </div>
        </x-modal>
    </main>
</x-admin-layout>
