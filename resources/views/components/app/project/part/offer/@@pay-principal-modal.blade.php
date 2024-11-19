<x-modal name="pay-principal">
    <div
        x-data="{
                    loaderShow: false,
                    loaded: false,
                    vs: '-- generuje se --',
                    qr: null,
                    projectId: @js($project->id),
                    projectUrlPart: @js($project->url_part),
                    async getVs() {
                        await fetch('/projekty/get-vs/' + this.projectUrlPart, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                        }).then((response) => response.json())
                            .then((data) => {
                                if (data.status === 'success') {
                                    this.vs = data.vs;
                                    this.qr = data.qr;
                                    this.loaded = true;
                                } else {
                                    alert('Chyba načtení dat k platbě');
                                }
                            })
                            .catch((error) => {
                                alert('Chyba načtení dat k platbě')
                            });
                    },
                }"
        x-init="$watch('show', value => {
                if (value) {
                    if(!loaded) {
                        getVs()
                    }
                }
            })"
        class="p-[40px_10px] tablet:p-[50px_40px] text-center">
        <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
             class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

        <div class="text-center mb-[30px]">
            <h1>{{ __('Zaplacení jistoty') }}</h1>
        </div>

        <div
            class="p-[25px] rounded-[7px] bg-[#F4FAFE] text-[#414141] text-center tablet:text-left space-y-[15px] mb-[25px]">
            <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                <div class="font-Spartan-Bold text-[16px] tablet:text-[20px] leading-[30px]">
                    {{ __('Projekt') }}:
                </div>
                <div
                    class="font-Spartan-Regular text-[16px] tablet:text-[20px] leading-[30px]">
                    {{ $project->title }}
                </div>
            </div>

            <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                <div class="font-Spartan-Bold text-[16px] tablet:text-[20px] leading-[30px]">
                    {{ __('Výše jistoty') }}:
                </div>
                <div
                    class="font-Spartan-Regular text-[16px] tablet:text-[20px] leading-[30px]">
                    {!! $project->minimum_principal_text !!}
                </div>
            </div>
        </div>

        <div
            class="p-[25px] rounded-[7px] bg-[#F8F8F8] text-[#414141] text-center tablet:text-left mb-[25px] tablet:mb-[50px] space-y-[15px]">
            <div class="col-span-full font-Spartan-Bold text-[16px] tablet:text-[20px] leading-[30px] mb-[20px]">
                {{ __('Zaplaťte jistotu') }}
            </div>

            <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                <div class="font-Spartan-Bold text-[16px] tablet:text-[14px] leading-[30px]">
                    {{ __('Číslo bankovního účtu') }}:
                </div>
                <div
                    class="font-Spartan-Regular text-[16px] tablet:text-[14px] leading-[30px]">
                    {{ env('BANK_ACCOUNT') }} / {{ env('BANK_CODE') }}
                </div>
            </div>

            <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                <div class="font-Spartan-Bold text-[16px] tablet:text-[14px] leading-[30px]">
                    IBAN:
                </div>
                <div
                    class="font-Spartan-Regular text-[16px] tablet:text-[14px] leading-[30px]">
                    {{ env('BANK_IBAN') }}
                </div>
            </div>

            <div class="grid tablet:grid-cols-[max-content_1fr] gap-x-[10px]">
                <div class="font-Spartan-Bold text-[16px] tablet:text-[14px] leading-[30px]">
                    {{ __('Variabilní symbol') }}:
                </div>
                <div>
                    <div
                        class="font-Spartan-Regular text-[16px] tablet:text-[14px] leading-[30px]"
                        x-text="vs" x-show="loaded" x-cloak
                    >
                    </div>
                    <div class="inline-loader mt-[4px] w-[16px] h-[16px]" x-show="!loaded" x-cloak>
                        <span class="loader"></span>
                    </div>
                </div>
            </div>

            <div class="col-span-full text-center" x-cloak x-show="qr && loaded">
                <img :src="qr" class="inline-block">
            </div>
        </div>

        <form method="post" action="{{ route('projects.show', ['project' => $project->url_part]) }}"
              @submit="loaderShow = true;">
            @csrf
            <input type="hidden" name="check-payment" value="1">
            <input type="hidden" name="vs" :value="vs">
            <button type="submit"
                    class="justify-self-start col-span-2 font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] max-tablet:mb-[15px]
                            h-[50px] leading-[50px] w-full text-[14px]
                            tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px] inline-block disabled:grayscale
                            "
                    :disabled="!loaded"
            >
                {{ __('Hotovo') }}
            </button>
        </form>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </div>
</x-modal>
