<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.less'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <style>
        .poppins-thin {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: normal;
        }

        .poppins-extralight {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: normal;
        }

        .poppins-light {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .poppins-medium {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .poppins-semibold {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .poppins-bold {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .poppins-extrabold {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: normal;
        }

        .poppins-black {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: normal;
        }

        .poppins-thin-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 100;
            font-style: italic;
        }

        .poppins-extralight-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 200;
            font-style: italic;
        }

        .poppins-light-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 300;
            font-style: italic;
        }

        .poppins-regular-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: italic;
        }

        .poppins-medium-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-style: italic;
        }

        .poppins-semibold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: italic;
        }

        .poppins-bold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-style: italic;
        }

        .poppins-extrabold-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 800;
            font-style: italic;
        }

        .poppins-black-italic {
            font-family: "Poppins", sans-serif;
            font-weight: 900;
            font-style: italic;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#f8f8f8] text-[#31363A]">
<div class="min-h-screen">
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-[1230px] mx-auto">
            <div class="flex justify-between h-16 mx-[15px]">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('homepage') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        <x-app.top-content
            imgSrc="{{ Vite::asset('resources/images/top-img-homepage.png') }}"
            header="On-line tržiště projektů FVE"
        >
            <div class="max-w-[900px] mx-auto">
                <div class="grid gap-y-[20px] tablet:gap-y-[25px] laptop:gap-y-[30px]">
                    <div class="font-WorkSans-Regular text-white text-[16px] leading-[19px] order-2
                    tablet:text-[19px] tablet:leading-[22px] tablet:order-2
                    laptop:text-[22px] laptop:leading-[25px] laptop:order-1
                    ">
                        Prodej a nákup projektů na výstavbu fotovoltaiky – od rané fáze až po příležitosti s platným
                        stavebním povolením a rezervovaným výkonem v distribuční soustavě.
                    </div>
                    <div class="font-WorkSans-Regular text-white text-[16px] leading-[19px] order-1
                    tablet:text-[19px] tablet:leading-[22px] tablet:order-1
                    laptop:text-[22px] laptop:leading-[25px] laptop:order-2
                    ">
                        Prodej a nákup existujících FVE.
                    </div>
                </div>

                <div class="h-[65px]"></div>
            </div>
        </x-app.top-content>

        <div class="max-w-[1230px] w-full mx-auto relative">
            <div
                class="mx-[15px] py-[50px] px-[30px] bg-white rounded-[3px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center mb-[100px]">
                <div class="mx-auto mb-[50px] font-WorkSans-SemiBold text-[28px] text-[#414141]">Projekt zveřejníme již
                    <span class="text-app-green">{{ $date->format('j.n.Y') }} v {{ $date->format('H:i') }}</span>
                </div>

                <div class="px-[60px] border  border-app-orange pt-[20px] pb-[35px] mb-[50px]
                    inline-grid grid-cols-[80px_120px_80px_120px_80px_120px_80px]
                    " id="countdown">

                    <div>
                        <div id="days" class="poppins-light text-[60px] text-app-orange">{{ $days }}</div>
                        <div class="poppins-regular text-[16px] text-[#31363A]">DNÍ</div>
                    </div>

                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4.912" height="25.652"
                             viewBox="0 0 4.912 25.652">
                            <g id="Group_20803" data-name="Group 20803" transform="translate(-736.824 -626.098)">
                                <path id="Path_25373" data-name="Path 25373"
                                      d="M215.218,81.011a2.457,2.457,0,1,0-2.456-2.456,2.456,2.456,0,0,0,2.456,2.456"
                                      transform="translate(524.063 550)" fill="#31363a"/>
                                <path id="Path_25374" data-name="Path 25374"
                                      d="M215.218,96.837a2.457,2.457,0,1,0,2.456,2.456,2.456,2.456,0,0,0-2.456-2.456"
                                      transform="translate(524.063 550)" fill="#31363a"/>
                            </g>
                        </svg>
                    </div>

                    <div>
                        <div id="hours" class="poppins-light text-[60px] text-app-orange">{{ $hours }}</div>
                        <div class="poppins-regular text-[16px] text-[#31363A]">HODIN</div>
                    </div>

                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4.912" height="25.652"
                             viewBox="0 0 4.912 25.652">
                            <g id="Group_20803" data-name="Group 20803" transform="translate(-736.824 -626.098)">
                                <path id="Path_25373" data-name="Path 25373"
                                      d="M215.218,81.011a2.457,2.457,0,1,0-2.456-2.456,2.456,2.456,0,0,0,2.456,2.456"
                                      transform="translate(524.063 550)" fill="#31363a"/>
                                <path id="Path_25374" data-name="Path 25374"
                                      d="M215.218,96.837a2.457,2.457,0,1,0,2.456,2.456,2.456,2.456,0,0,0-2.456-2.456"
                                      transform="translate(524.063 550)" fill="#31363a"/>
                            </g>
                        </svg>
                    </div>

                    <div>
                        <div id="minutes" class="poppins-light text-[60px] text-app-orange">{{ $minutes }}</div>
                        <div class="poppins-regular text-[16px] text-[#31363A]">MINUT</div>
                    </div>

                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="4.912" height="25.652"
                             viewBox="0 0 4.912 25.652">
                            <g id="Group_20803" data-name="Group 20803" transform="translate(-736.824 -626.098)">
                                <path id="Path_25373" data-name="Path 25373"
                                      d="M215.218,81.011a2.457,2.457,0,1,0-2.456-2.456,2.456,2.456,0,0,0,2.456,2.456"
                                      transform="translate(524.063 550)" fill="#31363a"/>
                                <path id="Path_25374" data-name="Path 25374"
                                      d="M215.218,96.837a2.457,2.457,0,1,0,2.456,2.456,2.456,2.456,0,0,0-2.456-2.456"
                                      transform="translate(524.063 550)" fill="#31363a"/>
                            </g>
                        </svg>
                    </div>

                    <div>
                        <div id="seconds" class="poppins-light text-[60px] text-app-orange">{{ $seconds }}</div>
                        <div class="poppins-regular text-[16px] text-[#31363A]">SEKUND</div>
                    </div>
                </div>

                <div class="p-[50px] w-full bg-[#F8F8F8] rounded-[3px]" x-data="{
                    confirm: false,
                    valid: false,
                    success: false,
                    email: '',
                    isValid() {
                        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        this.valid = this.$refs.form.checkValidity() && re.test(String(this.email).toLowerCase());
                    },
                    async sendRegister() {
                        await fetch('/save-email', {
                            method: 'POST',
                            body: JSON.stringify({
                                email: this.email,
                            }),
                            headers: {
                                'Content-type': 'application/json; charset=UTF-8',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                            },
                        }).then((response) => response.json())
                            .then((data) => {
                                if(data.status === 'ok') {
                                    this.email = '';
                                    this.success = true;
                                }
                            })
                            .catch((error) => {
                                alert('Chyba vložení emailu')
                            });
                    }
                    }">
                    <div class="mb-[30px] font-WorkSans-SemiBold text-[28px] text-[#414141]">
                        Zadejte svůj e-mail a spuštění vám připomeneme
                    </div>

                    <div x-cloak x-show="success" x-collapse>
                        <div class="bg-app-orange inline-block px-[100px] h-[50px] leading-[50px] mb-[25px] rounded-[3px] text-white font-Spartan-Regular">
                            Email byl úspěšně uložený
                        </div>
                    </div>

                    <div class=""></div>

                    <label class="block mb-[10px] font-Spartan-Bold text-[13px] text-[#676464]">Váš e-mail</label>
                    <form x-ref="form">
                        <x-text-input id="email" class="block mb-[25px] w-[350px] mx-auto" type="email" name="email"
                                      @input="isValid()" @change="isValid()"
                                      required autofocus x-model="email"/>
                    </form>

                    <div></div>
                    <div
                        class="inline-grid grid-cols-[20px_1fr] gap-x-[15px] h-[50px] bg-app-blue text-white font-Spartan-SemiBold rounded-[7px] content-center px-[15px] mb-[30px]">
                        <div
                            class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                            @click="confirm = !confirm">
                            <div x-show="confirm" x-cloak
                                 class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"></div>
                        </div>
                        <div @click="confirm = !confirm">Odesláním souhlasím se <a href="#"
                                                                                   class="underline cursor-pointer">Zásadami
                                zpracování osobních údajů</a></div>
                    </div>

                    <div class=""></div>

                    <button type="button"
                            class="h-[60px] leading-[60px] px-[100px] font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale"
                            :disabled="!confirm || !valid"
                            @click="sendRegister()"
                    >
                        Odeslat
                    </button>
                </div>
            </div>
        </div>
    </main>

    <div
        class="w-full bg-white text-center font-Spartan-Regular text-[13px] leading-[60px] h-[60px] px-[30px]">
        &copy;{{ date('Y') }} PVtrusted.cz
    </div>
</div>

<script>
    function countdown(targetDate) {
        const target = new Date(targetDate).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = target - now;

            if (distance < 0) {
                clearInterval(interval);
                document.getElementById("countdown").innerHTML = "EXPIRED";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").innerText = days;
            document.getElementById("hours").innerText = hours;
            document.getElementById("minutes").innerText = minutes;
            document.getElementById("seconds").innerText = seconds;
        }

        const interval = setInterval(updateCountdown, 1000);
    }

    // Vstupní datum, do kdy se má odpočítávat (ve formátu YYYY-MM-DDTHH:MM:SS)
    countdown('{{ $date->format('Y-m-dTH:i:s') }}');
</script>
</body>
</html>
