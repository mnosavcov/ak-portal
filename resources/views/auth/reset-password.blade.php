<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <h2 class="text-center mb-[25px]">Obnova hesla</h2>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Přihlašovací e-mail účtu, u kterého chcete obnovit heslo')"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email', $request->email)" required autofocus autocomplete="username"/>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Nové heslo')"/>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                          autocomplete="new-password"/>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Zadejte nové heslo ještě jednou')"/>

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password"/>
        </div>

        <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="justify-self-start col-span-2 font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] max-tablet:mb-[15px]
                            h-[50px] leading-[50px] w-full text-[14px]
                            tablet:h-[60px] tablet:leading-[60px] tablet:text-[18px]
                            ">
                Obnovit heslo
            </button>
        </div>
    </form>
</x-guest-layout>
