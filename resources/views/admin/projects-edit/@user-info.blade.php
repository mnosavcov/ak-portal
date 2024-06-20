<div class="relative w-full max-w-[1200px] p-[15px] pl-[50px] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] leading-[24px]
                    {{ $project->user->check_status === 'verified' ?
                        'bg-[#d8d8d8] after:bg-[url("/resources/images/ico-info-orange.svg")] text-[#676464]'
                        : 'bg-app-orange after:bg-[url("/resources/images/ico-info-gray.svg")] text-[#222]'
                    }}
                    after:absolute after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Zadavatel</div>

    <div class="w-full grid grid-cols-5 gap-x-[20px] gap-y-[10px]">
        <div>
            <div class="font-bold">titul&nbsp;před:</div>
            <div>{{ $project->user->title_before }}</div>
        </div>
        <div>
            <div class="font-bold">Jméno:</div>
            <div>{{ $project->user->name }}</div>
        </div>
        <div>
            <div class="font-bold">Příjmení:</div>
            <div>{{ $project->user->surname }}</div>
        </div>
        <div>
            <div class="font-bold">titul&nbsp;za:</div>
            <div>{{ $project->user->title_after }}</div>
        </div>
        <div></div>

        <div>
            <div class="font-bold">Ulice:</div>
            <div>{{ $project->user->street }}</div>
        </div>
        <div>
            <div class="font-bold">Č.p.:</div>
            <div>{{ $project->user->street_number }}</div>
        </div>
        <div>
            <div class="font-bold">Město:</div>
            <div>{{ $project->user->city }}</div>
        </div>
        <div>
            <div class="font-bold">Psč:</div>
            <div>{{ $project->user->psc }}</div>
        </div>
        <div>
            <div class="font-bold">Stát:</div>
            <div>{{ $project->user->country }}</div>
        </div>

        <div>
            <div class="font-bold">Email:</div>
            <div><a href="mailto:{{ $project->user->email }}">{{ $project->user->email }}</a></div>
        </div>
        <div>
            <div class="font-bold">Telefon:</div>
            <div><a href="tel:{{ $project->user->phone_number }}">{{ $project->user->phone_number }}</a>
            </div>
        </div>
        <div>
            <div class="font-bold">Investor:</div>
            <div>{!! $project->user->investor ? '<span class="bg-app-green p-[5px] rounded-[3px] text-white">Ano</span>' : '<span class="bg-app-red p-[5px] rounded-[3px] text-white">Ne</span>' !!}</div>
        </div>
        <div>
            <div class="font-bold">Nabízející:</div>
            <div>{!! $project->user->advertiser ? '<span class="bg-app-green p-[5px] rounded-[3px] text-white">Ano</span>' : '<span class="bg-app-red p-[5px] rounded-[3px] text-white">Ne</span>' !!}</div>
        </div>
        <div>
            <div class="font-bold">Real. makléř:</div>
            <div>{!! $project->user->real_estate_broker ? '<span class="bg-app-green p-[5px] rounded-[3px] text-white">Ano</span>' : '<span class="bg-app-red p-[5px] rounded-[3px] text-white">Ne</span>' !!}</div>
        </div>

        <div class="col-span-4">
            <div class="font-bold">Více informací:</div>
            <div>{{ $project->user->more_info }}</div>
        </div>

        <div>
            <div class="font-bold">Ověřený:</div>
            <div>{!! $project->user->check_status === 'verified' ? '<span class="bg-app-green p-[5px] rounded-[3px] text-white">Ano</span>' : '<span class="bg-app-red p-[5px] rounded-[3px] text-white">Ne</span>' !!}</div>
        </div>
    </div>
</div>
