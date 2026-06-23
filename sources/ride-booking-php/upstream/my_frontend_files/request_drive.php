<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
$user_username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book a Ride - UCY Carpooling</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
    #map { 
        height: 440px; 
        border-radius: 12px; 
        margin-bottom: 20px;
        border: 2px solid #e5e7eb;
    }

    @media (max-width: 768px) {
        #map { height: 300px; }
    }

    .results-box {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 5000;
        width: 100%;
        background: white;
        border: 1px solid #ddd;
        border-radius: 12px;
        max-height: 220px;
        overflow-y: auto;
        display: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .results-box div {
        padding: 10px 14px;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
    }

    .results-box div:hover {
        background-color: #f3f4f6;
        cursor: pointer;
    }

    .results-box div:last-child {
        border-bottom: none;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-overlay.active {
        display: flex;
    }

    .spinner {
        border: 4px solid #f3f4f6;
        border-top: 4px solid #4F46E5;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .route-calculating {
        background: #FEF3C7;
        border-color: #F59E0B;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
</style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="bg-white p-6 rounded-lg shadow-xl text-center">
        <div class="spinner mx-auto mb-4"></div>
        <p class="text-gray-700 font-semibold">Processing your request...</p>
    </div>
</div>

<!-- Header -->
<div class="bg-white shadow-sm border-b mb-6">
    <div class="max-w-6xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Carpooling Car Icon -->
                <svg class="h-16 w-16" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <rect x="15" y="45" width="70" height="25" rx="5" fill="#4F46E5"/>
                    <path d="M 20 45 L 30 30 L 70 30 L 80 45 Z" fill="#6366F1"/>
                    <rect x="32" y="35" width="15" height="8" rx="2" fill="#E0E7FF"/>
                    <rect x="53" y="35" width="15" height="8" rx="2" fill="#E0E7FF"/>
                    <circle cx="30" cy="70" r="8" fill="#1F2937"/>
                    <circle cx="30" cy="70" r="5" fill="#6B7280"/>
                    <circle cx="70" cy="70" r="8" fill="#1F2937"/>
                    <circle cx="70" cy="70" r="5" fill="#6B7280"/>
                    <circle cx="40" cy="50" r="3" fill="#FCD34D"/>
                    <circle cx="50" cy="50" r="3" fill="#FCD34D"/>
                    <circle cx="60" cy="50" r="3" fill="#FCD34D"/>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Book a Ride</h1>
                    <p class="text-sm text-gray-600">University of Cyprus - Carpooling System</p>
                </div>
            </div>
            <div class="hidden md:block text-right">
                <p class="text-sm text-gray-600">Logged in as</p>
                <p class="font-semibold text-gray-900"><?= htmlspecialchars($user_username) ?></p>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 pb-8">

    <!-- Info Banner -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow mb-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-semibold text-blue-800">How to Book</h3>
                <p class="text-sm text-blue-700">Search for locations or click on the map to set pickup and destination points. Drag markers to adjust.</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-xl">

        <!-- Search Boxes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            <div class="search-box relative">
                <label class="font-semibold mb-2 block text-gray-700">
                    <svg class="inline w-5 h-5 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Pickup Location *
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-400">🔍</span>
                    <input id="pickupSearch"
                        type="text"
                        placeholder="Search pickup location..."
                        class="w-full pl-10 p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"
                        autocomplete="off">
                </div>
                <div id="pickupResults" class="results-box"></div>
            </div>

            <div class="search-box relative">
                <label class="font-semibold mb-2 block text-gray-700">
                    <svg class="inline w-5 h-5 text-red-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Destination *
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-400">📍</span>
                    <input id="destSearch"
                        type="text"
                        placeholder="Search destination..."
                        class="w-full pl-10 p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-red-400 focus:border-red-400 transition"
                        autocomplete="off">
                </div>
                <div id="destResults" class="results-box"></div>
            </div>

        </div>

        <!-- Map -->
        <div id="map"></div>

        <!-- Route Info -->
        <div id="routeInfo" class="hidden mt-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border-2 border-indigo-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Distance</p>
                        <p class="text-2xl font-bold text-indigo-700"><span id="distance_km">0</span> km</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-gray-600">Estimated Cost</p>
                        <p class="text-2xl font-bold text-green-700">€<span id="estimated_cost">0</span></p>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3 text-center">
                <svg class="inline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Cost is calculated as: €5 base fare + €0.60 per km
            </p>
        </div>

        <!-- Form -->
        <form id="driveRequestForm" method="POST" action="request_drive_processor.php" class="mt-6">

            <input type="hidden" id="pick_up_latitude" name="pick_up_latitude" required>
            <input type="hidden" id="pick_up_longitude" name="pick_up_longitude" required>
            <input type="hidden" id="destination_latitude" name="destination_latitude" required>
            <input type="hidden" id="destination_longitude" name="destination_longitude" required>
            <input type="hidden" id="estimated_cost_input" name="estimated_cost" required>

            <!-- Service Type -->
            <div class="mb-6">
                <label class="font-semibold block mb-2 text-gray-700">
                    <svg class="inline w-5 h-5 text-indigo-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Service Type *
                </label>
                <select name="service_type" id="serviceType" required class="w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                    <option value="">-- Select Service Type --</option>
                    <option value="Simple Drive">🚗 Simple Drive - Standard carpooling</option>
                    <option value="Luxury">✨ Luxury - Premium comfort</option>
                    <option value="Small Cargo">📦 Small Cargo - Light packages</option>
                    <option value="Large Cargo">🚚 Large Cargo - Heavy items</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Choose the service that best fits your needs</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="submitBtn"
                class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white p-4 rounded-xl text-lg font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="inline w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Submit Ride Request
            </button>

            <p class="text-sm text-gray-500 mt-4 text-center md:hidden">
                Requesting as <strong><?= htmlspecialchars($user_username) ?></strong>
            </p>
        </form>
    </div>
</div>

<script>
const cyprusBounds = L.latLngBounds([34.50, 32.00], [35.80, 34.90]);

var map = L.map('map', {
    maxBounds: cyprusBounds,
    maxBoundsViscosity: 1.0,
}).setView([35.1856, 33.3823], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

let pickupMarker = null;
let destMarker = null;

const greenIcon = new L.Icon({
    iconUrl: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
    iconSize: [40, 40]
});

const redIcon = new L.Icon({
    iconUrl: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
    iconSize: [40, 40]
});

let mainRoute = null;
let altRoutes = [];

function calculateEstimatedCost(distanceKm) {
    const baseFare = 5;
    const perKm = 0.60;
    return (baseFare + distanceKm * perKm).toFixed(2);
}

function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-lg z-50 max-w-md';
    errorDiv.innerHTML = `
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-red-800">Error</h3>
                <p class="text-sm text-red-700">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(errorDiv);
    setTimeout(() => errorDiv.remove(), 5000);
}

function drawRoutes() {
    if (!pickupMarker || !destMarker) return;

    let start = pickupMarker.getLatLng();
    let end = destMarker.getLatLng();

    // Show calculating state
    const routeInfo = document.getElementById("routeInfo");
    routeInfo.classList.remove("hidden");
    routeInfo.classList.add("route-calculating");
    document.getElementById("distance_km").innerText = "Calculating...";
    document.getElementById("estimated_cost").innerText = "...";

    let url =
        `https://routing.openstreetmap.de/routed-car/route/v1/driving/` +
        `${start.lng},${start.lat};${end.lng},${end.lat}` +
        `?overview=full&geometries=geojson&alternatives=true`;

    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('Routing service unavailable');
            return res.json();
        })
        .then(data => {
            if (!data.routes || data.routes.length === 0) {
                throw new Error('No route found between these locations');
            }

            // Remove calculating state
            routeInfo.classList.remove("route-calculating");

            if (mainRoute) map.removeLayer(mainRoute);
            altRoutes.forEach(r => map.removeLayer(r));
            altRoutes = [];

            let coords = data.routes[0].geometry.coordinates.map(c => [c[1], c[0]]);
            mainRoute = L.polyline(coords, { color: "blue", weight: 5 }).addTo(map);

            const altColors = ["orange", "purple", "green"];
            for (let i = 1; i < data.routes.length; i++) {
                let alt = L.polyline(
                    data.routes[i].geometry.coordinates.map(c => [c[1], c[0]]),
                    { color: altColors[(i - 1) % altColors.length], dashArray: "8,6", weight: 4 }
                ).addTo(map);
                altRoutes.push(alt);
            }

            map.fitBounds(mainRoute.getBounds(), { padding: [25, 25] });

            let distKm = (data.routes[0].distance / 1000).toFixed(2);
            let est = calculateEstimatedCost(distKm);
            document.getElementById("distance_km").innerText = distKm;
            document.getElementById("estimated_cost").innerText = est;
            document.getElementById("estimated_cost_input").value = est;
        })
        .catch(err => {
            routeInfo.classList.add("hidden");
            showError(err.message || 'Failed to calculate route. Please try again.');
        });
}

function makeSearchHandler(inputEl, resultsEl, markerType) {
    let keepOpen = false;
    let debounceTimer = null;

    resultsEl.addEventListener("mouseenter", () => keepOpen = true);
    resultsEl.addEventListener("mouseleave", () => keepOpen = false);

    inputEl.addEventListener("input", function () {
        clearTimeout(debounceTimer);

        let query = this.value.trim();
        if (query.length < 2) {
            resultsEl.style.display = "none";
            resultsEl.innerHTML = "";
            return;
        }

        debounceTimer = setTimeout(() => {

            fetch("nominatim_proxy.php?q=" + encodeURIComponent(query))
                .then(res => {
                    if (!res.ok) throw new Error('Search service unavailable');
                    return res.json();
                })
                .then(data => {

                    resultsEl.innerHTML = "";

                    if (!data || data.length === 0) {
                        resultsEl.innerHTML = "<div class='p-3 text-gray-500'>No locations found. Try a different search term.</div>";
                        resultsEl.style.display = "block";
                        return;
                    }

                    data.forEach(loc => {
                        let div = document.createElement("div");
                        div.textContent = loc.display_name;

                        div.addEventListener("click", () => {
                            inputEl.value = loc.display_name;
                            resultsEl.style.display = "none";

                            let lat = parseFloat(loc.lat);
                            let lon = parseFloat(loc.lon);

                            map.setView([lat, lon], 14);

                            if (markerType === "pickup") {
                                if (pickupMarker) map.removeLayer(pickupMarker);
                                pickupMarker = L.marker([lat, lon], { draggable: true, icon: greenIcon }).addTo(map);
                                document.getElementById("pick_up_latitude").value = lat;
                                document.getElementById("pick_up_longitude").value = lon;
                                pickupMarker.on("dragend", function() {
                                    const pos = pickupMarker.getLatLng();
                                    document.getElementById("pick_up_latitude").value = pos.lat;
                                    document.getElementById("pick_up_longitude").value = pos.lng;
                                    drawRoutes();
                                });
                            } else {
                                if (destMarker) map.removeLayer(destMarker);
                                destMarker = L.marker([lat, lon], { draggable: true, icon: redIcon }).addTo(map);
                                document.getElementById("destination_latitude").value = lat;
                                document.getElementById("destination_longitude").value = lon;
                                destMarker.on("dragend", function() {
                                    const pos = destMarker.getLatLng();
                                    document.getElementById("destination_latitude").value = pos.lat;
                                    document.getElementById("destination_longitude").value = pos.lng;
                                    drawRoutes();
                                });
                            }

                            drawRoutes();
                        });

                        resultsEl.appendChild(div);
                    });

                    resultsEl.style.display = "block";
                })
                .catch(err => {
                    resultsEl.innerHTML = "<div class='p-3 text-red-500'>Search failed. Please try again.</div>";
                    resultsEl.style.display = "block";
                });

        }, 300);
    });

    inputEl.addEventListener("blur", () => {
        setTimeout(() => {
            if (!keepOpen) resultsEl.style.display = "none";
        }, 150);
    });
}

makeSearchHandler(document.getElementById("pickupSearch"), document.getElementById("pickupResults"), "pickup");
makeSearchHandler(document.getElementById("destSearch"), document.getElementById("destResults"), "dest");

document.addEventListener("click", function (e) {
    if (!e.target.closest(".search-box")) {
        document.getElementById("pickupResults").style.display = "none";
        document.getElementById("destResults").style.display = "none";
    }
});

document.getElementById("driveRequestForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    // Validation
    if (!pickupMarker || !destMarker) {
        showError('Please select both pickup and destination locations.');
        return;
    }

    const serviceType = document.getElementById("serviceType").value;
    if (!serviceType) {
        showError('Please select a service type.');
        return;
    }

    // Show loading overlay
    const loadingOverlay = document.getElementById("loadingOverlay");
    const submitBtn = document.getElementById("submitBtn");
    
    loadingOverlay.classList.add("active");
    submitBtn.disabled = true;

    let form = e.target;
    let data = new FormData(form);

    try {
        let response = await fetch(form.action, {
            method: "POST",
            body: data
        });

        if (!response.ok) {
            throw new Error('Server error. Please try again.');
        }

        let result = await response.json();

        if (result.status === "success") {
            // Success! Redirect to route selection
            window.location.href = "choose_route_option.php?request=" + result.request_number;
            return;
        }

        throw new Error(result.message || 'An error occurred. Please try again.');

    } catch (err) {
        loadingOverlay.classList.remove("active");
        submitBtn.disabled = false;
        showError(err.message);
    }
});
</script>

</body>
</html>
