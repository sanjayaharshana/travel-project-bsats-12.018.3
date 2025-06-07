@include('tour-plan.steps.wizard-step-indicator',['step'  => 1])
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete" async defer></script>

<!-- Add Flash Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<style>
    .autocomplete-container {
        margin: 20px;
        width: 300px;
    }
    .hidden-fields {
        margin-top: 20px;
    }
    #autocomplete {
        width: 100%;
        padding: 10px;
        font-size: 16px;
    }
    .location-input-container {
        position: relative;
        width: 100%;
    }

    .location-input {
        width: 100%;
    padding: 0.75rem 1rem;
        border: 1px solid #ced4da;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

    .location-input:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
}

.pac-container {
        z-index: 1056 !important;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    border: none;
    margin-top: 5px;
}

.pac-item {
    padding: 8px 12px;
    font-size: 14px;
    cursor: pointer;
}

.pac-item:hover {
    background-color: #f8f9fa;
}

.pac-item-query {
    font-size: 14px;
    color: #2c3e50;
    font-weight: 500;
}

.pac-icon {
    margin-right: 8px;
    color: #6c757d;
}

.pac-matched {
    font-weight: 600;
    color: #0d6efd;
}

    /* Dining Preferences Styles */
    .preference-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .preference-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-color: #3498db;
    }

    .preference-card.selected {
        border-color: #3498db;
        background-color: rgba(52, 152, 219, 0.05);
    }

    .preference-card input[type="radio"],
    .preference-card input[type="checkbox"] {
        position: absolute;
        opacity: 0;
    }

    .preference-card .card-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .preference-card .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .preference-card .card-description {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0;
    }

    .preference-card .check-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #3498db;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .preference-card.selected .check-icon {
        opacity: 1;
    }

    .preference-section {
        margin-bottom: 2rem;
    }

    .preference-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .preference-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .preference-grid {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
    }

    /* Add these styles to your existing preference-card styles */
    .selected-meals-summary {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #e9ecef;
    }

    .selected-meals-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.75rem;
    }

    .selected-meals-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .meal-tag {
        background-color: #e3f2fd;
        color: #1976d2;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .meal-tag:hover {
        background-color: #bbdefb;
    }

    .meal-tag .remove-meal {
        cursor: pointer;
        color: #1976d2;
        font-size: 1rem;
        line-height: 1;
        padding: 0.125rem;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .meal-tag .remove-meal:hover {
        background-color: #1976d2;
        color: white;
    }

    .no-selections {
        color: #6c757d;
        font-style: italic;
        font-size: 0.875rem;
    }

    /* Update preference card styles */
    .preference-card {
        /* ... existing styles ... */
        border: 2px solid #e9ecef;
    }

    .preference-card.selected {
        border-color: #1976d2;
        background-color: rgba(25, 118, 210, 0.05);
    }

    .preference-card .check-icon {
        background: #1976d2;
    }

    /* Description button styles */
    .info-button {
        background: none;
        border: none;
        color: #6c757d;
        padding: 0.25rem;
        margin-left: 0.5rem;
        cursor: pointer;
        transition: color 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
    }

    .info-button:hover {
        color: #1976d2;
        background-color: rgba(25, 118, 210, 0.1);
    }

    .info-button i {
        font-size: 1rem;
    }

    /* Popover customization */
    .popover {
        max-width: 300px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .popover-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        font-weight: 600;
        color: #2c3e50;
    }

    .popover-body {
        color: #495057;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Section header with info button */
    .section-header-with-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .section-header-with-info h5 {
        margin-bottom: 0;
    }

    /* Group Details Section Styles */
    .group-input-container {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1.25rem;
        height: 100%;
        transition: all 0.2s ease;
    }

    .group-input-container:hover {
        border-color: #86b7fe;
    }

    .group-icon-wrapper {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .group-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: #fff;
        font-size: 1rem;
        background: #1976d2; /* Consistent color for all icons */
    }

    .group-label {
        font-size: 1rem;
        font-weight: 500;
        color: #2c3e50;
        margin: 0;
    }

    .group-input-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .group-input {
        flex: 1;
        text-align: center;
        font-size: 1rem;
        font-weight: 500;
        color: #2c3e50;
        padding: 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 6px;
        background: #f8f9fa;
        transition: all 0.2s ease;
    }

    .group-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
    }

    .group-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #ced4da;
        background: #fff;
        color: #1976d2; /* Consistent color for buttons */
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .group-btn:hover {
        background: #f8f9fa;
        border-color: #1976d2;
        color: #1976d2;
    }

    .group-btn:active {
        transform: scale(0.95);
    }

    .group-description {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }

    .group-total {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
        text-align: center;
        font-size: 1rem;
        color: #2c3e50;
    }

    .group-total span {
        font-weight: 600;
        color: #1976d2;
    }

    /* Add these styles to your existing style section */
    .tour-date-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid #e9ecef;
    }

    .tour-date-info .days-count {
        font-size: 1.1rem;
        color: #1976d2;
        font-weight: 600;
    }

    .tour-date-info .date-range {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .date-time-input {
        position: relative;
    }

    .date-time-input .form-control {
        padding-right: 2.5rem;
    }

    .date-time-input .input-icon {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
    }
</style>

<!-- Test script to verify script loading -->

<div class="">
    <!-- Step 1 Content -->
    <div class="step-content active" id="step1">
        <div class="row g-4">
            <form id="tourPlanForm" class="needs-validation" action="{{ route('tourplan.store') }}" method="POST" novalidate>
                @csrf
                <!-- Tour Overview -->
                <div class="col-12 mb-4">
                    <div class="section-card p-4">
                        <div class="section-header-with-info">
                            <h5 class="section-header">Tour Overview</h5>
                            <button type="button"
                                    class="info-button"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Tour Overview"
                                    data-bs-content="Select your tour start date and time, and end date. The system will automatically calculate the duration of your tour."
                                    data-bs-trigger="hover">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <!-- Start Date and Time -->
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date & Time</label>
                                <div class="date-time-input">
                                    <input type="datetime-local"
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           id="startDate"
                                           name="start_date"
                                           value="{{ old('start_date') }}"
                                           required>
                                    <i class="bi bi-calendar3 input-icon"></i>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <div class="date-time-input">
                                    <input type="date"
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           id="endDate"
                                           name="end_date"
                                           value="{{ old('end_date') }}"
                                           required>
                                    <i class="bi bi-calendar3 input-icon"></i>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tour Duration Info -->
                            <div class="col-12">
                                <div class="tour-date-info">
                                    <div class="days-count">
                                        Tour Duration: <span id="tourDuration">0</span> days
                                    </div>
                                    <div class="date-range" id="dateRangeDisplay">
                                        Select dates to see the tour period
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Locations section -->
                <div class="col-12 mb-4">
                    <div class="section-card p-4">
                        <div class="section-header-with-info">
                            <h5 class="section-header">Tour Locations</h5>
                            <button type="button"
                                    class="info-button"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Tour Locations"
                                    data-bs-content="Select your pickup location and final destination. These locations will be used to plan your tour route and calculate travel times."
                                    data-bs-trigger="hover">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <!-- Start Location -->
                            <div class="col-md-6">
                                <label for="startLocationInput" class="form-label">Pickup Location</label>
                                <div class="location-input-container">
                                    <input type="text"
                                           class="form-control location-input @error('start_location_name') is-invalid @enderror"
                                           id="startLocationInput"
                                           placeholder="Enter pickup location"
                                           value="{{ old('start_location_name') }}"
                                           autocomplete="off">
                                    <input type="hidden" id="startLocationLat" name="start_location_lat" value="{{ old('start_location_lat') }}">
                                    <input type="hidden" id="startLocationLng" name="start_location_lng" value="{{ old('start_location_lng') }}">
                                    <input type="hidden" id="startLocationName" name="start_location_name" value="{{ old('start_location_name') }}">
                                    @error('start_location_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- End Location -->
                            <div class="col-md-6">
                                <label for="endLocationInput" class="form-label">Final Destination</label>
                                <div class="location-input-container">
                                    <input type="text"
                                           class="form-control location-input @error('end_location_name') is-invalid @enderror"
                                           id="endLocationInput"
                                           placeholder="Enter final destination"
                                           value="{{ old('end_location_name') }}"
                                           autocomplete="off">
                                    <input type="hidden" id="endLocationLat" name="end_location_lat" value="{{ old('end_location_lat') }}">
                                    <input type="hidden" id="endLocationLng" name="end_location_lng" value="{{ old('end_location_lng') }}">
                                    <input type="hidden" id="endLocationName" name="end_location_name" value="{{ old('end_location_name') }}">
                                    @error('end_location_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Details section -->
                <div class="col-12 mb-4">
                    <div class="section-card p-4">
                        <div class="section-header-with-info">
                            <h5 class="section-header">Return Details</h5>
                            <button type="button"
                                    class="info-button"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Return Details"
                                    data-bs-content="Choose whether you want to return to your pickup location or a different location. This helps us plan your return journey."
                                    data-bs-trigger="hover">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Return Type</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="return_type" id="returnToPickup" value="pickup" {{ old('return_type', 'pickup') == 'pickup' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="returnToPickup">
                                        Return to Pickup Location
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="return_type" id="returnToSpecific" value="specific" {{ old('return_type') == 'specific' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="returnToSpecific">
                                        Return to Specific Location
                                    </label>
                                </div>
                            </div>
                            <!-- Return Location (initially hidden) -->
                            <div class="col-md-6" id="returnLocationContainer" style="display: {{ old('return_type') == 'specific' ? 'block' : 'none' }};">
                                <label for="returnLocationInput" class="form-label">Return Location</label>
                                <div class="location-input-container">
                                    <input type="text"
                                           class="form-control location-input @error('return_location_name') is-invalid @enderror"
                                           id="returnLocationInput"
                                           placeholder="Enter return location"
                                           value="{{ old('return_location_name') }}"
                                           autocomplete="off">
                                    <input type="hidden" id="returnLocationLat" name="return_location_lat" value="{{ old('return_location_lat') }}">
                                    <input type="hidden" id="returnLocationLng" name="return_location_lng" value="{{ old('return_location_lng') }}">
                                    <input type="hidden" id="returnLocationName" name="return_location_name" value="{{ old('return_location_name') }}">
                                    @error('return_location_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group Details section -->
                <div class="col-12 mb-4">
                    <div class="section-card p-4">
                        <div class="section-header-with-info">
                            <h5 class="section-header">Group Details</h5>
                            <button type="button"
                                    class="info-button"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Group Details"
                                    data-bs-content="Specify the number of adults, children, and infants in your group. This helps us plan appropriate accommodations and activities."
                                    data-bs-trigger="hover">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </div>
                        <div class="row g-3">
                            <!-- Adults -->
                            <div class="col-md-4">
                                <div class="group-input-container">
                                    <div class="group-icon-wrapper">
                                        <div class="group-icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <label class="group-label">Adults</label>
                                    </div>
                                    <div class="group-input-wrapper">
                                        <button type="button" class="group-btn" onclick="decreaseCount('adultCount')">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number"
                                               class="group-input"
                                               id="adultCount"
                                               name="adult_count"
                                               min="1"
                                               value="{{ old('adult_count', 1) }}"
                                               required
                                               readonly>
                                        <button type="button" class="group-btn" onclick="increaseCount('adultCount')">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="group-description">Age 12+ years</div>
                                </div>
                            </div>

                            <!-- Children -->
                            <div class="col-md-4">
                                <div class="group-input-container">
                                    <div class="group-icon-wrapper">
                                        <div class="group-icon">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <label class="group-label">Children</label>
                                    </div>
                                    <div class="group-input-wrapper">
                                        <button type="button" class="group-btn" onclick="decreaseCount('childCount')">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number"
                                               class="group-input"
                                               id="childCount"
                                               name="child_count"
                                               min="0"
                                               value="{{ old('child_count', 0) }}"
                                               required
                                               readonly>
                                        <button type="button" class="group-btn" onclick="increaseCount('childCount')">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="group-description">Age 5-11 years</div>
                                </div>
                            </div>

                            <!-- Infants -->
                            <div class="col-md-4">
                                <div class="group-input-container">
                                    <div class="group-icon-wrapper">
                                        <div class="group-icon">
                                            <i class="bi bi-person-heart"></i>
                                        </div>
                                        <label class="group-label">Infants</label>
                                    </div>
                                    <div class="group-input-wrapper">
                                        <button type="button" class="group-btn" onclick="decreaseCount('infantCount')">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number"
                                               class="group-input"
                                               id="infantCount"
                                               name="infant_count"
                                               min="0"
                                               value="{{ old('infant_count', 0) }}"
                                               required
                                               readonly>
                                        <button type="button" class="group-btn" onclick="increaseCount('infantCount')">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <div class="group-description">Age 0-4 years</div>
                                </div>
                            </div>

                            <!-- Total Group Size -->
                            <div class="col-12">
                                <div class="group-total">
                                    Total Group Size: <span id="totalGroupSize">1</span> persons
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Dining Preferences -->
                <div class="col-12 mb-4">
                    <div class="section-card p-4">
                        <div class="section-header-with-info">
                            <h5 class="section-header">Dining Preferences</h5>
                            <button type="button"
                                    class="info-button"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Dining Preferences"
                                    data-bs-content="Select your beverage and meal preferences. This helps us arrange appropriate dining options during your tour."
                                    data-bs-trigger="hover">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </div>

                        <!-- Beverage Preferences with info -->
                        <div class="preference-section">
                            <div class="section-header-with-info">
                                <h6 class="preference-section-title">Beverage Preferences</h6>
                                <button type="button"
                                        class="info-button"
                                        data-bs-toggle="popover"
                                        data-bs-placement="right"
                                        data-bs-title="Beverage Preferences"
                                        data-bs-content="Choose whether you want alcoholic beverages included in your tour package or prefer non-alcoholic options only."
                                        data-bs-trigger="hover">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            </div>
                            <div class="preference-grid">
                                <!-- Alcoholic Beverages -->
                                <label class="preference-card" onclick="beverage_preference('alcoholic')">
                                    <input type="radio" name="beverage_preference" value="alcoholic" class="beverage-radio">
                                    <img src="{{ asset('images/dining/alcoholic.jpg') }}" alt="Alcoholic Beverages" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Include Alcoholic Beverages</h6>
                                    <p class="card-description">Wine, beer, and spirits available</p>
                                </label>

                                <!-- Non-Alcoholic -->
                                <label class="preference-card" onclick="beverage_preference('non_alcoholic')">
                                    <input type="radio" name="beverage_preference" value="non_alcoholic" class="beverage-radio">
                                    <img src="{{ asset('images/dining/non-alcoholic.jpg') }}" alt="Non-Alcoholic Beverages" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Non-Alcoholic Only</h6>
                                    <p class="card-description">Fresh juices, mocktails, and soft drinks</p>
                                </label>
                            </div>
                        </div>

                        <!-- Meal Preferences with info -->
                        <div class="preference-section">
                            <div class="section-header-with-info">
                                <h6 class="preference-section-title">Meal Preferences</h6>
                                <button type="button"
                                        class="info-button"
                                        data-bs-toggle="popover"
                                        data-bs-placement="right"
                                        data-bs-title="Meal Preferences"
                                        data-bs-content="Select your meal preferences. You can choose multiple options. This helps us arrange suitable dining options for your group."
                                        data-bs-trigger="hover">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                            </div>
                            <div class="preference-grid">
                                <!-- Vegetarian -->
                                <label class="preference-card"  onclick="selectMealType('vegetarian')">
                                    <input type="radio" name="meal_types" value="vegetarian" class="meal-radio">
                                    <img src="{{ asset('images/dining/vegetarian.jpg') }}" alt="Vegetarian Options" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Vegetarian Options</h6>
                                    <p class="card-description">Fresh vegetables and plant-based dishes</p>
                                </label>

                                <!-- Non-Vegetarian -->
                                <label class="preference-card"  onclick="selectMealType('non_vegetarian')">
                                    <input type="radio" name="meal_types" value="non_vegetarian" class="meal-radio">
                                    <img src="{{ asset('images/dining/non-vegetarian.jpg') }}" alt="Non-Vegetarian Options" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Non-Vegetarian Options</h6>
                                    <p class="card-description">Meat and seafood dishes available</p>
                                </label>

                                <!-- Halal -->
                                <label class="preference-card" onclick="selectMealType('halal')">
                                    <input type="radio" name="meal_types" value="halal" class="meal-radio">
                                    <img src="{{ asset('images/dining/halal.jpg') }}" alt="Halal Food" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1563245372-f21724e3856d?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Halal Food</h6>
                                    <p class="card-description">Certified halal options available</p>
                                </label>

                                <!-- Mixed Options -->
                                <label class="preference-card"  onclick="selectMealType('mixed_options')">
                                    <input type="radio" name="meal_types" value="mixed" class="meal-radio">
                                    <img src="{{ asset('images/dining/mixed.jpg') }}" alt="Mixed Options" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Mixed Options</h6>
                                    <p class="card-description">Variety of dishes for all preferences</p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Budget -->
                <div class="col-12 mb-4">
                    <div class="section-card p-4">
                        <h5 class="section-header">Tour Budget</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="budget" class="form-label">Total Budget</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number" 
                                           class="form-control @error('budget') is-invalid @enderror" 
                                           id="budget" 
                                           name="budget" 
                                           min="0" 
                                           step="1000" 
                                           value="{{ old('budget') }}" 
                                           required>
                                    @error('budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="budgetType" class="form-label">Budget Category</label>
                                <select class="form-select @error('budget_type') is-invalid @enderror" 
                                        id="budgetType" 
                                        name="budget_type" 
                                        required>
                                    <option value="">Select budget category</option>
                                    <option value="luxury" {{ old('budget_type') == 'luxury' ? 'selected' : '' }}>Luxury Tour Package</option>
                                    <option value="medium" {{ old('budget_type') == 'medium' ? 'selected' : '' }}>Standard Tour Package</option>
                                    <option value="emergency" {{ old('budget_type') == 'emergency' ? 'selected' : '' }}>Budget Tour Package</option>
                                </select>
                                @error('budget_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Location Preferences -->
                <div class="col-12 mb-4">
                    <div class="section-card p-4">
                        <div class="section-header-with-info">
                            <h5 class="section-header">Tour Location Preferences</h5>
                            <button type="button"
                                    class="info-button"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Tour Location Preferences"
                                    data-bs-content="Select your preferred types of locations for the tour. You can choose multiple options to help us plan a diverse and enjoyable itinerary."
                                    data-bs-trigger="hover">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </div>

                        <div class="preference-section">
                            <div class="preference-grid">
                                <!-- Nature & Wildlife -->
                                <label class="preference-card">
                                    <input type="checkbox" name="location_types[]" value="nature" {{ in_array('nature', old('location_types', [])) ? 'checked' : '' }}>
                                    <img src="{{ asset('images/locations/nature.jpg') }}" alt="Nature & Wildlife" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Nature & Wildlife</h6>
                                    <p class="card-description">National parks, wildlife sanctuaries, and natural landscapes</p>
                                </label>

                                <!-- Cultural & Heritage -->
                                <label class="preference-card">
                                    <input type="checkbox" name="location_types[]" value="cultural" {{ in_array('cultural', old('location_types', [])) ? 'checked' : '' }}>
                                    <img src="{{ asset('images/locations/cultural.jpg') }}" alt="Cultural & Heritage" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1528181304800-259b08848526?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Cultural & Heritage</h6>
                                    <p class="card-description">Temples, historical sites, and cultural landmarks</p>
                                </label>

                                <!-- Urban & City Life -->
                                <label class="preference-card">
                                    <input type="checkbox" name="location_types[]" value="urban" {{ in_array('urban', old('location_types', [])) ? 'checked' : '' }}>
                                    <img src="{{ asset('images/locations/urban.jpg') }}" alt="Urban & City Life" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Urban & City Life</h6>
                                    <p class="card-description">City attractions, shopping districts, and modern landmarks</p>
                                </label>

                                <!-- Beach & Coastal -->
                                <label class="preference-card">
                                    <input type="checkbox" name="location_types[]" value="beach" {{ in_array('beach', old('location_types', [])) ? 'checked' : '' }}>
                                    <img src="{{ asset('images/locations/beach.jpg') }}" alt="Beach & Coastal" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Beach & Coastal</h6>
                                    <p class="card-description">Beaches, coastal areas, and water activities</p>
                                </label>

                                <!-- Adventure & Sports -->
                                <label class="preference-card">
                                    <input type="checkbox" name="location_types[]" value="adventure" {{ in_array('adventure', old('location_types', [])) ? 'checked' : '' }}>
                                    <img src="{{ asset('images/locations/adventure.jpg') }}" alt="Adventure & Sports" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1533105079780-92b9be482077?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Adventure & Sports</h6>
                                    <p class="card-description">Adventure parks, sports facilities, and outdoor activities</p>
                                </label>

                                <!-- Wellness & Relaxation -->
                                <label class="preference-card">
                                    <input type="checkbox" name="location_types[]" value="wellness" {{ in_array('wellness', old('location_types', [])) ? 'checked' : '' }}>
                                    <img src="{{ asset('images/locations/wellness.jpg') }}" alt="Wellness & Relaxation" class="card-image" onerror="this.src='https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=500&auto=format&fit=crop&q=60'">
                                    <div class="check-icon">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <h6 class="card-title">Wellness & Relaxation</h6>
                                    <p class="card-description">Spas, wellness centers, and relaxation spots</p>
                                </label>
                            </div>

                            <!-- Selected Locations Summary -->
                            <div class="selected-meals-summary mt-4">
                                <div class="selected-meals-title">Selected Location Types</div>
                                <div class="selected-meals-list" id="selectedLocationsList">
                                    <div class="no-selections">No locations selected yet</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="meal_type_text" id="meal_type_text">
                <input type="hidden" name="beverage_preference_text" id="beverage_preference_text">

                <!-- Submit Button -->
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Continue to Tour Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function selectMealType(type) {
        const startInput = document.getElementById('meal_type_text');
        startInput.value = type;
    }

    function beverage_preference(type) {
        const startInput = document.getElementById('beverage_preference_text');
        startInput.value = type;
    }

    let startAutocomplete = null;
    let endAutocomplete = null;
    let returnAutocomplete = null;

    function initAutocomplete() {
        // Initialize start location autocomplete
        const startInput = document.getElementById('startLocationInput');
        if (startInput) {
            startAutocomplete = new google.maps.places.Autocomplete(startInput, {
                types: ['geocode'],
                componentRestrictions: { country: 'lk' }, // Restrict to Sri Lanka
                fields: ['geometry', 'name', 'formatted_address']
            });

            startAutocomplete.addListener('place_changed', () => {
                const place = startAutocomplete.getPlace();
                handlePlaceSelection(place, 'start');
            });
        }

        // Initialize end location autocomplete
        const endInput = document.getElementById('endLocationInput');
        if (endInput) {
            endAutocomplete = new google.maps.places.Autocomplete(endInput, {
                types: ['geocode'],
                componentRestrictions: { country: 'lk' }, // Restrict to Sri Lanka
                fields: ['geometry', 'name', 'formatted_address']
            });

            endAutocomplete.addListener('place_changed', () => {
                const place = endAutocomplete.getPlace();
                handlePlaceSelection(place, 'end');
            });
        }

        // Initialize return location autocomplete
        const returnInput = document.getElementById('returnLocationInput');
        if (returnInput) {
            returnAutocomplete = new google.maps.places.Autocomplete(returnInput, {
                types: ['geocode'],
                componentRestrictions: { country: 'lk' },
                fields: ['geometry', 'name', 'formatted_address']
            });

            returnAutocomplete.addListener('place_changed', () => {
                const place = returnAutocomplete.getPlace();
                handlePlaceSelection(place, 'return');
            });
        }

        // Handle return type radio buttons
        document.querySelectorAll('input[name="return_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const returnLocationContainer = document.getElementById('returnLocationContainer');
                const returnLocationInput = document.getElementById('returnLocationInput');

                if (this.value === 'specific') {
                    returnLocationContainer.style.display = 'block';
                    returnLocationInput.required = true;
                } else {
                    returnLocationContainer.style.display = 'none';
                    returnLocationInput.required = false;
                    // Clear return location fields
                    document.getElementById('returnLocationLat').value = '';
                    document.getElementById('returnLocationLng').value = '';
                    document.getElementById('returnLocationName').value = '';
                    returnLocationInput.value = '';
                }
            });
        });

        // Add validation for group counts
        document.querySelectorAll('#adultCount, #childCount, #infantCount').forEach(input => {
            input.addEventListener('change', validateGroupCounts);
        });

        // Prevent form submission on enter key
        document.querySelectorAll('.location-input').forEach(input => {
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        });
    }

    function handlePlaceSelection(place, type) {
        if (!place.geometry) {
            console.log("No location data for this place");
            return;
        }

        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();
        const name = place.name || place.formatted_address;

        // Update hidden fields
        document.getElementById(`${type}LocationLat`).value = lat;
        document.getElementById(`${type}LocationLng`).value = lng;
        document.getElementById(`${type}LocationName`).value = name;

        // Optional: Add visual feedback
        const input = document.getElementById(`${type}LocationInput`);
        input.classList.add('is-valid');
        setTimeout(() => {
            input.classList.remove('is-valid');
        }, 2000);
    }

    function validateGroupCounts() {
        const adultCount = parseInt(document.getElementById('adultCount').value) || 0;
        const childCount = parseInt(document.getElementById('childCount').value) || 0;
        const infantCount = parseInt(document.getElementById('infantCount').value) || 0;
        const totalCount = adultCount + childCount + infantCount;

        // Ensure at least one adult
        if (adultCount < 1) {
            document.getElementById('adultCount').value = 1;
        }

        // Ensure non-negative numbers
        if (childCount < 0) document.getElementById('childCount').value = 0;
        if (infantCount < 0) document.getElementById('infantCount').value = 0;

        // Optional: Add maximum limit if needed
        if (totalCount > 20) {
            alert('Maximum group size is 20 persons');
            document.getElementById('adultCount').value = Math.min(adultCount, 20);
            document.getElementById('childCount').value = Math.min(childCount, 20 - adultCount);
            document.getElementById('infantCount').value = Math.min(infantCount, 20 - adultCount - childCount);
        }
    }

    // Error handling
    window.addEventListener('error', function(event) {
        console.error('Google Maps API Error:', event.error);
    });

    window.addEventListener('unhandledrejection', function(event) {
        console.error('Unhandled promise rejection:', event.reason);
    });

document.addEventListener('DOMContentLoaded', function() {
        // Handle preference card selection
        document.querySelectorAll('.preference-card').forEach(card => {
            card.addEventListener('click', function() {
                const input = this.querySelector('input');

                if (input.type === 'radio') {
                    // For radio buttons, remove selected class from all cards in the group
                    document.querySelectorAll(`.preference-card input[name="${input.name}"]`).forEach(radio => {
                        radio.closest('.preference-card').classList.remove('selected');
                    });
                }

                // Toggle selected class
                this.classList.toggle('selected');

                // Trigger input change
                input.checked = !input.checked;
                input.dispatchEvent(new Event('change'));
            });
        });

        // Initialize selected states
        document.querySelectorAll('.preference-card input:checked').forEach(input => {
            input.closest('.preference-card').classList.add('selected');
        });

        // Initialize all popovers
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true,
                sanitize: false
            });
        });

        // Optional: Close popovers when clicking outside
        document.addEventListener('click', function(event) {
            const popovers = document.querySelectorAll('.popover');
            popovers.forEach(popover => {
                if (!popover.contains(event.target) && !event.target.matches('.info-button')) {
                    const popoverInstance = bootstrap.Popover.getInstance(popover);
                    if (popoverInstance) {
                        popoverInstance.hide();
                    }
                }
            });
        });
});

    // Add these functions to handle group count changes
    function increaseCount(inputId) {
        const input = document.getElementById(inputId);
        const currentValue = parseInt(input.value) || 0;
        const maxValue = inputId === 'adultCount' ? 20 : 20 - getTotalCount() + currentValue;

        if (getTotalCount() < 20) {
            input.value = currentValue + 1;
            updateTotalCount();
            validateGroupCounts();
        }
    }

    function decreaseCount(inputId) {
        const input = document.getElementById(inputId);
        const currentValue = parseInt(input.value) || 0;
        const minValue = inputId === 'adultCount' ? 1 : 0;

        if (currentValue > minValue) {
            input.value = currentValue - 1;
            updateTotalCount();
            validateGroupCounts();
        }
    }

    function getTotalCount() {
        const adultCount = parseInt(document.getElementById('adultCount').value) || 0;
        const childCount = parseInt(document.getElementById('childCount').value) || 0;
        const infantCount = parseInt(document.getElementById('infantCount').value) || 0;
        return adultCount + childCount + infantCount;
    }

    function updateTotalCount() {
        document.getElementById('totalGroupSize').textContent = getTotalCount();
    }

    // Initialize total count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotalCount();
    });

    // Add these functions to handle date calculations
    function calculateTourDuration() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);

            // Validate dates
            if (end < start) {
                alert('End date cannot be before start date');
                document.getElementById('endDate').value = '';
                updateDurationDisplay(0, null, null);
                return;
            }

            // Calculate days difference
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            // Format dates for display
            const startFormatted = start.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            const endFormatted = end.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            updateDurationDisplay(diffDays, startFormatted, endFormatted);
        } else {
            updateDurationDisplay(0, null, null);
        }
    }

    function updateDurationDisplay(days, startDate, endDate) {
        const durationElement = document.getElementById('tourDuration');
        const rangeElement = document.getElementById('dateRangeDisplay');

        durationElement.textContent = days;

        if (startDate && endDate) {
            rangeElement.textContent = `${startDate} to ${endDate}`;
        } else {
            rangeElement.textContent = 'Select dates to see the tour period';
        }
    }

    // Add event listeners for date inputs
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');

        // Set min date for start date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.min = today;

        startDateInput.addEventListener('change', function() {
            // Update end date min to start date
            endDateInput.min = this.value.split('T')[0];
            calculateTourDuration();
        });

        endDateInput.addEventListener('change', calculateTourDuration);

        // Set default start date to current date and time
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        startDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
    });

    // Add this to your existing DOMContentLoaded event listener
    document.addEventListener('DOMContentLoaded', function() {
        // ... existing code ...

        // Handle location preference card selection
        const locationCards = document.querySelectorAll('.preference-card input[name="location_types[]"]');
        const selectedLocationsList = document.getElementById('selectedLocationsList');

        locationCards.forEach(card => {
            card.addEventListener('change', function() {
                updateSelectedLocations();
            });
        });

        function updateSelectedLocations() {
            const selectedCards = document.querySelectorAll('.preference-card input[name="location_types[]"]:checked');

            if (selectedCards.length === 0) {
                selectedLocationsList.innerHTML = '<div class="no-selections">No locations selected yet</div>';
                return;
            }

            selectedLocationsList.innerHTML = '';
            selectedCards.forEach(card => {
                const cardTitle = card.closest('.preference-card').querySelector('.card-title').textContent;
                const locationTag = document.createElement('div');
                locationTag.className = 'meal-tag';
                locationTag.innerHTML = `
                    ${cardTitle}
                    <span class="remove-meal" onclick="removeLocation('${card.value}')">
                        <i class="bi bi-x"></i>
                    </span>
                `;
                selectedLocationsList.appendChild(locationTag);
            });
        }

        // Initialize selected locations display
        updateSelectedLocations();
    });

    // Add this function to handle location removal
    function removeLocation(value) {
        const checkbox = document.querySelector(`input[name="location_types[]"][value="${value}"]`);
        if (checkbox) {
            checkbox.checked = false;
            checkbox.closest('.preference-card').classList.remove('selected');
            updateSelectedLocations();
        }
    }
</script>
