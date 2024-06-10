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

    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

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
        @if (session('project-added'))
            <div class="mx-[15px]">
                <div
                    class="text-center w-[calc(100%-[30px])] max-w-[1200px] mx-auto bg-app-orange text-white rounded-[3px] p-[15px] block my-[25px]">
                    {{ session('project-added') }}
                </div>
            </div>
        @endif

        <x-app.top-content
            imgSrc="{{ Vite::asset('resources/images/top-img-homepage.png') }}"
            header="On-line tržiště projektů FVE"
        >
            <div class="max-w-[900px] mx-auto">
                <div class="grid gap-y-[20px] tablet:gap-y-[25px] laptop:gap-y-[30px]">
                    <div class="font-WorkSans-Regular text-white text-[16px] leading-[19px]
                    tablet:text-[19px] tablet:leading-[22px] tablet:order-2
                    laptop:text-[22px] laptop:leading-[25px] laptop:order-1
                    ">
                        Prodej a nákup projektů na výstavbu fotovoltaiky – od rané fáze až po příležitosti s platným
                        stavebním povolením a rezervovaným výkonem v distribuční soustavě.
                    </div>
                    <div class="font-WorkSans-Regular text-white text-[16px] leading-[19px]
                    tablet:text-[19px] tablet:leading-[22px] tablet:order-1
                    laptop:text-[22px] laptop:leading-[25px] laptop:order-2
                    ">
                        Prodej a nákup existujících FVE.
                    </div>
                </div>

                <div class="h-[75px]"></div>
            </div>
        </x-app.top-content>

        <div class="max-w-[1230px] w-full mx-auto relative">
            <div
                class="mx-[15px] py-[50px] bg-white rounded-[3px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center mb-[50px] md:mb-[70px]
                    px-[15px] md:px-[30px]">
                <div class="mx-auto font-WorkSans-SemiBold text-[#414141] max-w-[820px]
                    text-[18px] mb-[30px]
                    md:text-[28px] md:mb-[50px]
                    ">Spuštění projektu jsme z provozně-technických důvodů odložili. Aktuální předpoklad zveřejnění je
                    <span class="text-app-green">{{ $date->format('j.n.Y') }}&nbsp;ve&nbsp;{{ $date->format('H:i') }}</span>
                </div>

                <div class="border border-app-orange mx-auto
                    grid-cols-[1fr_auto_1fr_auto_1fr_auto_1fr] max-w-[450px] px-[15px] pt-[10px] pb-[10px] mb-[30px] grid
                    md:grid-cols-[80px_100px_80px_100px_80px_100px_80px] md:max-w-none md:inline-grid md:pt-[20px] md:pb-[35px] md:mb-[40px]
                    lg:grid-cols-[80px_120px_80px_120px_80px_120px_80px] lg:px-[60px] lg:mb-[50px]
                    " id="countdown">

                    <div>
                        <div id="days" class="poppins-light text-app-orange
                        text-[30px] md:text-[60px]">{{ $days }}</div>
                        <div class="poppins-regular text-[12px] md:text-[16px] text-[#31363A]">DNÍ</div>
                    </div>

                    <div class="flex items-center justify-center md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="3.018" height="12.406"
                             viewBox="0 0 3.018 12.406">
                            <g id="Group_20813" data-name="Group 20813" transform="translate(-175.983 -529)">
                                <path id="Path_25373" data-name="Path 25373"
                                      d="M214.271,79.117a1.509,1.509,0,1,0-1.509-1.509,1.509,1.509,0,0,0,1.509,1.509"
                                      transform="translate(-36.779 452.902)" fill="#31363a"/>
                                <path id="Path_25374" data-name="Path 25374"
                                      d="M214.271,96.837a1.509,1.509,0,1,0,1.509,1.509,1.509,1.509,0,0,0-1.509-1.509"
                                      transform="translate(-36.779 441.551)" fill="#31363a"/>
                            </g>
                        </svg>
                    </div>
                    <div class="items-center justify-center hidden md:flex">
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
                        <div id="hours" class="poppins-light text-app-orange
                        text-[30px] md:text-[60px]">{{ $hours }}</div>
                        <div class="poppins-regular text-[12px] md:text-[16px] text-[#31363A]">HODIN</div>
                    </div>

                    <div class="flex items-center justify-center md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="3.018" height="12.406"
                             viewBox="0 0 3.018 12.406">
                            <g id="Group_20813" data-name="Group 20813" transform="translate(-175.983 -529)">
                                <path id="Path_25373" data-name="Path 25373"
                                      d="M214.271,79.117a1.509,1.509,0,1,0-1.509-1.509,1.509,1.509,0,0,0,1.509,1.509"
                                      transform="translate(-36.779 452.902)" fill="#31363a"/>
                                <path id="Path_25374" data-name="Path 25374"
                                      d="M214.271,96.837a1.509,1.509,0,1,0,1.509,1.509,1.509,1.509,0,0,0-1.509-1.509"
                                      transform="translate(-36.779 441.551)" fill="#31363a"/>
                            </g>
                        </svg>
                    </div>
                    <div class="items-center justify-center hidden md:flex">
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
                        <div id="minutes" class="poppins-light text-app-orange
                        text-[30px] md:text-[60px]">{{ $minutes }}</div>
                        <div class="poppins-regular text-[12px] md:text-[16px] text-[#31363A]">MINUT</div>
                    </div>

                    <div class="flex items-center justify-center md:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="3.018" height="12.406"
                             viewBox="0 0 3.018 12.406">
                            <g id="Group_20813" data-name="Group 20813" transform="translate(-175.983 -529)">
                                <path id="Path_25373" data-name="Path 25373"
                                      d="M214.271,79.117a1.509,1.509,0,1,0-1.509-1.509,1.509,1.509,0,0,0,1.509,1.509"
                                      transform="translate(-36.779 452.902)" fill="#31363a"/>
                                <path id="Path_25374" data-name="Path 25374"
                                      d="M214.271,96.837a1.509,1.509,0,1,0,1.509,1.509,1.509,1.509,0,0,0-1.509-1.509"
                                      transform="translate(-36.779 441.551)" fill="#31363a"/>
                            </g>
                        </svg>
                    </div>
                    <div class="items-center justify-center hidden md:flex">
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
                        <div id="seconds" class="poppins-light text-app-orange
                        text-[30px] md:text-[60px]">{{ $seconds }}</div>
                        <div class="poppins-regular text-[12px] md:text-[16px] text-[#31363A]">SEKUND</div>
                    </div>
                </div>

                <div class="px-[10px] py-[15px] mb:px-[50px] mb:py-[50px] w-full bg-[#F8F8F8] rounded-[3px]" x-data="{
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
                    <div
                        class="mb-[20px] md:mb-[30px] font-WorkSans-SemiBold text-[18px] md:text-[28px] text-[#414141]">
                        Zadejte svůj e-mail a spuštění vám připomeneme
                    </div>

                    <div x-cloak x-show="success" x-collapse>
                        <div
                            class="py-[15px] bg-app-orange inline-block md:px-[100px] text-[14px] md:text-[28px] w-full md:w-auto min-h-[50px] leading-[25px] md:leading-[50px] mb-[25px] rounded-[3px] text-white font-Spartan-Regular">
                            Váš e-mail byl úspěšně odeslaný. Jakmile bude naše platforma oficiálně spuštěna, budeme vás
                            kontaktovat.
                        </div>
                    </div>

                    <div class=""></div>

                    <label class="block mb-[10px] font-Spartan-Bold text-[13px] text-[#676464]">Váš e-mail</label>
                    <form x-ref="form">
                        <x-text-input id="email" class="block mb-[25px] w-[350px] max-w-full mx-auto" type="email"
                                      name="email"
                                      @input="isValid()" @change="isValid()"
                                      required x-model="email"/>
                    </form>

                    <div></div>
                    <div
                        class="inline-grid grid-cols-[20px_1fr] gap-x-[15px] min-h-[50px] max-md:py-[15px] max-md:text-[12px] bg-app-blue text-white font-Spartan-SemiBold rounded-[7px] content-center px-[15px] mb-[30px]">
                        <div
                            class="relative inline-block w-[20px] h-[20px] border border-[#e2e2e2] rounded-[3px] bg-white"
                            @click="confirm = !confirm">
                            <div x-show="confirm" x-cloak
                                 class="absolute top-[2px] left-[2px] w-[14px] h-[14px] rounded-[3px] bg-app-green"></div>
                        </div>
                        <div @click="confirm = !confirm" class="text-left md:text-center max-md:leading-[22px]">
                            Odesláním souhlasím se <a href="{{ route('zpracovani-osobnich-udaju') }}" target="_blank"
                                                      class="underline cursor-pointer">Zásadami
                                zpracování osobních údajů</a></div>
                    </div>

                    <div class=""></div>

                    <button type="button"
                            class="h-[50px] leading-[50px] md:h-[60px] md:leading-[60px] md:px-[100px] w-full md:w-auto font-Spartan-Bold text-[18px] text-white bg-app-green rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)] disabled:grayscale"
                            :disabled="!confirm || !valid"
                            @click="sendRegister()"
                    >
                        Odeslat
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-[1230px] w-full mx-auto relative">
            <div
                class="mx-[15px] py-[30px] md:py-[50px] bg-white rounded-[3px] shadow-[0_3px_55px_rgba(0,0,0,0.16)] text-center mb-[50px] md:mb-[100px]
                    px-[15px] md:px-[30px]">

                <div class="mx-[15px] font-WorkSans-SemiBold text-[#414141]
                    text-[18px] mb-[30px]
                    md:text-[28px] md:mb-[50px]
                    ">Máte FVE projekt na prodej?
                </div>

                <div class="max-w-[1200px] mx-auto text-center mb-[40px] md:mb-[60px]">
                    <a href="{{ route('projects.add') }}"
                       class="inline-block font-Spartan-Regular text-white rounded-[3px] shadow-[0_3px_6px_rgba(0,0,0,0.16)]
                             text-[14px] h-[50px] leading-[50px] w-full max-w-[350px] justify-self-center
                             tablet:text-[16px] tablet:h-[55px] tablet:leading-[55px] tablet:justify-self-start
                             laptop:text-[18px] laptop:h-[60px] laptop:leading-[60px] bg-app-orange
                             "><span
                            class="font-Spartan-Bold">Chci nabídnout</span> projekt
                    </a>
                </div>

                <div class="text-center">
                    <div class="mb-[30px] font-Spartan-Regular text-[16px] md:text-[20px] text-[#414141]">Máte dotazy? Potřebujete více informací?</div>

                    <a href="mailto:info@pvtrusted.cz" class="inline-block text-[13px] md:text-[15px] font-Spartan-Bold text-app-orange">info@pvtrusted.cz</a>
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
            let now = new Date();
            now.setHours(now.getHours() + 2);
            now = now.getTime();
            const distance = target - now;

            if (distance < 0) {
                clearInterval(interval);
                window.location.reload()
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
