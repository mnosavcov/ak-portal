<div class="font-Spartan-Regular text-[#414141]
                text-[15px] leading-[20px] mb-[15px]
                tablet:text-[17px] tablet:leading-[24px] tablet:mb-[20px]
                laptop:text-[20px] laptop:leading-[30px]">
    @if($project->type === 'auction')
        Příhozy
    @else
        Obdržené nabídky
    @endif
</div>

<div id="price-box-bid-list">
    @include('components.app.project.part.offer.list.@offer-list', ['userType' => 'superadmin'])
</div>
