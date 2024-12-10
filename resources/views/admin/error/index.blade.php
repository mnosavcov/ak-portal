<x-admin-layout>
    <style>
        .admin-error-row {
            padding: 0 12px;
        }
        .admin-error-row:nth-child(even) {
            background-color: #f0f0f0;
        }
    </style>
    <main class="flex-1 h-screen overflow-y-scroll overflow-x-hidden">
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
        <section class="max-w-7xl mx-auto py-4 px-5"
                 x-data="{
                        errors: @js($errors),
                        loaded: {},
                        opened: {},
                        content: {},
                        open(index) {
                            this.opened[index] = !this.opened[index]
                            if(this.opened[index] && this.loaded[index] === false) {
                            this.load(index);
                            }
                        },
                        async load(index) {
                            this.loaded[index] = 'waiting';

                            await fetch('/admin/error/load/' + this.errors[index], {
                                method: 'GET',
                                headers: {
                                    'Content-type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                            }).then((response) => response.json())
                                .then((data) => {
                                    if(data.status === 'success') {
                                        this.loaded[index] = true;
                                        this.content[index] = data.content;
                                        return;
                                    }

                                    this.loaded[index] = false;
                                    alert('Chyba načtení')
                                })
                                .catch((error) => {
                                    this.loaded[index] = false;
                                    alert('Chyba načtení')
                                });
                        },
                        async archive(index) {
                            if(!confirm('Opravdu si přejete archivovat tuto chybu?\n\nPo archivaci se nebude možné dostat k chybě z administrace.')) {
                                return;
                            }

                            await fetch('{{ route('admin.error.archive') }}', {
                                method: 'DELETE',
                                body: JSON.stringify({
                                    filename: this.errors[index]
                                }),
                                headers: {
                                    'Content-type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                            }).then((response) => response.json())
                                .then((data) => {
                                    if(data.status === 'success') {
                                        delete this.errors[index]
                                        return;
                                    }

                                    alert('Chyba archivování')
                                })
                                .catch((error) => {
                                    alert('Chyba archivování')
                                });
                        }
                    }">
            <div class="flex justify-between items-center border-b border-gray-300">
                <h1 class="text-2xl font-semibold pt-2 pb-6">Errors</h1>
            </div>

            <!-- TABLE -->
            <div class="bg-white shadow rounded-sm my-2.5 overflow-x-auto">
                <template x-for="(error, index) in errors" :key="index">
                    <div>
                        <div
                            x-init="opened[index] = false; loaded[index] = false; content[index] = ''"
                            class="bg-gray-200 text-gray-600 text-sm leading-normal py-3 px-6 border-b border-b-gray-400
                        grid grid-cols-[1fr_min-content] gap-x-4">
                            <span x-text="error" class="cursor-pointer" @click="open(index)"></span>
                            <span class="cursor-pointer" @click="archive(index)"><i class="fa-solid fa-xmark text-red-600"></i></span>
                        </div>
                        <div x-show="opened[index]" x-collapse class="font-mono">
                            <template x-if="loaded[index] === true">
                                <div class="p-3">
                                    <div x-html="content[index]"></div>
                                </div>
                            </template>
                            <template x-if="loaded[index] !== true">
                                <div class="inline-loader h-[100px]">
                                    <div class="loader !w-6 !h-6"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </section>
    </main>
</x-admin-layout>
