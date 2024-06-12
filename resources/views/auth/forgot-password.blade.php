<x-guest-layout>
    <div class="mb-4 text-gray-600">
        {{ __('Zapomněli jste heslo? Zadejte svou e-mailovou adresu a my vám zašleme e-mail s odkazem na obnovení hesla.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('E-mail')"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                          autofocus/>
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit"
                    class="justify-self-start col-span-2 font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale max-tablet:mb-[15px]
                            h-[50px] leading-[50px] w-full text-[14px]
                            tablet:h-[60px] tablet:leading-[60px] tablet:text-[18px]
                            "
            >
                {{ __('E-mail pro resetování hesla') }}
            </button>
        </div>
    </form>
</x-guest-layout>
