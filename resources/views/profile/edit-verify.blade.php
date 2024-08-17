<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Nastavení účtu' => route('profile.edit'),
        ]"></x-app.breadcrumbs>
    </div>

    <div class="w-full max-w-[1230px] mx-auto px-[15px]">

        @if($user->check_status !== 'not_verified')
            <h1 class="mb-[25px]">Aktualizace osobních údajů</h1>
        @else
            <h1 class="mb-[25px]" id="anchor-overeni-uctu">Ověření účtu</h1>
        @endif

        @if ($errors->any())
            <ul class="bg-app-red text-white p-[15px] rounded-[3px] mb-[50px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @include('profile.edit-account')
    </div>

    @include('app.@faq')
</x-app-layout>
