<div class="space-y-[25px] mt-[25px]">
    @if(count($payments['empty']))
        <div class="rounded-[7px] bg-white shadow-[0_3px_6px_rgba(0,0,0,0.16)] p-[20px]">
            <div>
                <h2 class="text-app-red">Bez přiřazení k projektu</h2>
            </div>

            <table class="w-full text-sm text-left text-gray-500 mt-[25px]">
                <thead class="text-s text-gray-700 bg-gray-50 border-b">
                <tr>
                    <th scope="col" class="px-6 py-4">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Datum platby
                    </th>
                    <th scope="col" class="px-6 py-4">
                        VS
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Částka
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Měna
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Protiúčet
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Identifikace protiúčtu
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Zpráva pro příjemce
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($payments['empty'] as $payment)
                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                        <td class="px-6 py-2">
                            {{ $payment->id }}
                        </td>
                        <td class="px-6 py-2">
                            {{ \Carbon\Carbon::create($payment->datum)->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $payment->vs }}
                        </td>
                        <td class="px-6 py-2">
                            {{ number_format($payment->castka, 2, ',', ' ') }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $payment->mena }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $payment->protiucet }} / {{ $payment->protiucet_kodbanky }}
                            <br>
                            {{ $payment->protiucet_nazevbanky }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $payment->protiucet_nazevprotiuctu }}
                            <br>
                            {{ $payment->protiucet_uzivatelska_identifikace }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $payment->zprava_pro_prijemce }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @foreach($payments['projectsList'] as $project)
        <div class="rounded-[7px] bg-white shadow-[0_3px_6px_rgba(0,0,0,0.16)] p-[20px]">
            <div>
                <h2><a href="{{ $project->url_detail }}" target="project-{{$project->id}}">
                        ({{ $project->id }}) {{ $project->title }}</a>
                </h2>
                <h3 class="mt-[5px]">požadovaná výše jistoty {{ $project->minimum_principal_text }}</h3>
            </div>

            <table class="w-full text-sm text-left text-gray-500 mt-[25px]">
                <thead class="text-s text-gray-700 bg-gray-50 border-b">
                <tr>
                    <th scope="col" class="px-6 py-4">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Stav
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Datum platby
                    </th>
                    <th scope="col" class="px-6 py-4">
                        VS
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Částka
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Měna
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Protiúčet
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Identifikace protiúčtu
                    </th>
                    <th scope="col" class="px-6 py-4">
                        Zpráva pro příjemce
                    </th>
                </tr>
                </thead>
                <tbody>
                @php
                    $odd = false;
                @endphp
                @foreach($project->payments()
                    ->selectRaw(
                        'min(id) as id,'.
                        'min(datum) as datum,'.
                        'min(mena) as mena,'.
                        'min(protiucet) as protiucet,'.
                        'min(protiucet_kodbanky) as protiucet_kodbanky,'.
                        'min(protiucet_nazevbanky) as protiucet_nazevbanky,'.
                        'min(protiucet_nazevprotiuctu) as protiucet_nazevprotiuctu,'.
                        'min(protiucet_uzivatelska_identifikace) as protiucet_uzivatelska_identifikace,'.
                        'min(zprava_pro_prijemce) as zprava_pro_prijemce,'.
                        'vs,'.
                        'sum(castka) as castka,'.
                        'count(*) as count'
                        )->groupBy(['vs'])->get() as $paymentX
                    )
                    @php
                        $odd = !$odd;
                    @endphp
                    <tr class="{{ $odd ? 'bg-white' : 'bg-gray-50' }} {{ $paymentX->count === 1 ? 'border-b' : '' }} font-bold">
                        <td class="px-6 py-2">
                            {{ $paymentX->count === 1 ? $paymentX->id : '' }}
                        </td>
                        <td class="px-6 py-1">
                            @if(\App\Models\ProjectShow::where('variable_symbol', $paymentX->vs)->first()->principal_paid)
                                <div class="bg-app-green rounded-[3px] text-white text-center p-[5px]">
                                    Zaplaceno
                                </div>
                            @else
                                <div class="bg-app-orange rounded-[3px] text-white text-center p-[5px]">
                                    Nezaplaceno
                                </div>
                            @endif

                            @if(
                                !\App\Models\ProjectShow::where('variable_symbol', $paymentX->vs)->first()->principal_paid
                                || $paymentX->castka != $project->minimum_principal
                            )
                                @if($paymentX->castka > $project->minimum_principal)
                                    <div class="font-normal text-xs text-app-blue text-center p-[5px]">
                                        Uhrazeno {{ number_format($paymentX->castka, 2, ',', ' ') }} Kč
                                    </div>
                                @elseif($paymentX->castka < $project->minimum_principal)
                                    <div class="font-normal text-xs text-app-red text-center p-[5px]">
                                        Uhrazeno {{ number_format($paymentX->castka, 2, ',', ' ') }} Kč
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            {{ $paymentX->count === 1 ? \Carbon\Carbon::create($paymentX->datum)->format('d.m.Y') : '' }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $paymentX->vs }}
                        </td>
                        <td class="px-6 py-2">
                            {{ number_format($paymentX->castka, 2, ',', ' ') }}
                        </td>
                        <td class="px-6 py-2">
                            {{ $paymentX->count === 1 ? $paymentX->mena : '' }}
                        </td>
                        <td class="px-6 py-2">
                            @if($paymentX->count === 1)
                                {{ $paymentX->protiucet }} / {{ $paymentX->protiucet_kodbanky }}
                                <br>
                                {{ $paymentX->protiucet_nazevbanky }}
                            @else
                                &nbsp;<br>&nbsp;
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            @if($paymentX->count === 1)
                                {{ $paymentX->protiucet_nazevprotiuctu }}
                                <br>
                                {{ $paymentX->protiucet_uzivatelska_identifikace }}
                            @else
                                &nbsp;<br>&nbsp;
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            {{ $paymentX->count === 1 ? $paymentX->zprava_pro_prijemce : '' }}
                        </td>
                    </tr>

                    @if($paymentX->count > 1)
                        @foreach(\App\Models\Payment::where('vs', $paymentX->vs)->orderBy('datum', 'desc')->get() as $payment)
                            <tr class="{{ $odd ? 'bg-white' : 'bg-red' }} {{ $paymentX->count === $loop->iteration ? 'border-b' : '' }} text-xs">
                                <td class="px-6 py-1">
                                    {{ $payment->id }}
                                </td>
                                <td class="px-6 py-1">
                                </td>
                                <td class="px-6 py-1">
                                    {{ \Carbon\Carbon::create($payment->datum)->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-1">
                                    {{ $payment->vs }}
                                </td>
                                <td class="px-6 py-1">
                                    {{ number_format($payment->castka, 2, ',', ' ') }}
                                </td>
                                <td class="px-6 py-1">
                                    {{ $payment->mena }}
                                </td>
                                <td class="px-6 py-1">
                                    {{ $payment->protiucet }} / {{ $payment->protiucet_kodbanky }}
                                    <br>
                                    {{ $payment->protiucet_nazevbanky }}
                                </td>
                                <td class="px-6 py-1">
                                    {{ $payment->protiucet_nazevprotiuctu }}
                                    <br>
                                    {{ $payment->protiucet_uzivatelska_identifikace }}
                                </td>
                                <td class="px-6 py-1">
                                    {{ $payment->zprava_pro_prijemce }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
