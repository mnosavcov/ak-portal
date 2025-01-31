<x-app-layout>
    <div class="w-full max-w-[1230px] mx-auto">
        <x-app.breadcrumbs :breadcrumbs="[
            __('Ověření účtu') => route('profile.edit-verify'),
        ]"></x-app.breadcrumbs>
    </div>

    <div class="w-full max-w-[1230px] mx-auto px-[15px]">

        <h1 class="mb-[25px]" id="anchor-overeni-uctu">{{ __('Ověření účtu') }}</h1>

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
