@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Welcome Section -->

    @include('dashboard.welcome')

    <!-- Create Tour Plan Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm create-plan-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center mb-3 mb-lg-0">
                                <div class="create-plan-icon me-3">
                                    <i class="bi bi-airplane-takeoff"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1">Ready to Start Your Next Adventure?</h4>
                                    <p class="text-muted mb-0">Create a personalized tour plan and let us help you explore the world</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="{{url('tour-plan/create')}}" class="btn btn-create-plan">
                                <span class="btn-text">Let's Create Tour Plan</span>
                                <span class="btn-icon"><i class="bi bi-arrow-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Travel Menu Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-journal-text text-primary fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">My Plans</h6>
                                        <p class="text-muted small mb-0">View all your travel plans</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-success bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-calendar-check text-success fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">Created Plans</h6>
                                        <p class="text-muted small mb-0">Your custom travel plans</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-cart-check text-warning fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">Pre-ordered</h6>
                                        <p class="text-muted small mb-0">Your upcoming bookings</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-info bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-geo-alt text-info fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">Visited Places</h6>
                                        <p class="text-muted small mb-0">Your travel history</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-bookmark text-danger fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">Bookmarked Places</h6>
                                        <p class="text-muted small mb-0">Your saved destinations</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-secondary bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-building text-secondary fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">City Tours</h6>
                                        <p class="text-muted small mb-0">Explore city attractions</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-purple bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-map text-purple fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">Travel Map</h6>
                                        <p class="text-muted small mb-0">View your travel journey</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="#" class="text-decoration-none">
                                <div class="d-flex align-items-center p-3 rounded bg-light hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="bg-pink bg-opacity-10 p-3 rounded">
                                            <i class="bi bi-heart text-pink fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 text-dark">Wishlist</h6>
                                        <p class="text-muted small mb-0">Your dream destinations</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-airplane text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Upcoming Trips</h6>
                            <h3 class="mb-0">3</h3>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 75%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Completed Trips</h6>
                            <h3 class="mb-0">12</h3>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="bi bi-bookmark text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Saved Places</h6>
                            <h3 class="mb-0">24</h3>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-star text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Reward Points</h6>
                            <h3 class="mb-0">1,250</h3>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Upcoming Trips -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upcoming Trips</h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <img src="https://images.unsplash.com/photo-1548013146-72479768bada?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                     alt="Bali"
                                     class="rounded"
                                     width="80" height="60"
                                     style="object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-1">Bali, Indonesia</h6>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-calendar me-1"></i>Mar 15 - Mar 22, 2024
                                    </p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-primary">Confirmed</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                     alt="Paris"
                                     class="rounded"
                                     width="80" height="60"
                                     style="object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-1">Paris, France</h6>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-calendar me-1"></i>Apr 5 - Apr 12, 2024
                                    </p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-warning">Pending</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex align-items-center">
                                <img src="https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                                     alt="Tokyo"
                                     class="rounded"
                                     width="80" height="60"
                                     style="object-fit: cover;">
                                <div class="ms-3">
                                    <h6 class="mb-1">Tokyo, Japan</h6>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-calendar me-1"></i>May 10 - May 17, 2024
                                    </p>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-success">Booked</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Plan New Trip
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-search me-2"></i>Explore Destinations
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-bookmark me-2"></i>View Saved Places
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Trip Booked</h6>
                                <p class="text-muted small mb-0">Bali, Indonesia - Mar 15, 2024</p>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Hotel Confirmed</h6>
                                <p class="text-muted small mb-0">Grand Hyatt Bali</p>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Flight Booked</h6>
                                <p class="text-muted small mb-0">Singapore Airlines - SQ 123</p>
                                <small class="text-muted">2 days ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 12px;
        height: calc(100% - 12px);
        width: 2px;
        background: #e9ecef;
    }
    .timeline-content {
        padding-left: 0;
    }
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .bg-purple {
        background-color: #6f42c1;
    }
    .text-purple {
        color: #6f42c1;
    }
    .bg-pink {
        background-color: #e83e8c;
    }
    .text-pink {
        color: #e83e8c;
    }

    /* Create Tour Plan Styles */
    .create-plan-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        overflow: hidden;
        position: relative;
    }
    .create-plan-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        animation: rotate 15s linear infinite;
    }
    .create-plan-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        animation: float 3s ease-in-out infinite;
    }
    .btn-create-plan {
        background: white;
        color: #0d6efd;
        padding: 12px 24px;
        border-radius: 50px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .btn-create-plan::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        z-index: -1;
        transition: transform 0.3s ease;
        transform: scaleX(0);
        transform-origin: right;
    }
    .btn-create-plan:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
    .btn-create-plan:hover::before {
        transform: scaleX(1);
        transform-origin: left;
    }
    .btn-create-plan .btn-text {
        transition: transform 0.3s ease;
    }
    .btn-create-plan:hover .btn-text {
        transform: translateX(-5px);
    }
    .btn-create-plan .btn-icon {
        transition: transform 0.3s ease;
    }
    .btn-create-plan:hover .btn-icon {
        transform: translateX(5px);
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection
