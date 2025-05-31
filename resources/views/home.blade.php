@extends('layouts.app')

@section('content')
    <!-- Modern Hero Section with Destination Slider -->
    <div class="hero-section position-relative" style="min-height: 80vh; background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80') center center/cover no-repeat;">
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(120deg, rgba(10,30,60,0.7) 60%, rgba(10,30,60,0.3) 100%); z-index:1;"></div>
        <div class="container position-relative h-100" style="z-index:2;">
            <div class="row align-items-center h-100" style="min-height: 70vh;">
                <div class="col-lg-6 text-white py-5">
                    <h1 class="display-3 fw-bold mb-3" style="letter-spacing:1px;">INDONESIA</h1>
                    <p class="lead mb-4" style="max-width: 500px;">As the largest archipelagic country in the world, Indonesia is blessed with many different people, cultures, customs, traditions, artworks, food, animals, plants, landscapes, and everything that made it almost like 100 (or even 200) countries melted beautifully into one.</p>
                    <a href="#" class="btn btn-lg btn-primary px-5 py-2 rounded-pill fw-semibold shadow">Explore <i class="bi bi-arrow-right ms-2"></i></a>
                </div>
                <div class="col-lg-6 d-flex flex-column align-items-end justify-content-center">
                    <div id="plan-trip" class="container py-4">
                        <div class="card shadow-lg border-0 rounded-4">
                            <div class="card-body p-3 p-md-4">
                                <h2 class="text-center fw-bold mb-4" style="font-size:1.3rem;">Plan Your Trip</h2>
                                <form action="/plan-trip" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <!-- From Destination -->
                                        <div class="col-md-6">
                                            <label for="from" class="form-label fw-medium" style="font-size:0.97rem;">From</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control form-control-sm"
                                                       name="from"
                                                       id="from"
                                                       placeholder="Enter departure city"
                                                       required>
                                                <span class="input-group-text bg-white">
                                    <i class="bi bi-geo-alt"></i>
                                </span>
                                            </div>
                                        </div>

                                        <!-- To Destination -->
                                        <div class="col-md-6">
                                            <label for="to" class="form-label fw-medium" style="font-size:0.97rem;">To</label>
                                            <div class="input-group">
                                                <input type="text"
                                                       class="form-control form-control-sm"
                                                       name="to"
                                                       id="to"
                                                       placeholder="Enter destination city"
                                                       required>
                                                <span class="input-group-text bg-white">
                                    <i class="bi bi-geo-alt"></i>
                                </span>
                                            </div>
                                        </div>

                                        <!-- Dates -->
                                        <div class="col-md-6">
                                            <label for="dates" class="form-label fw-medium" style="font-size:0.97rem;">Travel Dates</label>
                                            <input type="text"
                                                   class="form-control form-control-sm"
                                                   name="dates"
                                                   id="dates"
                                                   placeholder="Select dates"
                                                   required>
                                        </div>

                                        <!-- Number of Travelers -->
                                        <div class="col-md-6">
                                            <label for="travelers" class="form-label fw-medium" style="font-size:0.97rem;">Number of Travelers</label>
                                            <select class="form-select form-select-sm"
                                                    name="travelers"
                                                    id="travelers"
                                                    required>
                                                <option value="">Select number of travelers</option>
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Traveler' : 'Travelers' }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Budget -->
                                        <div class="col-12">
                                            <label for="budget" class="form-label fw-medium" style="font-size:0.97rem;">Budget Range (USD)</label>
                                            <input type="range"
                                                   class="form-range"
                                                   name="budget"
                                                   id="budget"
                                                   min="500"
                                                   max="10000"
                                                   step="500"
                                                   required>
                                            <div class="d-flex justify-content-between text-muted" style="font-size:0.92rem;">
                                                <span>$500</span>
                                                <span id="budget-value">$5,000</span>
                                                <span>$10,000</span>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12 text-center mt-3">
                                            <button type="submit" class="btn btn-primary btn-sm px-4 py-2" style="font-size:1rem;">
                                                <i class="bi bi-lightning-charge me-2"></i>
                                                Generate AI Travel Plan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .hero-section {
            min-height: 80vh;
            position: relative;
        }
        .hero-overlay {
            background: linear-gradient(120deg, rgba(10,30,60,0.7) 60%, rgba(10,30,60,0.3) 100%);
            z-index: 1;
        }
        .glass-card {
            background: rgba(255,255,255,0.13);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.18);
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .glass-card:hover {
            box-shadow: 0 16px 48px 0 rgba(31,38,135,0.22);
            transform: translateY(-4px) scale(1.03);
        }
        .glass-icon {
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            padding: 0.4rem 0.5rem;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 2px 8px rgba(31,38,135,0.10);
        }
        .swiper.hero-swiper {
            width: 100%;
            padding-bottom: 2.5rem;
        }
        .swiper-slide {
            display: flex;
            justify-content: center;
        }
        .destination-card {
            width: 400px;
            min-height: 600px;
            max-width: 400px;
            max-height: 600px;
            margin: 0 0.7rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .destination-card img {
            width: 400px;
            height: 600px;
            object-fit: cover;
            object-position: center;
            border-radius: 1.5rem 1.5rem 0 0;
            box-shadow: 0 2px 12px rgba(31,38,135,0.10);
            margin-bottom: 0.5rem;
        }
        .swiper-button-next, .swiper-button-prev {
            color: #fff;
            top: 45%;
        }
        .swiper-pagination-bullet {
            background: #fff;
            opacity: 0.7;
        }
        .swiper-pagination-bullet-active {
            background: #0d6efd;
            opacity: 1;
        }
        @media (max-width: 991.98px) {
            .hero-section .display-3 {
                font-size: 2.2rem;
            }
            .destination-card {
                width: 180px;
                min-height: 200px;
            }
            .hero-swiper {
                max-width: 100% !important;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.hero-swiper', {
                slidesPerView: 1.3,
                spaceBetween: 28,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    576: { slidesPerView: 1.7 },
                    768: { slidesPerView: 2.2 },
                    992: { slidesPerView: 2.7 },
                    1200: { slidesPerView: 3 },
                }
            });
        });
    </script>

    <!-- Travel Planning Form Section -->


    <!-- Features Section -->
    <div class="bg-light py-4">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="font-size:1.2rem;">Why Choose EezyTrip?</h2>
                <p class="lead text-muted" style="font-size:1rem;">Experience the future of travel planning</p>
            </div>

            <div class="row g-3">
                <!-- AI-Powered Planning -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-2 mb-3" style="width: fit-content;">
                                <i class="bi bi-lightbulb text-primary fs-5"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-2">AI-Powered Planning</h3>
                            <p class="text-muted mb-0" style="font-size:0.95rem;">Our advanced AI analyzes your preferences to create personalized travel itineraries that match your style and budget.</p>
                        </div>
                    </div>
                </div>

                <!-- Smart Booking -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-2 mb-3" style="width: fit-content;">
                                <i class="bi bi-calendar-check text-primary fs-5"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-2">Smart Booking</h3>
                            <p class="text-muted mb-0" style="font-size:0.95rem;">Book flights, hotels, and activities all in one place with our integrated booking system and real-time availability.</p>
                        </div>
                    </div>
                </div>

                <!-- Local Insights -->
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-2 mb-3" style="width: fit-content;">
                                <i class="bi bi-geo-alt text-primary fs-5"></i>
                            </div>
                            <h3 class="h6 fw-bold mb-2">Local Insights</h3>
                            <p class="text-muted mb-0" style="font-size:0.95rem;">Get authentic local recommendations and hidden gems that most tourists miss, curated by our AI and local experts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="bg-white py-4">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="fw-bold" style="font-size:1.2rem;">What Our Travelers Say</h2>
                <p class="lead text-muted" style="font-size:1rem;">Real reviews from happy EezyTrip users</p>
            </div>
            <div class="row g-3 justify-content-center">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-2">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="rounded-circle me-2" width="40" height="40">
                            <div>
                                <h6 class="mb-0 fw-bold" style="font-size:1rem;">Sarah Lee</h6>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size:0.95rem;">"EezyTrip made planning our honeymoon so easy! The AI suggestions were spot on and we loved every moment of our trip."</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-2">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="rounded-circle me-2" width="40" height="40">
                            <div>
                                <h6 class="mb-0 fw-bold" style="font-size:1rem;">James Carter</h6>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size:0.95rem;">"I saved hours of research. The itinerary was perfect and booking everything in one place was a breeze!"</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm p-2">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User" class="rounded-circle me-2" width="40" height="40">
                            <div>
                                <h6 class="mb-0 fw-bold" style="font-size:1rem;">Priya Singh</h6>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted mb-0" style="font-size:0.95rem;">"The local insights were amazing. We discovered hidden gems we'd never have found on our own!"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Partners Section -->
    <div class="bg-light py-4">
        <div class="container">
            <div class="text-center mb-3">
                <h2 class="fw-bold" style="font-size:1.2rem;">Our Trusted Partners</h2>
                <p class="lead text-muted" style="font-size:1rem;">We collaborate with the best in travel</p>
            </div>
            <div class="row justify-content-center align-items-center g-3">
                <div class="col-6 col-md-2 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2d/Airbnb_Logo_Bélo.svg" alt="Airbnb" class="img-fluid" style="max-height: 36px;">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/73/Booking.com_logo.svg" alt="Booking.com" class="img-fluid" style="max-height: 36px;">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Expedia_Logo.svg" alt="Expedia" class="img-fluid" style="max-height: 36px;">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2b/Tripadvisor_Logo.svg" alt="Tripadvisor" class="img-fluid" style="max-height: 36px;">
                </div>
                <div class="col-6 col-md-2 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2c/Skyscanner_Logo.svg" alt="Skyscanner" class="img-fluid" style="max-height: 36px;">
                </div>
            </div>
        </div>
    </div>

    <script>
        // Budget range slider value display
        const budgetSlider = document.getElementById('budget');
        const budgetValue = document.getElementById('budget-value');

        budgetSlider.addEventListener('input', function() {
            const value = this.value;
            budgetValue.textContent = `$${parseInt(value).toLocaleString()}`;
        });
    </script>
@endsection
