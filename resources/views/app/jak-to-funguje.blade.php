<x-app-layout>
    <div x-data="projectEdit">
        <div class="w-full max-w-[1230px] mx-auto">
            <x-app.breadcrumbs :breadcrumbs="[
            'Jak to funguje' => route('jak-to-funguje')
        ]"></x-app.breadcrumbs>
        </div>
    </div>

    @include('app.@faq')
</x-app-layout>
