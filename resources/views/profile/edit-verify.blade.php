<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            'Nastavení účtu' => route('profile.edit'),
        ]"></x-app.breadcrumbs>
    </div>

    <div class="w-full max-w-[1230px] mx-auto px-[15px]">

        <h1 class="mb-[25px]">Ověření účtu</h1>

        @if ($errors->any())
            <ul class="bg-app-red text-white p-[15px] rounded-[3px] mb-[50px]">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @include('profile.edit-account')
    </div>

    <div class="pt-[50px] laptop:pt-[100px] bg-white">
        @include('app.@faq')
    </div>
</x-app-layout>
