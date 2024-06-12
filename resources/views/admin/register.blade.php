<x-admin-layout>
    <div class="w-full">
        <div class="bg-white shadow-[0_3px_35px_rgba(0,0,0,0.10)] rounded-[3px] w-max max-w-[1230px]
                 px-[10px] py-[25px] mt-[25px]
                 tablet:px-[30px] tablet:py-[50px] mx-auto
                ">

            <h2 class="mb-[25px]">{{ $title }}</h2>

            <form method="POST" action="{{ $url }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Jméno')"/>
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                                  required/>
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <div class="mt-4">
                    <x-input-label for="surname" :value="__('Příjmení')"/>
                    <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname"
                                  :value="old('surname')" required/>
                    <x-input-error :messages="$errors->get('surname')" class="mt-2"/>
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')"/>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                                  required/>
                    <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                </div>

                <div class="flex items-center justify-start mt-4">
                    <button
                        class="font-Spartan-Bold text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale mb-[20px] tablet:mb-[50px]
                    h-[50px] leading-[50px] w-full text-[14px]
                    tablet:h-[60px] tablet:leading-[60px] tablet:w-auto tablet:px-[100px] tablet:text-[18px]
                    ">
                        Vytvořit
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
