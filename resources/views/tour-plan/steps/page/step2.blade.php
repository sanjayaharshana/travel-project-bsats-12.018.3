@extends('tour-plan.create')
@section('content_steps')



    @include('tour-plan.steps.wizard-step-indicator',['step'  => 2])

<style>
    #map {
        height: 500px;
        width: 100%;
        margin-bottom: 20px;
    }
    .route-options {
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .route-option {
        margin: 10px 0;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }
    .route-option:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }
    .route-option.active {
        background: #007bff;
        color: white;
        border-color: #0056b3;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .route-info {
        margin-top: 10px;
        padding: 10px;
        background: #fff;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    .route-details {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .route-mode {
        display: flex;
        align-items: center;
        padding: 5px 10px;
        background: #e9ecef;
        border-radius: 4px;
        margin-right: 10px;
    }
    .route-stats {
        display: flex;
        gap: 15px;
        color: #666;
    }
    .route-filter {
        margin-bottom: 15px;
        padding: 10px;
        background: #fff;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    .route-filter select {
        padding: 5px;
        margin-right: 10px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    .custom-marker {
        background-color: #007bff;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        border: 2px solid white;
        transition: all 0.3s ease;
    }
    .custom-marker.start {
        background-color: #28a745;
    }
    .custom-marker.end {
        background-color: #dc3545;
    }
    .custom-marker.return {
        background-color: #007bff;
    }
    .custom-marker.active {
        transform: scale(1.2);
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    .route-path {
        stroke-width: 5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .route-path.active {
        stroke-width: 7;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% {
            stroke-width: 5;
        }
        50% {
            stroke-width: 7;
        }
        100% {
            stroke-width: 5;
        }
    }
    .route-title {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .route-leg {
        margin: 15px 0;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 4px;
    }
    .route-steps {
        margin-top: 10px;
        padding-left: 20px;
    }
    .route-steps ol {
        margin-bottom: 0;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    .route-option.active .route-stats {
        color: white;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Travel Route Map</h2>
            <div id="map" data-map-id="tour-route-map"></div>

            <div class="route-filter">
                <select id="travelMode" onchange="updateRouteOptions()">
                    <option value="DRIVING">Driving</option>
                    <option value="WALKING">Walking</option>
                    <option value="BICYCLING">Bicycling</option>
                    <option value="TRANSIT">Transit</option>
                </select>
                <select id="routeType" onchange="updateRouteOptions()">
                    <option value="fastest">Fastest Route</option>
                    <option value="avoidHighways">Avoid Highways</option>
                    <option value="avoidTolls">Avoid Tolls</option>
                    <option value="scenic">Scenic Route</option>
                    <option value="all">All Routes</option>
                </select>
            </div>

            <div class="route-options">
                <h4>Suggested Routes</h4>
                @if($suggestedRoutes && $suggestedRoutes->routes_data && count($suggestedRoutes->routes_data) > 0)
                    <div id="suggestedRoutes">
                        @foreach($suggestedRoutes->routes_data as $index => $route)
                            <div class="route-option" data-route-index="{{ $index }}" onclick="selectRoute({{ $index }})">
                                <div class="route-info">
                                    <div class="route-title">{{ $route['route_name'] }}</div>
                                    <div class="route-stats">
                                        <span>{{ count($route['places']) }} places</span>
                                        <span>•</span>
                                        <span>Route {{ $index + 1 }}</span>
                                    </div>
                                </div>
                                <div class="route-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); previewRoute({{ $index }})">
                                        <i class="bi bi-eye"></i> Preview
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No suggested routes available. Please wait while we generate routes for you.
                    </div>
                @endif
            </div>

            <div id="routeInfo" class="route-info" style="display: none;">
                <h5>Route Details</h5>
                <div id="routeDetails"></div>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary" onclick="continueToNextStep()" id="continueBtn" style="display: none;">
                        <i class="bi bi-arrow-right"></i> Continue to Next Step
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tour data from the backend
const tourPlan = @json($tourPlan);
const suggestedRoutes = @json($suggestedRoutes);
const routesData = suggestedRoutes ? suggestedRoutes.routes_data : [];

// Location data from the backend
const startLocation = @json($startLocation);
const endLocation = @json($endLocation);
const returnLocation = @json($returnLocation);

// Create tourDetails from actual location data
const tourDetails = {
    start_location: {
        id: tourPlan.start_location,
        name: startLocation ? startLocation.location_name : 'Start Location',
        latitude: startLocation ? parseFloat(startLocation.latitude) : 6.9271,
        longitude: startLocation ? parseFloat(startLocation.longitude) : 79.8612
    },
    end_location: {
        id: tourPlan.end_location,
        name: endLocation ? endLocation.location_name : 'End Location',
        latitude: endLocation ? parseFloat(endLocation.latitude) : 7.2906,
        longitude: endLocation ? parseFloat(endLocation.longitude) : 80.6337
    },
    return_location: {
        id: tourPlan.return_location,
        name: returnLocation ? returnLocation.location_name : 'Return Location',
        latitude: returnLocation ? parseFloat(returnLocation.latitude) : 6.9271,
        longitude: returnLocation ? parseFloat(returnLocation.longitude) : 79.8612
    }
};

let map;
let directionsService;
let directionsRenderer;
let markers = [];
let locations = [];
let currentRoute = null;
let selectedRouteIndex = null;

// Load Google Maps API asynchronously
function loadGoogleMapsAPI() {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap`;
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
}

// Initialize the map
async function initMap() {
    try {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        const mapElement = document.getElementById('map');
        if (!mapElement) {
            console.error('Map element not found');
            return;
        }

        const mapId = mapElement.dataset.mapId;

        map = new Map(mapElement, {
            mapId: mapId,
            zoom: 12,
            center: { lat: 6.9271, lng: 79.8612 }, // Default to Colombo
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#007bff',
                strokeWeight: 5,
                strokeOpacity: 0.8
            }
        });

        // Add click listener to map to deselect route
        map.addListener('click', () => {
            deselectRoute();
        });

        processTourLocations();
        await addLocationMarkers(AdvancedMarkerElement);
        
        // Show first route by default if available
        if (routesData.length > 0) {
            selectRoute(0);
        }
    } catch (error) {
        console.error('Error initializing map:', error);
    }
}

function processTourLocations() {
    locations = [];

    // Validate tourDetails exists and has required properties
    if (!tourDetails) {
        console.error('Tour details not found');
        return;
    }

    // Start location with validation
    if (tourDetails.start_location && tourDetails.start_location.latitude && tourDetails.start_location.longitude) {
        locations.push({
            lat: parseFloat(tourDetails.start_location.latitude),
            lng: parseFloat(tourDetails.start_location.longitude),
            name: tourDetails.start_location.name || 'Start Location',
            type: 'start'
        });
    } else {
        console.warn('Start location data is missing or incomplete');
    }

    // End location with validation
    if (tourDetails.end_location && tourDetails.end_location.latitude && tourDetails.end_location.longitude) {
        locations.push({
            lat: parseFloat(tourDetails.end_location.latitude),
            lng: parseFloat(tourDetails.end_location.longitude),
            name: tourDetails.end_location.name || 'End Location',
            type: 'end'
        });
    } else {
        console.warn('End location data is missing or incomplete');
    }

    // Return location with validation
    if (tourDetails.return_location && tourDetails.return_location.latitude && tourDetails.return_location.longitude) {
        locations.push({
            lat: parseFloat(tourDetails.return_location.latitude),
            lng: parseFloat(tourDetails.return_location.longitude),
            name: tourDetails.return_location.name || 'Return Location',
            type: 'return'
        });
    }
}

async function addLocationMarkers(AdvancedMarkerElement) {
    // Clear existing markers
    markers.forEach(marker => marker.map = null);
    markers = [];

    // Validate locations array
    if (!locations || locations.length === 0) {
        console.warn('No locations to add markers for');
        return;
    }

    locations.forEach((location, index) => {
        // Validate location data
        if (!location || typeof location.lat !== 'number' || typeof location.lng !== 'number') {
            console.warn(`Invalid location data at index ${index}:`, location);
            return;
        }

        try {
            const markerContent = createMarkerContent(index + 1, location.type);
            const marker = new AdvancedMarkerElement({
                map,
                position: { lat: location.lat, lng: location.lng },
                title: location.name,
                content: markerContent
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<strong>${location.name}</strong>`
            });

            marker.addListener('gmp-click', () => {
                infoWindow.open(map, marker);
            });

            markers.push(marker);
        } catch (error) {
            console.error(`Error creating marker for location ${index}:`, error);
        }
    });

    // Fit map to show all markers
    if (markers.length > 0) {
        const bounds = new google.maps.LatLngBounds();
        markers.forEach(marker => bounds.extend(marker.position));
        map.fitBounds(bounds);
    }
}

function createMarkerContent(number, type) {
    const div = document.createElement('div');
    div.className = `custom-marker ${type}`;
    div.textContent = number;
    return div;
}

// Function to select a route
function selectRoute(routeIndex) {
    // Remove active class from all route options
    document.querySelectorAll('.route-option').forEach(option => {
        option.classList.remove('active');
    });

    // Add active class to selected route
    const selectedOption = document.querySelector(`[data-route-index="${routeIndex}"]`);
    if (selectedOption) {
        selectedOption.classList.add('active');
    }

    selectedRouteIndex = routeIndex;
    displayRouteOnMap(routeIndex);
    showRouteDetails(routeIndex);
    
    // Show continue button
    document.getElementById('continueBtn').style.display = 'inline-block';
}

// Function to preview a route
function previewRoute(routeIndex) {
    displayRouteOnMap(routeIndex);
    showRouteDetails(routeIndex);
}

// Function to display route on map
function displayRouteOnMap(routeIndex) {
    if (!routesData[routeIndex]) {
        console.error('Route data not found for index:', routeIndex);
        return;
    }

    const route = routesData[routeIndex];
    const places = route.places;

    if (places.length < 2) {
        console.error('Route must have at least 2 places');
        return;
    }

    // Create waypoints for the route
    const waypoints = places.slice(1, -1).map(place => ({
        location: new google.maps.LatLng(place.coordinates.lat, place.coordinates.lng),
        stopover: true
    }));

    const request = {
        origin: new google.maps.LatLng(places[0].coordinates.lat, places[0].coordinates.lng),
        destination: new google.maps.LatLng(places[places.length - 1].coordinates.lat, places[places.length - 1].coordinates.lng),
        waypoints: waypoints,
        travelMode: google.maps.TravelMode.DRIVING,
        optimizeWaypoints: false
    };

    directionsService.route(request, (result, status) => {
        if (status === 'OK') {
            directionsRenderer.setDirections(result);
            currentRoute = result;
        } else {
            console.error('Directions request failed due to ' + status);
        }
    });
}

// Function to show route details
function showRouteDetails(routeIndex) {
    if (!routesData[routeIndex]) {
        console.error('Route data not found for index:', routeIndex);
        return;
    }

    const route = routesData[routeIndex];
    const routeInfo = document.getElementById('routeInfo');
    const routeDetails = document.getElementById('routeDetails');

    let detailsHtml = `
        <div class="route-title">${route.route_name}</div>
        <div class="route-stats">
            <span><i class="bi bi-geo-alt"></i> ${route.places.length} places</span>
        </div>
        <div class="route-legs">
    `;

    route.places.forEach((place, index) => {
        detailsHtml += `
            <div class="route-leg">
                <strong>${index + 1}.</strong> ${place.name}
                <small class="text-muted">(${place.coordinates.lat.toFixed(4)}, ${place.coordinates.lng.toFixed(4)})</small>
            </div>
        `;
    });

    detailsHtml += '</div>';

    routeDetails.innerHTML = detailsHtml;
    routeInfo.style.display = 'block';
}

// Function to deselect route
function deselectRoute() {
    selectedRouteIndex = null;
    document.querySelectorAll('.route-option').forEach(option => {
        option.classList.remove('active');
    });
    
    if (directionsRenderer) {
        directionsRenderer.setDirections({ routes: [] });
    }
    
    document.getElementById('routeInfo').style.display = 'none';
    document.getElementById('continueBtn').style.display = 'none';
}

// Function to continue to next step
function continueToNextStep() {
    if (selectedRouteIndex === null) {
        alert('Please select a route first');
        return;
    }

    // Here you can add AJAX call to save the selected route
    // For now, we'll just redirect to the next step
    const tourId = {{ $tourId }};
    const selectedRoute = routesData[selectedRouteIndex];
    
    // You can add AJAX call here to save the selected route
    // Example:
    /*
    fetch(`/tour-plan/${tourId}/save-route`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            selected_route_index: selectedRouteIndex,
            selected_route: selectedRoute
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect_url;
        }
    });
    */
    
    // For now, just redirect to next step (you can modify this URL as needed)
    window.location.href = `/tour-plan/${tourId}/step3`;
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadGoogleMapsAPI();
});
</script>

@endsection
