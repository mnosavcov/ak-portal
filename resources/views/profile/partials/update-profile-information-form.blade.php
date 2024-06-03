<section x-data="{newPasswordOpen: false}">
    <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">
        <header>
            <h2>{{ __('Přihlašovací a kontaktní údaje') }}</h2>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-[30px]
            grid gap-x-[20px] gap-y-[35px]
            md:grid-cols-2
            laptop:grid-cols-3
        ">
            @csrf
            @method('patch')
            <input type="hidden" name="set_new_password" :value="newPasswordOpen ? 1 : 0">

            <div>
                <x-input-label for="email" :value="__('Kontaktní e-mail *')"/>
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                              value="{{ old('email', $user['email']) }}" required autocomplete="email"/>
            </div>

            <div>
                <x-input-label for="phone_number" :value="__('Telefonní číslo *')"/>
                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full"
                              value="{{ old('phone_number', $user['phone_number']) }}" required/>
            </div>

            <div class="hidden laptop:block"></div>

            <div class="col-span-full font-Spartan-Regular text-[20px] leading-[30px]">Heslo</div>

            <div type="button" @click="newPasswordOpen = !newPasswordOpen"
                 class="col-span-full font-Spartan-SemiBold text-[18px] text-app-blue inline-block justify-self-start cursor-pointer">
                Nastavit nové heslo
            </div>

            <div x-cloak x-show="newPasswordOpen" x-collapse>
                <x-input-label for="password" :value="__('Zvolte své heslo *')"/>
                <x-text-input id="password" name="password" class="block mt-1 w-full" type="password"/>
            </div>

            <div x-cloak x-show="newPasswordOpen" x-collapse>
                <x-input-label for="password_confirmation" :value="__('Zadejte heslo znovu pro kontrolu *')"/>
                <x-text-input id="password_confirmation" name="password_confirmation" class="block mt-1 w-full" type="password"/>
            </div>

            <button type="submit"
                    class="col-span-full leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"
            >
                Uložit změny
            </button>

            <div class="col-span-full h-[1px] bg-[#D9E9F2]"></div>
        </form>

        <div class="col-span-full mt-[20px]">
            <button type="button" class="font-Spartan-SemiBold text-app-red text-[13px] cursor-pointer"
                    @click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                Zrušit celý účet
            </button>

            <x-modal name="confirm-user-deletion">
                <div class="relative">
                    <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
                         class="cursor-pointer w-[20px] h-[20px] float-right relative top-[-20px] tablet:right-[-15px] right-[5px]">

                    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Jste si jistí, že chcete smazat váš účet?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Jakmile bude váš účet smazán, budou trvale smazány všechny jeho zdroje a data. Zadejte prosím své heslo pro potvrzení, že chcete trvale zrušit svůj účet.') }}
                        </p>

                        <div class="mt-6">
                            <x-input-label for="password_delete" value="{{ __('Heslo') }}"/>

                            <x-text-input
                                id="password_delete" name="password_delete" type="password"
                                class="mt-1 block w-3/4"
                                placeholder="{{ __('Heslo') }}"
                            />
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-danger-button class="ml-3">
                                {{ __('Zrušit účet') }}
                            </x-danger-button>
                        </div>
                    </form>
                </div>
            </x-modal>
        </div>
    </div>
</section>
