<div x-show="projectShow === 'lokace'" x-cloak class="col-span-full"
     x-data="{
        loadMap: false,
     }"
     x-init="
        $watch('projectShow', value => {
            if(value === 'lokace' && loadMap === false) {
                loadMap = true;
                const script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&libraries=marker';
                script.defer = true;
                document.body.appendChild(script);
            }
        })
    "
>
    @if($project->isVerified())
        <script>
            let map;

            async function initMap() {
                var myLatLng = {
                    lat: {{ trim(explode(',', $project->map_lat_lng)[0]) }},
                    lng: {{ trim(explode(',', $project->map_lat_lng)[1] ?? '0') }}
                };

                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: {{ $project->map_zoom }},
                    center: myLatLng,
                    mapId: "PVtrusted",
                    mapTypeId: '{{ $project->map_type }}'
                });

                const marker = new google.maps.marker.AdvancedMarkerElement({
                    map,
                    position: myLatLng,
                    title: '{{ trim($project->map_title ?? '') }}',
                });
            }
        </script>
        <div class="w-full relative aspect-[4/3]" id="map">
            <div class="w-full h-[25%] grid content-center justify-center">
                <div class="inline-loader mt-[4px] w-[32px] h-[32px]" x-show="!loadMap" x-cloak>
                    <span class="loader"></span>
                </div>
            </div>
        </div>
    @else
        @if(auth()->guest())
            jen pro přihlášené
        @elseif (!auth()->user()->investor)
            jen pro investory
        @else
            Lokalita není dostupná
        @endif
    @endif
</div>
