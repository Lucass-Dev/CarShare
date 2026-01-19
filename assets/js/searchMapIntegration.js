/**
 * GOOGLE MAPS INTEGRATION FOR SEARCH RESULTS
 * Interactive map with trip markers and routes
 */

let map;
let markers = [];
let routePolylines = [];
let infoWindows = [];
let currentHighlightedTrip = null;

/**
 * Initialize Google Maps when page loads
 */
function initSearchMap() {
    if (typeof google === 'undefined' || !google.maps) {
        console.error('Google Maps API not loaded');
        return;
    }
    
    if (!window.tripsMapData || window.tripsMapData.length === 0) {
        console.log('No trips data for map');
        return;
    }
    
    // Center map on first trip's start location
    const firstTrip = window.tripsMapData[0];
    const centerLat = parseFloat(firstTrip.start_lat);
    const centerLng = parseFloat(firstTrip.start_lng);
    
    // Create map
    const mapElement = document.getElementById('search-trips-map');
    if (!mapElement) {
        console.error('Map element not found');
        return;
    }
    
    map = new google.maps.Map(mapElement, {
        center: { lat: centerLat, lng: centerLng },
        zoom: 10,
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ],
        mapTypeControl: true,
        streetViewControl: false,
        fullscreenControl: true
    });
    
    // Add markers for all trips
    window.tripsMapData.forEach((trip, index) => {
        addTripToMap(trip, index);
    });
    
    // Fit map bounds to show all markers
    fitMapBounds();
}

/**
 * Add trip markers and route to map
 */
function addTripToMap(trip, index) {
    const startLat = parseFloat(trip.start_lat);
    const startLng = parseFloat(trip.start_lng);
    const endLat = parseFloat(trip.end_lat);
    const endLng = parseFloat(trip.end_lng);
    
    if (isNaN(startLat) || isNaN(startLng) || isNaN(endLat) || isNaN(endLng)) {
        console.warn('Invalid coordinates for trip:', trip.id);
        return;
    }
    
    // Start marker (green)
    const startMarker = new google.maps.Marker({
        position: { lat: startLat, lng: startLng },
        map: map,
        title: `Départ: ${trip.start_name}`,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 10,
            fillColor: '#4CAF50',
            fillOpacity: 1,
            strokeColor: '#ffffff',
            strokeWeight: 3
        },
        tripId: trip.id,
        isStart: true
    });
    
    // End marker (orange)
    const endMarker = new google.maps.Marker({
        position: { lat: endLat, lng: endLng },
        map: map,
        title: `Arrivée: ${trip.end_name}`,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 10,
            fillColor: '#FF9800',
            fillOpacity: 1,
            strokeColor: '#ffffff',
            strokeWeight: 3
        },
        tripId: trip.id,
        isStart: false
    });
    
    // Info window for trip details
    const infoContent = `
        <div style="font-family: Arial, sans-serif; padding: 10px; max-width: 250px;">
            <h3 style="margin: 0 0 10px 0; color: #1a1a2e; font-size: 16px;">
                ${trip.start_name} → ${trip.end_name}
            </h3>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #64748b;">Prix:</span>
                <strong style="color: #8f9bff; font-size: 18px;">${parseFloat(trip.price).toFixed(2)} €</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #64748b;">Places:</span>
                <strong>${trip.available_places}</strong>
            </div>
            <a href="index.php?action=trip_details&id=${trip.id}" 
               style="display: block; text-align: center; padding: 8px 16px; background: linear-gradient(135deg, #a9b2ff 0%, #8f9bff 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 10px;">
                Voir détails
            </a>
        </div>
    `;
    
    const infoWindow = new google.maps.InfoWindow({
        content: infoContent
    });
    
    // Click on marker to show info
    startMarker.addListener('click', () => {
        closeAllInfoWindows();
        infoWindow.open(map, startMarker);
        highlightTrip(trip.id);
    });
    
    endMarker.addListener('click', () => {
        closeAllInfoWindows();
        infoWindow.open(map, endMarker);
        highlightTrip(trip.id);
    });
    
    // Draw route line between start and end
    const routeLine = new google.maps.Polyline({
        path: [
            { lat: startLat, lng: startLng },
            { lat: endLat, lng: endLng }
        ],
        geodesic: true,
        strokeColor: '#a9b2ff',
        strokeOpacity: 0.6,
        strokeWeight: 3,
        map: map,
        tripId: trip.id
    });
    
    // Store references
    markers.push({ start: startMarker, end: endMarker, tripId: trip.id });
    routePolylines.push(routeLine);
    infoWindows.push(infoWindow);
}

/**
 * Fit map bounds to show all markers
 */
function fitMapBounds() {
    if (markers.length === 0) return;
    
    const bounds = new google.maps.LatLngBounds();
    markers.forEach(markerPair => {
        bounds.extend(markerPair.start.getPosition());
        bounds.extend(markerPair.end.getPosition());
    });
    
    map.fitBounds(bounds);
    
    // Add padding
    setTimeout(() => {
        const zoom = map.getZoom();
        if (zoom > 12) {
            map.setZoom(12);
        }
    }, 500);
}

/**
 * Close all open info windows
 */
function closeAllInfoWindows() {
    infoWindows.forEach(infoWindow => infoWindow.close());
}

/**
 * Highlight trip on map when hovering over card
 */
function highlightTripOnMap(tripId) {
    highlightTrip(tripId);
}

/**
 * Unhighlight trip when mouse leaves card
 */
function unhighlightTripOnMap() {
    if (currentHighlightedTrip) {
        const card = document.querySelector(`[data-trip-id="${currentHighlightedTrip}"]`);
        if (card) {
            card.classList.remove('highlighted');
        }
        
        // Reset markers and route
        markers.forEach(markerPair => {
            if (markerPair.tripId === currentHighlightedTrip) {
                markerPair.start.setIcon({
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: '#4CAF50',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 3
                });
                markerPair.end.setIcon({
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: '#FF9800',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 3
                });
            }
        });
        
        routePolylines.forEach(polyline => {
            if (polyline.tripId === currentHighlightedTrip) {
                polyline.setOptions({
                    strokeColor: '#a9b2ff',
                    strokeOpacity: 0.6,
                    strokeWeight: 3
                });
            }
        });
        
        currentHighlightedTrip = null;
    }
}

/**
 * Highlight specific trip
 */
function highlightTrip(tripId) {
    // Unhighlight previous
    unhighlightTripOnMap();
    
    currentHighlightedTrip = tripId;
    
    // Highlight card
    const card = document.querySelector(`[data-trip-id="${tripId}"]`);
    if (card) {
        card.classList.add('highlighted');
        card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    // Highlight markers
    markers.forEach(markerPair => {
        if (markerPair.tripId === tripId) {
            markerPair.start.setIcon({
                path: google.maps.SymbolPath.CIRCLE,
                scale: 14,
                fillColor: '#2E7D32',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 4
            });
            markerPair.end.setIcon({
                path: google.maps.SymbolPath.CIRCLE,
                scale: 14,
                fillColor: '#E65100',
                fillOpacity: 1,
                strokeColor: '#ffffff',
                strokeWeight: 4
            });
            
            // Center map on this trip
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(markerPair.start.getPosition());
            bounds.extend(markerPair.end.getPosition());
            map.fitBounds(bounds);
            
            setTimeout(() => {
                const zoom = map.getZoom();
                if (zoom > 13) {
                    map.setZoom(13);
                }
            }, 500);
        }
    });
    
    // Highlight route
    routePolylines.forEach(polyline => {
        if (polyline.tripId === tripId) {
            polyline.setOptions({
                strokeColor: '#8f9bff',
                strokeOpacity: 1,
                strokeWeight: 5,
                zIndex: 1000
            });
        }
    });
}

/**
 * Toggle map visibility (mobile)
 */
function toggleMapView() {
    const mapContainer = document.getElementById('search-map-container');
    const toggleBtn = document.getElementById('toggle-map-btn');
    const toggleText = document.getElementById('map-toggle-text');
    
    if (!mapContainer) return;
    
    if (mapContainer.style.display === 'none') {
        mapContainer.style.display = 'flex';
        if (toggleText) toggleText.textContent = 'Masquer la carte';
        
        // Initialize map if not already done
        if (!map) {
            setTimeout(initSearchMap, 100);
        } else {
            // Trigger resize event
            google.maps.event.trigger(map, 'resize');
            fitMapBounds();
        }
    } else {
        mapContainer.style.display = 'none';
        if (toggleText) toggleText.textContent = 'Afficher la carte';
    }
}

/**
 * Auto-initialize on desktop
 */
document.addEventListener('DOMContentLoaded', () => {
    // Check if we have trips data and map container
    if (window.tripsMapData && window.tripsMapData.length > 0) {
        const mapContainer = document.getElementById('search-map-container');
        
        // Auto-show on desktop (width > 1200px)
        if (window.innerWidth > 1200 && mapContainer) {
            mapContainer.style.display = 'flex';
            
            // Wait for Google Maps API to load
            if (typeof google !== 'undefined' && google.maps) {
                initSearchMap();
            } else {
                // Retry after a delay
                setTimeout(() => {
                    if (typeof google !== 'undefined' && google.maps) {
                        initSearchMap();
                    }
                }, 1000);
            }
        }
    }
});

/**
 * Handle window resize
 */
window.addEventListener('resize', () => {
    const mapContainer = document.getElementById('search-map-container');
    if (!mapContainer) return;
    
    if (window.innerWidth > 1200) {
        if (mapContainer.style.display === 'none') {
            mapContainer.style.display = 'flex';
            if (!map) {
                setTimeout(initSearchMap, 100);
            }
        }
    }
});
