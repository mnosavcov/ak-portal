<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        @if(auth()->user()->isVerifyFinished())
            <x-app.breadcrumbs :breadcrumbs="[
            __('Aktualizace účtu') => route('profile.edit-verify'),
            ]"></x-app.breadcrumbs>
        @else
            <x-app.breadcrumbs :breadcrumbs="[
            __('Ověření účtu') => route('profile.edit-verify'),
            ]"></x-app.breadcrumbs>
        @endif
    </div>

    <div class="w-full max-w-[1230px] mx-auto px-[15px]" x-data="verifyUserAccount(@js(!empty(env('RIVAAS_SECRET'))))"
         x-init="
         lang.Potvrdit_a_odeslat = @js(__('Potvrdit a odeslat'));
         lang.Pokracovat = @js(__('Pokračovat'));
         lang.Ulozit_zmeny = @js(__('Uložit změny'));
         lang.Zadejte_vase_statni_obcanstvi = @js(__('Zadejte vaše státní občanství.'));
         lang.Pro_vase_statni_obcanstvi_neni_mozne_automaticke_overeni = @js(__('Pro vaše státní občanství není možné automatické ověření.'));
         lang.Pred_pokracovanim_na_dalsi_krok_musite_vybrat_nekterou_z_metod_overeni_totoznosti_kliknutim_na_logo_overovaci_sluzby = @js(__('Před pokračováním na další krok musíte vybrat některou z metod ověření totožnosti (kliknutím na logo ověřovací služby).'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_investor_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "investor" alespoň 5 znaků.'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_nabizejici_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "nabízejí" alespoň 5 znaků.'));
         lang.Zadejte_do_pole_za_jakym_ucelem_ci_ucely_chcete_nas_portal_vyuzivat_jako_realitni_makler_alespon_5_znaku = @js(__('Zadejte do pole za jakým účelem či účely chcete náš portál využívat jako "realitní makléř" alespoň 5 znaků.'));
         lang.Chyba_deslani_dat = @js(__('Chyba odeslání dat'));
         data = @js($user->dataForVerify());
         actualizationData = @js($actualizationData);
         countries = @js(\App\Services\CountryServices::COUNTRIES);
         verified = {{ auth()->user()->check_status === 'verified' || auth()->user()->check_status === 'waiting' || auth()->user()->check_status === 're_verified' ? 'true' : 'false' }}
     ">

        @if(auth()->user()->isVerifyFinished())
            <h1 class="mb-[25px]" id="anchor-overeni-uctu">{{ __('Aktualizace účtu') }}</h1>
        @else
            <h1 class="mb-[25px]" id="anchor-overeni-uctu">{{ __('Ověření účtu') }}</h1>
        @endif

        @if ($errors->any())
            <ul class="bg-app-red text-white p-[15px] rounded-[3px] mb-[50px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if($actualizationData['errors'])
            <ul class="bg-app-red text-white p-[15px] rounded-[3px] mb-[50px]" x-cloak x-show="step === 2">
                <template x-for="(error, index) in actualizationData.errors" :key="index">
                    <li x-text="error"></li>
                </template>
            </ul>
        @endif

        @include('profile.edit-account')
    </div>

    @include('app.@faq')
</x-app-layout>
