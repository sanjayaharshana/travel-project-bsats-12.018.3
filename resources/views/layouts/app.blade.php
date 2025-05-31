<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EezyTrip - AI-Powered Travel Planning</title>
        <meta name="description" content="Plan your perfect trip with EezyTrip's AI-powered travel planning and booking platform">

        <!-- Google Maps API -->
        <script>
            (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries","places");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
            key: "{{ config('services.google.places_api_key') }}",
            v: "weekly",
            callback: "initGoogleMaps"
            });
            
            function initGoogleMaps() {
                console.log('Google Maps API loaded successfully');
                window.dispatchEvent(new Event('googleMapsLoaded'));
            }
        </script>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <!-- Swiper.js CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            .navbar {
                padding: 0.5rem 0;
                transition: all 0.3s ease;
            }
            .navbar.scrolled {
                padding: 0.5rem 0;
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(10px);
            }
            .navbar-brand {
                font-weight: 700;
                color: #0d6efd !important;
                font-size: 1.1rem;
            }
            .nav-link {
                font-size: 0.97rem;
                padding: 0.35rem 0.7rem !important;
                position: relative;
            }
            .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: 0;
                left: 50%;
                background-color: #0d6efd;
                transition: all 0.3s ease;
                transform: translateX(-50%);
            }
            .nav-link:hover::after {
                width: 100%;
            }
            .btn-login, .btn-signup {
                font-size: 0.97rem;
                padding: 0.35rem 1rem;
            }
            .btn-login {
                background-color: #0d6efd;
                color: white;
                border-radius: 50px;
                transition: all 0.3s ease;
            }
            .btn-login:hover {
                background-color: #0b5ed7;
                transform: translateY(-2px);
            }
            .btn-signup {
                border: 2px solid #0d6efd;
                color: #0d6efd;
                border-radius: 50px;
                transition: all 0.3s ease;
            }
            .btn-signup:hover {
                background-color: #0d6efd;
                color: white;
                transform: translateY(-2px);
            }
            /* Mega Menu Styles */
            .dropdown {
                position: static;
            }
            .mega-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                width: 100vw;
                min-width: 0;
                max-width: none;
                margin-left: calc(-50vw + 50%);
                background: white;
                box-shadow: 0 4px 16px rgba(0,0,0,0.10);
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: all 0.3s ease;
                z-index: 1000;
                border: none;
                border-radius: 0 0 16px 16px;
                margin-top: 8px;
                padding: 0;
            }
            .mega-menu .container {
                max-width: 1200px;
                margin: 0 auto;
                padding-left: 2rem;
                padding-right: 2rem;
            }
            .dropdown-menu.mega-menu {
                min-width: 0;
                max-width: none;
                width: 100vw;
                left: 0;
                right: 0;
                margin-left: calc(-50vw + 50%);
                padding: 0;
            }
            .dropdown:hover .mega-menu {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }
            .mega-menu-content {
                padding: 2rem 0;
            }
            .mega-menu-title {
                font-size: 1rem;
                font-weight: 600;
                color: #0d6efd;
                margin-bottom: 1rem;
            }
            .mega-menu-link {
                font-size: 0.92rem;
                color: #6c757d;
                text-decoration: none;
                padding: 0.35rem 0;
                display: block;
                transition: all 0.2s ease;
            }
            .mega-menu-link:hover {
                color: #0d6efd;
                transform: translateX(5px);
                background: none;
            }
            .mega-menu-image {
                border-radius: 10px;
                overflow: hidden;
                position: relative;
                height: 200px;
            }
            .mega-menu-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: all 0.3s ease;
            }
            .mega-menu-image:hover img {
                transform: scale(1.05);
            }
            .mega-menu-image-overlay {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 1rem;
                background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
                color: white;
            }
            .navbar {
                padding: 0.5rem 0;
                transition: all 0.3s ease;
                z-index: 1030;
            }
            .navbar.scrolled {
                padding: 0.5rem 0;
                background: rgba(255, 255, 255, 0.95) !important;
                backdrop-filter: blur(10px);
            }
            .dropdown-toggle::after {
                display: none;
            }
            .nav-link.dropdown-toggle {
                padding-right: 1.5rem !important;
                position: relative;
            }
            .nav-link.dropdown-toggle::before {
                content: '\F282';
                font-family: "bootstrap-icons";
                position: absolute;
                right: 0.5rem;
                top: 50%;
                transform: translateY(-50%);
                font-size: 0.8rem;
                transition: transform 0.3s ease;
            }
            .dropdown:hover .nav-link.dropdown-toggle::before {
                transform: translateY(-50%) rotate(180deg);
            }
            @media (max-width: 991.98px) {
                .dropdown {
                    position: relative;
                }
                .mega-menu, .dropdown-menu.mega-menu {
                    position: static;
                    width: 100%;
                    left: 0;
                    right: 0;
                    margin-left: 0;
                    box-shadow: none;
                    opacity: 1;
                    visibility: visible;
                    transform: none;
                    display: none;
                    border-radius: 0;
                    background: #f8f9fa;
                }
                .dropdown-menu.show {
                    display: block;
                }
                .mega-menu-content {
                    padding: 1rem 0;
                }
                .mega-menu-image {
                    height: 150px;
                    margin-top: 1rem;
                }
                .nav-link.dropdown-toggle::before {
                    transform: translateY(-50%) rotate(0deg);
                }
                .dropdown.show .nav-link.dropdown-toggle::before {
                    transform: translateY(-50%) rotate(180deg);
                }
                .navbar {
                    padding: 0.5rem 0;
                }
            }
            /* Profile Dropdown Enhancements */
            .profile-dropdown-card {
                background: linear-gradient(90deg, #0d6efd 60%, #4f8cff 100%);
                color: #fff;
                border-radius: 1rem 1rem 0 0;
                padding: 0.8rem 0.7rem 0.7rem 0.7rem;
                text-align: center;
                margin-bottom: 0.5rem;
            }
            .profile-dropdown-card img {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                border: 3px solid #fff;
                margin-bottom: 0.3rem;
                box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            }
            .profile-dropdown-card .profile-name {
                font-size: 1rem;
                font-weight: 600;
            }
            .profile-dropdown-card .profile-email {
                font-size: 0.89rem;
                color: #e0e7ef;
            }
            .dropdown-menu.profile-dropdown {
                border-radius: 1rem;
                box-shadow: 0 8px 32px rgba(0,0,0,0.15);
                min-width: 200px;
                padding-top: 0;
                animation: fadeInDown 0.25s cubic-bezier(.4,2,.6,1) both;
            }
            @keyframes fadeInDown {
                0% { opacity: 0; transform: translateY(-10px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            .dropdown-menu.profile-dropdown .dropdown-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                font-weight: 500;
                border-radius: 0.5rem;
                transition: background 0.18s;
            }
            .dropdown-menu.profile-dropdown .dropdown-item:hover {
                background: #f0f6ff;
                color: #0d6efd;
            }
            .dropdown-menu.profile-dropdown .dropdown-divider {
                margin: 0.5rem 0;
            }
            .profile-dropdown-btn {
                display: block;
                width: 100%;
                background: #fff;
                color: #0d6efd;
                border: 1px solid #0d6efd;
                border-radius: 0.5rem;
                font-weight: 600;
                padding: 0.35rem 0;
                margin-top: 0.5rem;
                margin-bottom: 0.5rem;
                transition: background 0.18s, color 0.18s;
            }
            .profile-dropdown-btn:hover {
                background: #0d6efd;
                color: #fff;
            }
        </style>
    </head>
    <body>
        <!-- Navigation Bar -->

        @include('layouts.navigation')
        <!-- Main Content -->
        <main class="mt-5">
            @yield('content')
        </main>

        <!-- Simple Inline Footer -->
        <footer class="py-3 mt-4 border-top">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        &copy; {{ date('Y') }} EezyTrip. All rights reserved.
                    </div>
                    <div class="d-flex gap-3">
                        <a href="/privacy" class="text-muted small text-decoration-none">Privacy</a>
                        <a href="/terms" class="text-muted small text-decoration-none">Terms</a>
                        <a href="/contact" class="text-muted small text-decoration-none">Contact</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Swiper.js JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!-- Stack for additional scripts -->
        @stack('scripts')

        <script>
            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Remove the hover effect JavaScript since we're using CSS hover now
            document.addEventListener('DOMContentLoaded', function() {
                // Add active class to current nav item
                const currentPath = window.location.pathname;
                document.querySelectorAll('.nav-link').forEach(link => {
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('active');
                    }
                });
            });
        </script>
    </body>
</html>
