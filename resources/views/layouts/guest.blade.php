<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/styles/tailwind.css') }}" />
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body class="text-blueGray-700 antialiased">

    <main>
        <section class="relative w-full">

            <div class=" mx-auto px-4">
                {{ $slot }}
            </div>
            <footer class="absolute w-full bottom-0 bg-blueGray-800 pb-6">
                <div class="container mx-auto px-4">
                    <hr class="mb-6 border-b-1 border-blueGray-600" />
                    <div class="flex flex-wrap items-center md:justify-between justify-center">
                        <div class="w-full md:w-4/12 px-4">
                            <div class="text-sm text-white font-semibold py-1 text-center md:text-left">
                                Copyright © <span id="get-current-year"></span>
                                <a href="https://www.creative-tim.com?ref=njs-login"
                                    class="text-white hover:text-blueGray-300 text-sm font-semibold py-1">Kingdomac</a>
                            </div>
                        </div>
                        <div class="w-full md:w-8/12 px-4">
                            <ul class="flex flex-wrap list-none md:justify-end justify-center">
                                <li>
                                    <a href="https://www.creative-tim.com?ref=njs-login"
                                        class="text-white hover:text-blueGray-300 text-sm font-semibold block py-1 px-3">Creative
                                        Tim</a>
                                </li>
                                <li>
                                    <a href="https://www.creative-tim.com/presentation?ref=njs-login"
                                        class="text-white hover:text-blueGray-300 text-sm font-semibold block py-1 px-3">About
                                        Us</a>
                                </li>
                                <li>
                                    <a href="http://blog.creative-tim.com?ref=njs-login"
                                        class="text-white hover:text-blueGray-300 text-sm font-semibold block py-1 px-3">Blog</a>
                                </li>
                                <li>
                                    <a href="https://github.com/creativetimofficial/notus-js/blob/main/LICENSE.md?ref=njs-login"
                                        class="text-white hover:text-blueGray-300 text-sm font-semibold block py-1 px-3">MIT
                                        License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </section>
    </main>
</body>
<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
<script>
    /* Make dynamic date appear */
    (function() {
        if (document.getElementById("get-current-year")) {
            document.getElementById(
                "get-current-year"
            ).innerHTML = new Date().getFullYear();
        }
    })();
    /* Function for opning navbar on mobile */
    function toggleNavbar(collapseID) {
        document.getElementById(collapseID).classList.toggle("hidden");
        document.getElementById(collapseID).classList.toggle("block");
    }
    /* Function for dropdowns */
    function openDropdown(event, dropdownID) {
        let element = event.target;
        while (element.nodeName !== "A") {
            element = element.parentNode;
        }
        Popper.createPopper(element, document.getElementById(dropdownID), {
            placement: "bottom-start",
        });
        document.getElementById(dropdownID).classList.toggle("hidden");
        document.getElementById(dropdownID).classList.toggle("block");
    }
</script>

</html>
