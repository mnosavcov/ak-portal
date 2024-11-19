@if($project->myShow()->first()->variable_symbol !== null && $project->myShow()->first()->principal_sum === null)
    <form x-data="{
                        loaderShow: false,
                        seconds: @js(session('after') ?? 0),
                        display: '',
                        afterText() {
                            if(this.seconds > 0) {
                                return '(další pokus můžete provést za ' + this.display + ')'
                            } else {
                                return '';
                            }
                        },
                        countdown(data) {
                            var interval = setInterval(function() {
                                if (data.seconds <= 0) {
                                    clearInterval(interval);
                                } else {
                                    var minutes = Math.floor(data.seconds / 60); // Celé minuty
                                    var remainingSeconds = data.seconds % 60; // Zbývající sekundy

                                    // Formátování výstupu
                                    if(minutes > 0) {
                                        data.display = minutes.toString().padStart(2, '0') + ':' + remainingSeconds.toString().padStart(2, '0');
                                    } else {
                                        data.display = remainingSeconds.toString().padStart(2, '0') + 's';
                                    }

                                    data.seconds--; // Snížení sekund o jednu
                                }
                            }, 1000); // Každou sekundu
                        }
                      }"
          x-init="countdown($data)"
          method="post"
          action="{{ route('projects.show', ['project' => $project->url_part]) }}"
          @submit="loaderShow = true;">
        @csrf
        <input type="hidden" name="check-payment" value="1">
        <input type="hidden" name="vs" value="{{ $project->myShow()->first()->variable_symbol }}">
        <button type="submit"
                class="text-[#888888] font-Spartan-Regular
                         text-[12px] mt-[15px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mt-[20px] laptop:leading-[26px]
                            "
        >
            {{ __('zkontrolovat platbu') }}
            <span x-text="afterText()" class="block"></span>
        </button>

        <div id="loader" x-show="loaderShow" x-cloak>
            <span class="loader"></span>
        </div>
    </form>
@endif

@if($project->myShow()->first()->principal_sum !== null)
    <div type="submit"
         class="text-[#888888] font-Spartan-Regular
                         text-[12px] mt-[15px] leading-[22px]
                         tablet:text-[13px] tablet:leading-[24px]
                         laptop:text-[15px] laptop:mt-[20px] laptop:leading-[26px]
                            "
    >
        <span class="text-app-red">{{ __('Jistota není uhrazena.') }}</span>
        <br>
        {{ __('Evidujeme zaplacenou částku') }}
        {{ number_format($project->myShow()->first()->principal_sum, 0, '.', ' ') }} Kč.
    </div>
@endif
