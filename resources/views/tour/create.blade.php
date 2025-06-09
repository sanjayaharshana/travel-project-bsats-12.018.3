@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Create Travel Route</h4>
                </div>
                <div class="card-body">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Generating routes...</span>
                        </div>
                        <p class="mt-2">Generating travel routes with AI... This may take up to 10 minutes for complex routes.</p>
                        <div class="progress mt-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: 0%" 
                                 id="progressBar">0%</div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form id="tourForm" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Start Location -->
                        <div class="mb-3">
                            <label for="start_location" class="form-label">Start Location *</label>
                            <select class="form-select" id="start_location" name="start_location" required>
                                <option value="">Select start location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" 
                                            data-lat="{{ $location->latitude }}" 
                                            data-lng="{{ $location->longitude }}">
                                        {{ $location->location_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a start location.
                            </div>
                        </div>

                        <!-- End Location -->
                        <div class="mb-3">
                            <label for="end_location" class="form-label">End Location *</label>
                            <select class="form-select" id="end_location" name="end_location" required>
                                <option value="">Select end location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" 
                                            data-lat="{{ $location->latitude }}" 
                                            data-lng="{{ $location->longitude }}">
                                        {{ $location->location_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select an end location.
                            </div>
                        </div>

                        <!-- Return Location -->
                        <div class="mb-3">
                            <label for="return_location" class="form-label">Return Location *</label>
                            <select class="form-select" id="return_location" name="return_location" required>
                                <option value="">Select return location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" 
                                            data-lat="{{ $location->latitude }}" 
                                            data-lng="{{ $location->longitude }}">
                                        {{ $location->location_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a return location.
                            </div>
                        </div>

                        <!-- Route Preferences -->
                        <div class="mb-3">
                            <label class="form-label">Route Preferences</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="scenic_route" name="preferences[]" value="scenic">
                                <label class="form-check-label" for="scenic_route">
                                    Prefer scenic routes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="fastest_route" name="preferences[]" value="fastest">
                                <label class="form-check-label" for="fastest_route">
                                    Prefer fastest routes
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="avoid_tolls" name="preferences[]" value="avoid_tolls">
                                <label class="form-check-label" for="avoid_tolls">
                                    Avoid toll roads
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-route me-2"></i>Generate Routes
                            </button>
                        </div>
                    </form>

                    <!-- Results Section -->
                    <div id="resultsSection" class="mt-4 d-none">
                        <h5>Generated Routes</h5>
                        <div id="routesContainer"></div>
                    </div>

                    <!-- Error Alert -->
                    <div id="errorAlert" class="alert alert-danger mt-3 d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="errorMessage"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Routes Generated Successfully!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Routes will be displayed here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveRoutesBtn">Save Routes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .route-card {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
    }
    
    .route-card:hover {
        background-color: #e9ecef;
        transition: background-color 0.2s;
    }
    
    .place-item {
        background-color: white;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin: 0.25rem 0;
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    
    .progress {
        height: 20px;
    }
    
    .btn:disabled {
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let requestTimeout;
    let progressInterval;
    let currentProgress = 0;
    
    // Form submission
    $('#tourForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        // Show loading state
        showLoading();
        
        // Prepare form data
        const formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        // Start progress animation
        startProgress();
        
        // Make AJAX request with extended timeout
        $.ajax({
            url: '{{ route("tour.generate-routes") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: {{ config('tour.timeouts.ajax_request', 120) * 1000 }}, // Convert to milliseconds
            success: function(response) {
                clearTimeout(requestTimeout);
                clearInterval(progressInterval);
                hideLoading();
                
                if (response.success) {
                    showResults(response.data);
                    showSuccessModal(response.data);
                } else {
                    showError(response.message || 'Failed to generate routes');
                }
            },
            error: function(xhr, status, error) {
                clearTimeout(requestTimeout);
                clearInterval(progressInterval);
                hideLoading();
                
                let errorMessage = 'An error occurred while generating routes.';
                
                if (status === 'timeout') {
                    errorMessage = 'Request timed out. The AI is taking longer than expected. Please try again.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    errorMessage = 'Please check your input and try again.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                showError(errorMessage);
            }
        });
        
        // Set timeout warning from config
        requestTimeout = setTimeout(function() {
            if ($('#loadingSpinner').is(':visible')) {
                showTimeoutWarning();
            }
        }, {{ config('tour.timeouts.warning_threshold', 90) * 1000 }}); // Convert to milliseconds
    });
    
    // Form validation
    function validateForm() {
        const form = document.getElementById('tourForm');
        
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
            return false;
        }
        
        // Check if all locations are selected
        const startLocation = $('#start_location').val();
        const endLocation = $('#end_location').val();
        const returnLocation = $('#return_location').val();
        
        if (!startLocation || !endLocation || !returnLocation) {
            showError('Please select all required locations.');
            return false;
        }
        
        // Check for duplicate locations
        if (startLocation === endLocation || startLocation === returnLocation || endLocation === returnLocation) {
            showError('Please select different locations for start, end, and return.');
            return false;
        }
        
        return true;
    }
    
    // Show loading state
    function showLoading() {
        $('#loadingSpinner').removeClass('d-none');
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Generating...');
        $('#errorAlert').addClass('d-none');
        $('#resultsSection').addClass('d-none');
    }
    
    // Hide loading state
    function hideLoading() {
        $('#loadingSpinner').addClass('d-none');
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-route me-2"></i>Generate Routes');
        currentProgress = 0;
        $('#progressBar').css('width', '0%').text('0%');
    }
    
    // Start progress animation
    function startProgress() {
        currentProgress = 0;
        progressInterval = setInterval(function() {
            if (currentProgress < 90) {
                currentProgress += Math.random() * 10;
                $('#progressBar').css('width', currentProgress + '%').text(Math.round(currentProgress) + '%');
            }
        }, 1000);
    }
    
    // Show timeout warning
    function showTimeoutWarning() {
        $('#progressBar').removeClass('progress-bar-animated').addClass('bg-warning');
        $('#loadingSpinner p').html('Still processing... This is taking longer than usual. Please wait.');
    }
    
    // Show error message
    function showError(message) {
        $('#errorMessage').text(message);
        $('#errorAlert').removeClass('d-none');
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('#errorAlert').addClass('d-none');
        }, 5000);
    }
    
    // Show results
    function showResults(data) {
        const container = $('#routesContainer');
        container.empty();
        
        if (data.routes && data.routes.length > 0) {
            data.routes.forEach(function(route, index) {
                const routeHtml = `
                    <div class="route-card">
                        <h6 class="text-primary">${route.route_name}</h6>
                        <div class="places-list">
                            ${route.places.map(function(place, placeIndex) {
                                return `
                                    <div class="place-item">
                                        <strong>${placeIndex + 1}. ${place.name}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Lat: ${place.coordinates.lat}, Lng: ${place.coordinates.lng}
                                        </small>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
                container.append(routeHtml);
            });
        }
        
        $('#resultsSection').removeClass('d-none');
    }
    
    // Show success modal
    function showSuccessModal(data) {
        const modalBody = $('#modalBody');
        modalBody.html($('#routesContainer').html());
        $('#successModal').modal('show');
    }
    
    // Save routes button
    $('#saveRoutesBtn').on('click', function() {
        const routeId = $('#successModal').data('route-id');
        if (routeId) {
            window.location.href = `/tour/routes/${routeId}`;
        }
    });
    
    // Location change handlers
    $('select[name="start_location"], select[name="end_location"], select[name="return_location"]').on('change', function() {
        $('#errorAlert').addClass('d-none');
    });
    
    // Prevent form resubmission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});
</script>
@endpush 