<x-admin-layout>
    <main class="flex-1 h-screen overflow-y-scroll overflow-x-hidden" x-data="adminCategory"
          x-init="setData(@js($categories))">
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
                <h1 class="text-2xl font-semibold pt-2 pb-6">Dashboard</h1>
            </div>

            <div class="grid gap-y-[50px] mt-[50px]">
                @foreach(\App\Models\Category::CATEGORIES as $index => $category)
                    <div class="p-[20px] bg-white rounded-[7px]">
                        <div class="font-Spartan-Bold text-[15px]" title="{{ $category['description'] }}">
                            {{ $category['title'] }}
                        </div>
                        <div class="font-Spartan-Regular text-[11px] inline-block mb-[5px] text-gray-500">
                            {{ $category['url'] }}
                        </div>

                        <div class="grid gap-y-[15px] mt-[15px]" x-init="data.categories">
                            <template x-for="(subcategory, index) in data.categories['{{ $index }}']">
                                <div>
                                    <div x-show="!subcategory.edit"
                                         class="cursor-pointer inline-block">
                                        <div @click="subcategory.edit = true; subcategory.status = 'EDIT';">
                                            <div x-text="subcategory.subcategory" :title="subcategory.description"
                                                 class="font-Spartan-Regular text-[15px] inline-block"></div>
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </div>
                                        <div x-text="subcategory.url"
                                             class="font-Spartan-Regular text-[11px] inline-block text-gray-500"></div>
                                    </div>

                                    <div class="bg-gray-200 p-[15px] space-y-[15px] rounded-[3px]" x-cloak
                                         x-show="subcategory.edit"
                                         :class="{'line-through !bg-app-red/50': subcategory.status === 'DELETE'}"
                                    >
                                        <div @click="cancel(index, subcategory.id, '{{ $index }}', subcategory)"
                                             class="cursor-pointer float-right">
                                            <i class="fa-solid fa-xmark text-app-red font-bold"></i>
                                        </div>
                                        <div>
                                            <x-input-label for="subcategory" :value="__('Titulek')"/>
                                            <x-text-input id="subcategory" name="subcategory" class="block mt-1 w-full" type="text"
                                                          x-model="subcategory.subcategory"/>
                                            <ul class="text-sm text-red-600 space-y-1">
                                                <template
                                                    x-for="(error) in errors['data.{{ $index }}.' + index + '.subcategory']">
                                                    <li x-text="error"></li>
                                                </template>
                                            </ul>
                                        </div>
                                        <div>
                                            <x-input-label for="description" :value="__('Popis')"/>
                                            <x-textarea-input id="description" name="description"
                                                              class="block mt-1 w-full" type="text"
                                                              style="height: 10.5rem;"
                                                              x-model="subcategory.description"/>
                                        </div>
                                        <div>
                                            <x-input-label for="url" :value="__('URL')"/>
                                            <x-text-input id="url" name="url" class="block mt-1 w-full" type="text"
                                                          x-model="subcategory.url"/>
                                            <ul class="text-sm text-red-600 space-y-1">
                                                <template
                                                    x-for="(error) in errors['data.{{ $index }}.' + index + '.url']">
                                                    <li x-text="error"></li>
                                                </template>
                                            </ul>
                                        </div>

                                        <div @click="deleteCategory(subcategory)"
                                             x-show="subcategory.status !== 'NEW'"
                                             class="cursor-pointer float-right font-Spartan-Regular text-[13px] text-app-red"
                                             :class="{'!text-white': subcategory.status === 'DELETE'}"
                                        x-text="subcategory.status === 'DELETE' ? 'obnovit subkategorii' : 'smazat subkategorii'">
                                        </div>
                                        <div class="clear-both"></div>
                                    </div>

                                    <div class="clear-both"></div>
                                </div>
                            </template>
                        </div>
                        <button type="button"
                                class="font-Spartan-Regular text-app-blue text-[15px] mt-[25px]"
                                @click="add('{{ $index }}')">přidat
                        </button>
                    </div>
                @endforeach
            </div>

            <button type="button"
                    @click.prevent="saveCategories();"
                    class="mt-[15px] text-center max-tablet:w-full leading-[60px] tablet:px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block disabled:grayscale"
            >
                Uložit
            </button>
        </section>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </main>
</x-admin-layout>
