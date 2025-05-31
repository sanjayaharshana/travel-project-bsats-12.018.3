@extends('layouts.app')

@section('content')
<!-- Hero Section with Background Image -->
<div class="career-hero-section position-relative">
    <div class="hero-overlay"></div>
    <div class="container position-relative">
        <div class="row min-vh-75 align-items-center">
            <div class="col-lg-8 mx-auto text-center text-white">
                <h1 class="display-3 fw-bold mb-4 animate-up">Join Our Journey</h1>
                <p class="lead mb-5 animate-up-delay-1">Be part of a team that's revolutionizing the way people travel. We're looking for passionate individuals who want to make a global impact.</p>
                <div class="d-flex justify-content-center gap-3 animate-up-delay-2">
                    <a href="#open-positions" class="btn btn-primary btn-lg px-5 py-3">View Open Positions</a>
                    <a href="#why-join-us" class="btn btn-outline-light btn-lg px-5 py-3">Why Join Us</a>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</div>

<div class="container py-5">
    <!-- Why Join Us Section -->
    <div id="why-join-us" class="row mb-5">
        <div class="col-12 text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Why Choose Us</span>
            <h2 class="fw-bold display-6">Why Join EezyTrip?</h2>
            <p class="text-muted lead">Discover the benefits of being part of our growing team</p>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-4">
                        <i class="bi bi-globe"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Global Impact</h3>
                    <p class="text-muted">Help travelers worldwide discover amazing destinations and create unforgettable memories. Make a real difference in how people experience travel.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-4">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Growth Opportunities</h3>
                    <p class="text-muted">Continuous learning and development opportunities to advance your career. We invest in your growth and success.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-4">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Great Culture</h3>
                    <p class="text-muted">Work with passionate individuals in a collaborative and innovative environment. Join a team that values creativity and teamwork.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section with Gradient Background -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="benefits-section rounded-4 p-5">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <span class="badge bg-white bg-opacity-10 text-white px-3 py-2 rounded-pill mb-3">Perks & Benefits</span>
                        <h2 class="fw-bold display-6 text-white mb-3">Our Benefits</h2>
                        <p class="text-white-50 lead">We take care of our team members</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="benefit-item text-center text-white">
                            <div class="benefit-icon-wrapper mb-4">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <h4 class="h5 fw-bold">Flexible Hours</h4>
                            <p class="text-white-50">Work-life balance is important to us</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="benefit-item text-center text-white">
                            <div class="benefit-icon-wrapper mb-4">
                                <i class="bi bi-heart"></i>
                            </div>
                            <h4 class="h5 fw-bold">Health Coverage</h4>
                            <p class="text-white-50">Comprehensive health insurance</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="benefit-item text-center text-white">
                            <div class="benefit-icon-wrapper mb-4">
                                <i class="bi bi-cup-hot"></i>
                            </div>
                            <h4 class="h5 fw-bold">Remote Work</h4>
                            <p class="text-white-50">Work from anywhere in the world</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="benefit-item text-center text-white">
                            <div class="benefit-icon-wrapper mb-4">
                                <i class="bi bi-gift"></i>
                            </div>
                            <h4 class="h5 fw-bold">Travel Perks</h4>
                            <p class="text-white-50">Discounted travel packages</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Open Positions Section -->
    <div id="open-positions" class="row mb-5">
        <div class="col-12 text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">Open Positions</span>
            <h2 class="fw-bold display-6">Find Your Perfect Role</h2>
            <p class="text-muted lead">Join us in our mission to transform travel experiences</p>
        </div>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="position-filters mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <select class="form-select form-select-lg">
                                    <option selected>All Departments</option>
                                    <option>Engineering</option>
                                    <option>Marketing</option>
                                    <option>Sales</option>
                                    <option>Customer Support</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-lg">
                                    <option selected>All Locations</option>
                                    <option>Remote</option>
                                    <option>Office</option>
                                    <option>Hybrid</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-lg">
                                    <option selected>All Job Types</option>
                                    <option>Full-time</option>
                                    <option>Part-time</option>
                                    <option>Contract</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="positions-list">
                        <!-- Position Cards with Enhanced Design -->
                        <div class="position-card p-4 border rounded-4 mb-4 hover-card">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h3 class="h4 fw-bold mb-3">Senior Software Engineer</h3>
                                    <div class="d-flex gap-3 mb-3">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-building me-1"></i>Engineering
                                        </span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-geo-alt me-1"></i>Remote
                                        </span>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="bi bi-clock me-1"></i>Full-time
                                        </span>
                                    </div>
                                    <p class="text-muted mb-0">Join our engineering team to build the next generation of travel technology. Work on exciting projects that impact millions of travelers worldwide.</p>
                                </div>
                                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                                    <a href="#" class="btn btn-primary btn-lg px-4">Apply Now</a>
                                </div>
                            </div>
                        </div>
                        <!-- More position cards with similar enhanced design -->
                        <!-- ... existing position cards with updated styling ... -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Process Section with Timeline -->
    <div class="row">
        <div class="col-12 text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">How to Apply</span>
            <h2 class="fw-bold display-6">Our Application Process</h2>
            <p class="text-muted lead">Simple and transparent process to join our team</p>
        </div>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="process-step text-center">
                                <div class="process-icon bg-primary bg-gradient text-white rounded-circle mb-4">
                                    <span>1</span>
                                </div>
                                <h4 class="h5 fw-bold mb-3">Apply</h4>
                                <p class="text-muted">Submit your application through our portal</p>
                            </div>
                        </div>
                        <!-- ... other process steps with similar enhanced styling ... -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hero Section Styles */
    .career-hero-section {
        background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        min-height: 75vh;
        position: relative;
        margin-top: -1.5rem;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.95) 0%, rgba(13, 110, 253, 0.85) 100%);
    }

    .hero-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        line-height: 0;
    }

    .min-vh-75 {
        min-height: 75vh;
    }

    /* Animation Classes */
    .animate-up {
        animation: fadeInUp 0.8s ease-out;
    }

    .animate-up-delay-1 {
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    .animate-up-delay-2 {
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Card Hover Effects */
    .hover-card {
        transition: all 0.3s ease;
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Feature Icon Styles */
    .feature-icon {
        width: 64px;
        height: 64px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        border-radius: 16px;
    }

    /* Benefits Section Styles */
    .benefits-section {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        position: relative;
        overflow: hidden;
    }

    .benefits-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        opacity: 0.1;
    }

    .benefit-icon-wrapper {
        width: 80px;
        height: 80px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        font-size: 2rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .benefit-item:hover .benefit-icon-wrapper {
        transform: scale(1.1);
        background: rgba(255, 255, 255, 0.2);
    }

    /* Process Step Styles */
    .process-icon {
        width: 64px;
        height: 64px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.5rem;
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    .process-step:hover .process-icon {
        transform: scale(1.1);
    }

    /* Position Card Styles */
    .position-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .position-card:hover {
        border-color: #0d6efd;
    }

    .badge {
        padding: 0.5em 1em;
        font-weight: 500;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .career-hero-section {
            min-height: 60vh;
        }

        .min-vh-75 {
            min-height: 60vh;
        }

        .display-6 {
            font-size: 2rem;
        }
    }
</style>
@endsection 