@php use Carbon\Carbon; @endphp
<x-app-layout>
    <div class="mt-[100px] mb-[100px]">
        <div class="w-full mx-auto max-w-[1230px] px-[15px]">
            <section x-data="{newPasswordOpen: false}">
                <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] mb-[50px] max-w-[1200px] mx-auto
            px-[10px] py-[25px]
            tablet:px-[20px] tablet:py-[35px]
            laptop:px-[30px] laptop:py-[50px]
            ">
                    <header>
                        <h2>{{ __('Vytvoření hesla') }}</h2>
                    </header>

                    <form method="post" action="{{ URL::temporarySignedRoute('admin.profile.update',
                        Carbon::createFromTimestamp($expires),
                        [
                        'id' => $user->id,
                         'hash' => $hash,
                         ]) }}" class="mt-[30px]
            grid gap-x-[20px] gap-y-[35px]
            md:grid-cols-2
            laptop:grid-cols-3
        ">
                        @csrf

                        <div>
                            <x-input-label for="password" :value="__('Zvolte své heslo *')"/>
                            <x-text-input id="password" name="password" class="block mt-1 w-full" type="password"/>
                            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                        </div>

                        <div>
                            <x-input-label for="password_confirmation"
                                           :value="__('Zadejte heslo znovu pro kontrolu *')"/>
                            <x-text-input id="password_confirmation" name="password_confirmation"
                                          class="block mt-1 w-full"
                                          type="password"/>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
                        </div>

                        <button type="submit"
                                class="col-span-full leading-[60px] w-full max-w-[350px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] inline-block justify-self-start"
                        >
                            Uložit změny
                        </button>
                    </form>
                </div>
            </section>

        </div>
    </div>

</x-app-layout>
