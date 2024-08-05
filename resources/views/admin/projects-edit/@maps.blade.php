<div
    x-data="{ open: true, mapEdit: false,
        map_lat_lng: @js($project->map_lat_lng),
        map_lat_lng_new: null,
        map_zoom: @js($project->map_zoom),
        map_zoom_new: null,
        map_title: @js($project->map_title),
        map_title_new: null
        }"
    class="relative w-full max-w-[1200px] p-[15px] pl-[50px] bg-[#d8d8d8] mb-[30px] rounded-[7px] font-Spartan-Regular text-[13px] text-[#676464] leading-[24px]
                    after:absolute after:bg-[url('/resources/images/ico-info-orange.svg')] after:w-[20px] after:h-[20px] after:left-[15px] after:top-[15px]">
    <div class="float-right cursor-pointer text-gray-700" x-text="open ? 'skrýt' : 'zobrazit'" @click="open = !open"
         x-show="Object.entries(projectDetails.data).length"></div>
    <div class="font-WorkSans-Bold text-[18px] mb-[10px]">Lokace</div>
    <div class="w-full grid gap-y-[25px]" x-show="open" x-collapse>
        <button type="button" @click="
                mapEdit = true;
                map_lat_lng_new = map_lat_lng;
                map_zoom_new = map_zoom;
                map_title_new = map_title;
                $dispatch('open-modal', 'set-map')
            "
                class="inline-block justify-self-start"
                x-text="(map_lat_lng && map_lat_lng.trim()) ? 'Editovat mapu' : 'Vytvořit mapu'"
        >
        </button>

        <input type="hidden" name="map_lat_lng" :value="map_lat_lng">
        <input type="hidden" name="map_zoom" :value="map_zoom">
        <input type="hidden" name="map_title" :value="map_title">

        <template x-if="map_lat_lng && map_lat_lng.trim()">
            <div
                class="p-[5px] rounded-[5px] relative overflow-hidden h-[500px] w-full grid content-center justify-center">
                <div>
                    <iframe class="absolute top-0 bottom-0 left-0 right-0"
                            width="100%"
                            height="100%"
                            style="border:0"
                            loading="lazy"
                            allowfullscreen
                            referrerpolicy="no-referrer-when-downgrade"
                            :src="'https://www.google.com/maps/embed/v1/view?key={{ env('GOOGLE_MAPS_API_KEY') }}&center=' + map_lat_lng + '&maptype=roadmap&zoom=' + map_zoom">
                    </iframe>

                    <div class="w-[32px] h-[32px] relative z-90 mt-[-32px]">
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                        <style type="text/css">
                            .st0 {
                                fill: #F93226;
                            }
                        </style>
                            <g>
                                <g>
                                    <path class="st0" d="M256,0C153.8,0,70.6,83.2,70.6,185.4c0,126.9,165.9,313.2,173,321c6.6,7.4,18.2,7.4,24.8,0
                                        c7.1-7.9,173-194.1,173-321C441.4,83.2,358.2,0,256,0z M256,278.7c-51.4,0-93.3-41.9-93.3-93.3s41.9-93.3,93.3-93.3
                                        s93.3,41.9,93.3,93.3S307.4,278.7,256,278.7z"/>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        </template>

        <x-modal name="set-map" :hidenable="false">
            <img src="{{ Vite::asset('resources/images/ico-close.svg') }}"
                 @click="$dispatch('close')"
                 class="cursor-pointer w-[20px] h-[20px] float-right absolute top-[15px] right-[15px]">

            <div class="p-[40px_10px] tablet:p-[50px_40px] text-center"
                 x-init="
                    $watch('mapEdit', value => {
                        const script = document.createElement('script');
                        script.src = 'https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&loading=async&libraries=marker';
                        script.defer = true;
                        document.body.appendChild(script);
                    })
                    $watch('show', value => {
                        if(value && map) {
                            const [latitude, longitude] = map_lat_lng_new.split(',');
                            const lat = parseFloat(latitude.trim());
                            const lng = parseFloat(longitude.trim());
                            const center = new google.maps.LatLng(lat, lng);
                            map.setCenter(center);
                            marker.position = center;
                            map.setZoom(parseInt(map_zoom_new));
                            marker.title = map_title_new;
                        }
                    })
                ">

                <div class="text-left mb-[15px]">
                    <x-input-label for="map_lat_lng_new" :value="__('Latitude longitude')"/>
                    <x-text-input type="text" x-model="map_lat_lng_new" class="w-full"
                                  @change="
                                    const [latitude, longitude] = map_lat_lng_new.split(',');
                                    const lat = parseFloat(latitude.trim());
                                    const lng = parseFloat(longitude.trim());
                                    const center = new google.maps.LatLng(lat, lng);
                                    map.setCenter(center);
                                    marker.position = center;
                                    "
                    />
                </div>

                <div class="text-left mb-[15px]">
                    <x-input-label for="map_zoom_new" :value="__('Zoom')"/>
                    <select type="text" x-model="map_zoom_new" class="w-[195px]"
                            @change="map.setZoom(parseInt(map_zoom_new))"
                    >
                        @for($i = 0; $i <= 21; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="text-left mb-[15px]">
                    <x-input-label for="map_title_new" :value="__('Popis')"/>
                    <x-text-input type="text" x-model="map_title_new" class="w-full"
                                  @change="marker.title = map_title_new;"
                    />
                </div>

                <script>
                    let map;
                    let marker;

                    async function initMap() {
                        var myLatLng = {
                            lat: {{ trim(explode(',', $project->map_lat_lng)[0]) === '' ? '0' : trim(explode(',', $project->map_lat_lng)[0]) }},
                            lng: {{ trim(explode(',', $project->map_lat_lng)[1] ?? '0') }}
                        };

                        map = new google.maps.Map(document.getElementById('map'), {
                            zoom: {{ $project->map_zoom }},
                            center: myLatLng,
                            mapId: "PVtrusted",
                            mapTypeId: '{{ $project->map_type }}'
                        });

                        marker = new google.maps.marker.AdvancedMarkerElement({
                            map,
                            position: myLatLng,
                            title: '{{ trim($project->map_title ?? '') }}',
                        });
                    }
                </script>
                <div class="w-full relative aspect-[4/3]" id="map"></div>

                <button type="button" class="bg-app-blue text-white mt-[25px] rounded-[3px] p-[10px_25px]"
                        @click="
                            map_lat_lng = map_lat_lng_new;
                            map_zoom = map_zoom_new;
                            map_title = map_title_new;
                            $dispatch('close')
                        ">
                    ok
                </button>
            </div>
        </x-modal>
    </div>
</div>
