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
                <h4>Route Suggestions</h4>
                <div id="routeOptions"></div>
            </div>

            <div id="routeInfo" class="route-info" style="display: none;">
                <h5>Route Details</h5>
                <div id="routeDetails"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Tour details data from the backend
const tourDetails = @json($tourDetails);

let map;
let directionsService;
let directionsRenderer;
let markers = [];
let locations = [];
let currentRoute = null;

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
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    const mapElement = document.getElementById('map');
    const mapId = mapElement.dataset.mapId;

    map = new Map(mapElement, {
        mapId: mapId,
        zoom: 12,
        center: { lat: 0, lng: 0 },
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
    updateRouteOptions();
}

function processTourLocations() {
    locations = [];
    
    // Start location
    locations.push({
        lat: parseFloat(tourDetails.start_location.latitude),
        lng: parseFloat(tourDetails.start_location.longitude),
        name: tourDetails.start_location.name || 'Start Location',
        type: 'start'
    });

    // Return location (if available)
    if (tourDetails.return_location) {
        locations.push({
            lat: parseFloat(tourDetails.return_location.latitude),
            lng: parseFloat(tourDetails.return_location.longitude),
            name: tourDetails.return_location.name || 'Return Location',
            type: 'return'
        });
    }

    // End location
    locations.push({
        lat: parseFloat(tourDetails.end_location.latitude),
        lng: parseFloat(tourDetails.end_location.longitude),
        name: tourDetails.end_location.name || 'End Location',
        type: 'end'
    });
}

async function addLocationMarkers(AdvancedMarkerElement) {
    // Clear existing markers
    markers.forEach(marker => marker.map = null);
    markers = [];

    locations.forEach((location, index) => {
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
    });

    const bounds = new google.maps.LatLngBounds();
    markers.forEach(marker => bounds.extend(marker.position));
    map.fitBounds(bounds);
}

function createMarkerContent(number, type) {
    const div = document.createElement('div');
    div.className = `custom-marker ${type}`;
    div.textContent = number;
    return div;
}

function updateRouteOptions() {
    const travelMode = document.getElementById('travelMode').value;
    const routeType = document.getElementById('routeType').value;
    const routeOptionsContainer = document.getElementById('routeOptions');
    routeOptionsContainer.innerHTML = '';

    getRouteAlternatives(travelMode, routeType);
}

function getRouteAlternatives(travelMode, routeType) {
    const routeOptionsContainer = document.getElementById('routeOptions');
    routeOptionsContainer.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

    // Generate different route combinations
    const routeCombinations = generateRouteCombinations();
    
    // Create route requests for each combination with different preferences
    const requests = [];
    
    routeCombinations.forEach(combination => {
        const baseRequest = {
            origin: new google.maps.LatLng(combination.origin.lat, combination.origin.lng),
            destination: new google.maps.LatLng(combination.destination.lat, combination.destination.lng),
            travelMode: google.maps.TravelMode[travelMode],
            waypoints: combination.waypoints.map(point => ({
                location: new google.maps.LatLng(point.lat, point.lng),
                stopover: true
            }))
        };

        // Store route metadata separately
        const routeMetadata = {
            type: 'fastest',
            description: `${combination.description} (Fastest)`,
            request: { ...baseRequest, optimizeWaypoints: false }
        };
        requests.push(routeMetadata);

        // Add route with different waypoint order for alternative path
        if (combination.waypoints.length > 0) {
            const reversedWaypoints = [...combination.waypoints].reverse();
            requests.push({
                type: 'alternative',
                description: `${combination.description} (Alternative Path)`,
                request: {
                    ...baseRequest,
                    waypoints: reversedWaypoints.map(point => ({
                        location: new google.maps.LatLng(point.lat, point.lng),
                        stopover: true
                    })),
                    optimizeWaypoints: false
                }
            });
        }

        // Add route avoiding highways
        requests.push({
            type: 'avoidHighways',
            description: `${combination.description} (Avoid Highways)`,
            request: {
                ...baseRequest,
                avoidHighways: true,
                optimizeWaypoints: false
            }
        });

        // Add route avoiding tolls
        requests.push({
            type: 'avoidTolls',
            description: `${combination.description} (Avoid Tolls)`,
            request: {
                ...baseRequest,
                avoidTolls: true,
                optimizeWaypoints: false
            }
        });

        // Add scenic route preference
        requests.push({
            type: 'scenic',
            description: `${combination.description} (Scenic)`,
            request: {
                ...baseRequest,
                avoidHighways: true,
                avoidTolls: true,
                optimizeWaypoints: false
            }
        });

        // Add route with optimized waypoints for another alternative
        if (combination.waypoints.length > 1) {
            requests.push({
                type: 'optimized',
                description: `${combination.description} (Optimized)`,
                request: {
                    ...baseRequest,
                    optimizeWaypoints: true
                }
            });
        }
    });

    // Filter requests based on selected route type
    const filteredRequests = requests.filter(route => {
        if (routeType === 'fastest') return route.type === 'fastest';
        if (routeType === 'avoidHighways') return route.type === 'avoidHighways';
        if (routeType === 'avoidTolls') return route.type === 'avoidTolls';
        if (routeType === 'scenic') return route.type === 'scenic';
        return true;
    });

    // Clear previous routes
    directionsRenderer.setMap(null);
    document.getElementById('routeInfo').style.display = 'none';
    document.querySelectorAll('.custom-marker').forEach(marker => {
        marker.classList.remove('active');
    });

    // Fetch all routes
    Promise.all(filteredRequests.map(routeMetadata => 
        new Promise((resolve, reject) => {
            directionsService.route(routeMetadata.request, (result, status) => {
                if (status === 'OK') {
                    resolve({
                        route: result.routes[0],
                        description: routeMetadata.description,
                        type: routeMetadata.type
                    });
                } else {
                    reject(status);
                }
            });
        })
    ))
    .then(results => {
        displayRouteAlternatives(results, travelMode);
    })
    .catch(error => {
        console.error('Error fetching routes:', error);
        routeOptionsContainer.innerHTML = '<div class="alert alert-warning">No routes found for the selected options. Please try different settings.</div>';
    });
}

function generateRouteCombinations() {
    const combinations = [];
    const start = locations[0];
    const end = locations[locations.length - 1];

    // Direct route (start to end)
    combinations.push({
        origin: start,
        destination: end,
        waypoints: [],
        description: 'Direct Route'
    });

    // If we have intermediate locations, generate combinations
    if (locations.length > 2) {
        const intermediatePoints = locations.slice(1, -1);
        
        // Generate all possible permutations of intermediate points
        const permutations = getPermutations(intermediatePoints);
        
        // Add each permutation as a route combination
        permutations.forEach(permutation => {
            combinations.push({
                origin: start,
                destination: end,
                waypoints: permutation,
                description: `Via ${permutation.map(p => p.name).join(' → ')}`
            });
        });
    }

    return combinations;
}

function getPermutations(arr) {
    if (arr.length <= 1) return [arr];
    
    const result = [];
    for (let i = 0; i < arr.length; i++) {
        const current = arr[i];
        const remaining = [...arr.slice(0, i), ...arr.slice(i + 1)];
        const remainingPerms = getPermutations(remaining);
        
        for (const perm of remainingPerms) {
            result.push([current, ...perm]);
        }
    }
    
    return result;
}

function displayRouteAlternatives(results, travelMode) {
    const routeOptionsContainer = document.getElementById('routeOptions');
    routeOptionsContainer.innerHTML = '';

    // Group routes by their base description
    const groupedRoutes = results.reduce((groups, {route, description, type}) => {
        const baseDescription = description.split(' (')[0];
        if (!groups[baseDescription]) {
            groups[baseDescription] = [];
        }
        groups[baseDescription].push({route, description, type});
        return groups;
    }, {});

    // Display routes by group
    Object.entries(groupedRoutes).forEach(([baseDescription, routes], groupIndex) => {
        const groupDiv = document.createElement('div');
        groupDiv.className = 'route-group';
        groupDiv.innerHTML = `<h5 class="route-group-title">${baseDescription}</h5>`;

        routes.forEach(({route, description, type}, index) => {
            const routeDiv = document.createElement('div');
            routeDiv.className = 'route-option';
            
            const routeDetails = document.createElement('div');
            routeDetails.className = 'route-details';
            
            // Calculate totals
            let totalDistance = 0;
            let totalDuration = 0;
            route.legs.forEach(leg => {
                totalDistance += leg.distance.value;
                totalDuration += leg.duration.value;
            });

            const routeColor = getRouteColor(groupIndex);
            
            routeDetails.innerHTML = `
                <div class="route-mode" style="border-left: 4px solid ${routeColor}">
                    <div class="route-title">${description}</div>
                    <div class="route-stats">
                        <span><i class="fas fa-road"></i> ${formatDistance(totalDistance)}</span>
                        <span><i class="fas fa-clock"></i> ${formatDuration(totalDuration)}</span>
                    </div>
                </div>
            `;

            routeDiv.appendChild(routeDetails);
            routeDiv.onclick = () => showRoute(route, travelMode, groupIndex, description);
            groupDiv.appendChild(routeDiv);
        });

        routeOptionsContainer.appendChild(groupDiv);
    });
}

function formatDistance(meters) {
    const km = meters / 1000;
    return km.toFixed(1) + ' km';
}

function formatDuration(seconds) {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    
    if (hours > 0) {
        return `${hours}h ${minutes}m`;
    }
    return `${minutes} min`;
}

function showRoute(route, travelMode, routeIndex, description) {
    event.stopPropagation();
    
    // Clear previous routes and markers
    directionsRenderer.setMap(null);
    document.querySelectorAll('.custom-marker').forEach(marker => {
        marker.classList.remove('active');
    });
    
    // Create new directions renderer with custom styling
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: true, // We'll use our custom markers
        polylineOptions: {
            strokeColor: getRouteColor(routeIndex),
            strokeWeight: 5,
            strokeOpacity: 0.8
        },
        preserveViewport: false // Allow map to fit bounds
    });

    // Set the route
    directionsRenderer.setDirections({ routes: [route] });

    // Update active route option
    document.querySelectorAll('.route-option').forEach(option => {
        option.classList.remove('active');
    });
    event.currentTarget.classList.add('active');

    // Highlight markers along the route
    highlightRouteMarkers(route);

    // Show route details
    displayRouteDetails(route, travelMode, routeIndex, description);

    // Fit map to show entire route with padding
    const bounds = new google.maps.LatLngBounds();
    route.legs.forEach(leg => {
        // Add start and end points
        bounds.extend(leg.start_location);
        bounds.extend(leg.end_location);
        
        // Add intermediate points from steps for better bounds
        leg.steps.forEach(step => {
            bounds.extend(step.start_location);
            bounds.extend(step.end_location);
        });
    });

    // Add padding to bounds
    const padding = {
        top: 50,
        right: 50,
        bottom: 50,
        left: 50
    };
    
    map.fitBounds(bounds, padding);

    // Add click listener to map to deselect route
    map.addListener('click', () => {
        deselectRoute();
    });
}

function displayRouteDetails(route, travelMode, routeIndex, description) {
    const routeInfo = document.getElementById('routeInfo');
    const routeDetails = document.getElementById('routeDetails');

    // Calculate totals
    let totalDistance = 0;
    let totalDuration = 0;
    route.legs.forEach(leg => {
        totalDistance += leg.distance.value;
        totalDuration += leg.duration.value;
    });

    let detailsHtml = `
        <h6>${description}</h6>
        <p><strong>Travel Mode:</strong> ${travelMode}</p>
        <p><strong>Total Distance:</strong> ${formatDistance(totalDistance)}</p>
        <p><strong>Estimated Duration:</strong> ${formatDuration(totalDuration)}</p>
    `;

    // Add details for each leg of the journey
    route.legs.forEach((leg, index) => {
        detailsHtml += `
            <div class="route-leg">
                <h6>${index === 0 ? 'Start' : 'Via'} ${leg.start_address}</h6>
                <p><strong>To:</strong> ${leg.end_address}</p>
                <p><strong>Distance:</strong> ${leg.distance.text}</p>
                <p><strong>Duration:</strong> ${leg.duration.text}</p>
            </div>
        `;

        if (leg.steps && leg.steps.length > 0) {
            detailsHtml += '<div class="route-steps"><ol>';
            leg.steps.forEach(step => {
                detailsHtml += `<li>${step.instructions} (${step.distance.text})</li>`;
            });
            detailsHtml += '</ol></div>';
        }
    });

    routeDetails.innerHTML = detailsHtml;
    routeInfo.style.display = 'block';
}

function deselectRoute() {
    // Clear route
    directionsRenderer.setMap(null);
    
    // Remove active state from route options
    document.querySelectorAll('.route-option').forEach(option => {
        option.classList.remove('active');
    });
    
    // Reset markers
    document.querySelectorAll('.custom-marker').forEach(marker => {
        marker.classList.remove('active');
    });
    
    // Hide route details
    document.getElementById('routeInfo').style.display = 'none';
    
    // Fit map to show all markers
    const bounds = new google.maps.LatLngBounds();
    markers.forEach(marker => bounds.extend(marker.position));
    map.fitBounds(bounds);
}

function highlightRouteMarkers(route) {
    // Get all locations from the route
    const routeLocations = new Set();
    route.legs.forEach(leg => {
        routeLocations.add(leg.start_location.lat() + ',' + leg.start_location.lng());
        routeLocations.add(leg.end_location.lat() + ',' + leg.end_location.lng());
    });

    // Highlight markers that are part of the route
    markers.forEach(marker => {
        const position = marker.position;
        const locationKey = position.lat + ',' + position.lng;
        if (routeLocations.has(locationKey)) {
            marker.content.classList.add('active');
            
            // Create or update info window for the marker
            const infoWindow = new google.maps.InfoWindow({
                content: `<strong>${marker.title}</strong>`
            });

            // Add click listener to marker
            marker.addListener('gmp-click', () => {
                infoWindow.open(map, marker);
            });
        }
    });
}

function getRouteColor(index) {
    const colors = [
        '#007bff', // Blue
        '#28a745', // Green
        '#dc3545', // Red
        '#ffc107', // Yellow
        '#17a2b8', // Cyan
        '#6610f2', // Purple
        '#fd7e14', // Orange
        '#20c997'  // Teal
    ];
    return colors[index % colors.length];
}

// Load Google Maps API when the page loads
document.addEventListener('DOMContentLoaded', loadGoogleMapsAPI);

// Add new styles for route groups
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    .route-group {
        margin-bottom: 20px;
        padding: 10px;
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .route-group-title {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #007bff;
        color: #007bff;
    }
    .route-option {
        margin: 5px 0;
    }
    .route-option:hover {
        background: #f8f9fa;
    }
    .route-option.active {
        background: #e9ecef;
    }
`;
document.head.appendChild(styleSheet);

// Update the route filter HTML to include all route types
document.addEventListener('DOMContentLoaded', () => {
    const routeFilter = document.querySelector('.route-filter');
    if (routeFilter) {
        routeFilter.innerHTML = `
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
        `;
    }
});
</script>
