@if(auth()->user() && auth()->user()->isSuperadmin())
    @include('components.app.project.part.offer.list.@offer-list', ['userType' => 'superadmin'])
@elseif($project->isMine())
    @include('components.app.project.part.offer.list.@offer-list', ['userType' => 'advertiser'])
@else
    @include('components.app.project.part.offer.list.@offer-list', ['userType' => 'investor'])
@endif
