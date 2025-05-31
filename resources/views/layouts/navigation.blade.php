<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-airplane-fill me-2"></i>EezyTrip
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/" style="margin-right: 10px;margin-left: 40px;">Home</a>
                </li>
                <li class="nav-item dropdown" style="margin-right: 10px;margin-left: 20px;">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Explore best destinations
                    </a>
                    <div class="dropdown-menu mega-menu p-0">
                        <div class="container mega-menu-content">
                            <div class="row g-4">
                                <div class="col-lg-3">
                                    <h5 class="mega-menu-title">Popular Destinations</h5>
                                    <a href="/explore/asia" class="mega-menu-link">
                                        <i class="bi bi-globe-asia-australia me-2"></i>Asia
                                    </a>
                                    <a href="/explore/europe" class="mega-menu-link">
                                        <i class="bi bi-globe-europe-africa me-2"></i>Europe
                                    </a>
                                    <a href="/explore/americas" class="mega-menu-link">
                                        <i class="bi bi-globe-americas me-2"></i>Americas
                                    </a>
                                    <a href="/explore/africa" class="mega-menu-link">
                                        <i class="bi bi-globe-africa me-2"></i>Africa
                                    </a>
                                </div>
                                <div class="col-lg-3">
                                    <h5 class="mega-menu-title">Travel Types</h5>
                                    <a href="/explore/adventure" class="mega-menu-link">
                                        <i class="bi bi-activity me-2"></i>Adventure
                                    </a>
                                    <a href="/explore/beach" class="mega-menu-link">
                                        <i class="bi bi-water me-2"></i>Beach Holidays
                                    </a>
                                    <a href="/explore/cultural" class="mega-menu-link">
                                        <i class="bi bi-building me-2"></i>Cultural Tours
                                    </a>
                                    <a href="/explore/luxury" class="mega-menu-link">
                                        <i class="bi bi-stars me-2"></i>Luxury Travel
                                    </a>
                                </div>
                                <div class="col-lg-3">
                                    <h5 class="mega-menu-title">Travel Services</h5>
                                    <a href="/services/flights" class="mega-menu-link">
                                        <i class="bi bi-airplane me-2"></i>Flights
                                    </a>
                                    <a href="/services/hotels" class="mega-menu-link">
                                        <i class="bi bi-building me-2"></i>Hotels
                                    </a>
                                    <a href="/services/packages" class="mega-menu-link">
                                        <i class="bi bi-box-seam me-2"></i>Travel Packages
                                    </a>
                                    <a href="/services/activities" class="mega-menu-link">
                                        <i class="bi bi-calendar-event me-2"></i>Activities
                                    </a>
                                </div>
                                <div class="col-lg-3">
                                    <div class="mega-menu-image">
                                        <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                                             alt="Travel"
                                             class="img-fluid w-100">
                                        <div class="mega-menu-image-overlay">
                                            <h6 class="mb-1">Special Offers</h6>
                                            <p class="mb-0">Get up to 30% off on selected destinations</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown" style="margin-right: 10px;margin-left: 20px;">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        About Us
                    </a>
                    <div class="dropdown-menu mega-menu p-0">
                        <div class="container mega-menu-content">
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <h5 class="mega-menu-title">Company</h5>
                                    <a href="/about/our-story" class="mega-menu-link">
                                        <i class="bi bi-book me-2"></i>Our Story
                                    </a>
                                    <a href="/about/team" class="mega-menu-link">
                                        <i class="bi bi-people me-2"></i>Our Team
                                    </a>
                                    <a href="{{ route('careers.index') }}" class="mega-menu-link">
                                        <i class="bi bi-briefcase me-2"></i>Careers
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <h5 class="mega-menu-title">Support</h5>
                                    <a href="/support/contact" class="mega-menu-link">
                                        <i class="bi bi-envelope me-2"></i>Contact Us
                                    </a>
                                    <a href="/support/faq" class="mega-menu-link">
                                        <i class="bi bi-question-circle me-2"></i>FAQ
                                    </a>
                                    <a href="/support/help" class="mega-menu-link">
                                        <i class="bi bi-headset me-2"></i>Help Center
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mega-menu-image">
                                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                                             alt="Our Team"
                                             class="img-fluid w-100">
                                        <div class="mega-menu-image-overlay">
                                            <h6 class="mb-1">Join Our Team</h6>
                                            <p class="mb-0">We're always looking for talented people</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown" style="margin-right: 10px;margin-left: 20px;">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pricing
                    </a>
                    <div class="dropdown-menu mega-menu p-0">
                        <div class="container mega-menu-content">
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <h5 class="mega-menu-title">Plans</h5>
                                    <a href="/pricing/basic" class="mega-menu-link">
                                        <i class="bi bi-star me-2"></i>Basic Plan
                                    </a>
                                    <a href="/pricing/premium" class="mega-menu-link">
                                        <i class="bi bi-star-fill me-2"></i>Premium Plan
                                    </a>
                                    <a href="/pricing/enterprise" class="mega-menu-link">
                                        <i class="bi bi-stars me-2"></i>Enterprise Plan
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <h5 class="mega-menu-title">Features</h5>
                                    <a href="/pricing/features" class="mega-menu-link">
                                        <i class="bi bi-check-circle me-2"></i>Compare Features
                                    </a>
                                    <a href="/pricing/custom" class="mega-menu-link">
                                        <i class="bi bi-gear me-2"></i>Custom Solutions
                                    </a>
                                    <a href="/pricing/faq" class="mega-menu-link">
                                        <i class="bi bi-question-circle me-2"></i>Pricing FAQ
                                    </a>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mega-menu-image">
                                        <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                                             alt="Pricing"
                                             class="img-fluid w-100">
                                        <div class="mega-menu-image-overlay">
                                            <h6 class="mb-1">Special Offer</h6>
                                            <p class="mb-0">Get 20% off on annual plans</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="d-flex gap-2 align-items-center">
                @if(Auth::check())
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="navbarProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}" alt="{{ Auth::user()->name }}" width="36" height="36" class="rounded-circle me-2">
                            <span class="fw-semibold">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 profile-dropdown" aria-labelledby="navbarProfileDropdown">
                            <li>
                                <div class="profile-dropdown-card">
                                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}" alt="{{ Auth::user()->name }}">
                                    <div class="profile-name">{{ Auth::user()->name }}</div>
                                    <div class="profile-email">{{ Auth::user()->email }}</div>
                                    <a href="{{ route('profile.edit') }}" class="profile-dropdown-btn">View Profile</a>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-gear"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-signup">Sign Up</a>
                @endif
            </div>
        </div>
    </div>
</nav>
