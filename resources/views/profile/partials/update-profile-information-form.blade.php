<section x-data="{newPasswordOpen: false}">
    <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">
        <header>
            @if(!auth()->user()->superadmin && !auth()->user()->advisor)
                <h2>{{ __('Přihlašovací a kontaktní údaje') }}</h2>
            @else
                <h2>{{ __('Heslo') }}</h2>
                <div x-init="newPasswordOpen = true;"></div>
            @endif
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-[30px]
            grid gap-x-[20px] gap-y-[35px]
            md:grid-cols-2
            laptop:grid-cols-3
        ">
            @csrf
            @method('patch')
            <input type="hidden" name="set_new_password" :value="newPasswordOpen ? 1 : 0">

            @if(!auth()->user()->superadmin && !auth()->user()->advisor)
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
            @endif

            <div x-cloak x-show="newPasswordOpen" x-collapse>
                <x-input-label for="password" :value="__('Zvolte své heslo *')"/>
                <x-text-input id="password" name="password" class="block mt-1 w-full" type="password"/>
            </div>

            <div x-cloak x-show="newPasswordOpen" x-collapse>
                <x-input-label for="password_confirmation" :value="__('Zadejte heslo znovu pro kontrolu *')"/>
                <x-text-input id="password_confirmation" name="password_confirmation" class="block mt-1 w-full"
                              type="password"/>
            </div>

            <button type="submit"
                    class="col-span-full leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"
            >
                Uložit změny
            </button>
        </form>

        @if(!auth()->user()->superadmin && !auth()->user()->advisor)
            <div class="col-span-full h-[1px] bg-[#D9E9F2] mt-[35px]"></div>

            <div class="col-span-full mt-[20px]">
                @if(auth()->user()->deletable)
                    <button type="button" class="font-Spartan-SemiBold text-app-red text-[13px] cursor-pointer"
                            @click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                        Zrušit celý účet
                    </button>
                @else
                    <button type="button"
                            class="font-Spartan-SemiBold text-app-red text-[13px] cursor-pointer grayscale"
                            @click.prevent="alert('Omlouváme se, funkce smazání uživatelského účtu není dostupná, dokud máte aktivní projekt.')">
                        Zrušit celý účet
                    </button>
                @endif

                <x-modal name="confirm-user-deletion">
                    <div class="relative p-[50px_15px_30px]">
                        <img src="{{ Vite::asset('resources/images/ico-close.svg') }}" @click="$dispatch('close')"
                             class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

                        <form method="post" action="{{ route('profile.destroy') }}">
                            @csrf
                            @method('delete')

                            <h2 class="mb-[15px] max-tablet:text-center">
                                {{ __('Opravdu si přejete smazat svůj uživatelský účet?') }}
                            </h2>

                            <p class="font-Spartan-Regular text-[13px] max-tablet:order-1 text-[#454141] leading-[22px] max-tablet:text-center">
                                {{ __('Smazání je nevratné. Smazání nevede k odstranění některých typů obsahu, které jste na portál mohli umístit. A to s ohledem na informační kontinuitu, transparentnost prezentovaných informací a konzistenci/stabilitu systému portálu. Při smazání dochází zejména k odstranění některých osobních údajů, odhlášení z e-mailových notifikací a uzavře se vám přístup do účtu.') }}
                            </p>

                            <p class="font-Spartan-Regular text-[13px] max-tablet:order-1 text-[#454141] leading-[22px] max-tablet:text-center mt-[10px]">
                                {{ __('Správa vašich osobních údajů a dat v&nbsp;uživatelském účtu se řídí Zásadami zpracování osobních údajů zveřejněnými na portálu.') }}
                            </p>

                            <div class="mt-6">
                                <x-input-label for="password_delete" value="{{ __('Heslo') }}"/>

                                <x-text-input
                                        id="password_delete" name="password_delete" type="password"
                                        class="mt-1 block w-3/4 max-tablet:w-full" required="required"
                                        placeholder="{{ __('Heslo') }}"
                                />
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit"
                                        class="col-span-full h-[50px] leading-[50px] w-full tablet:max-w-[250px] font-Spartan-Bold text-[18px] text-white bg-app-red rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"
                                >
                                    Zrušit účet
                                </button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            </div>
        @endif
    </div>
</section>
