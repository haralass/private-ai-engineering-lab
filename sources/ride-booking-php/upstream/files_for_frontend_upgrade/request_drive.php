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
<title>Book a Ride</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
    #map { height: 440px; border-radius: 12px; margin-bottom: 20px; }

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
</style>
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow-xl">

    <h2 class="text-2xl font-bold mb-4">Book a Ride 🚕</h2>
    <p class="text-gray-600 mb-4">Choose pickup and destination using search or the map.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 search-container">

        <div class="search-box relative">
            <label class="font-semibold mb-1 block">Pickup Address</label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-400">🔍</span>
                <input id="pickupSearch"
                    type="text"
                    placeholder="Search pickup location..."
                    class="w-full pl-10 p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                    autocomplete="off">
            </div>
            <div id="pickupResults" class="results-box"></div>
        </div>

        <div class="search-box relative">
            <label class="font-semibold mb-1 block">Destination Address</label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-gray-400">📍</span>
                <input id="destSearch"
                    type="text"
                    placeholder="Search destination..."
                    class="w-full pl-10 p-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                    autocomplete="off">
            </div>
            <div id="destResults" class="results-box"></div>
        </div>

    </div>

    <div id="map"></div>

    <div id="routeInfo" class="hidden mt-4 p-3 bg-indigo-50 rounded-lg border border-indigo-200">
        <p class="text-lg font-semibold text-indigo-700">
            📏 Distance: <span id="distance_km">0</span> km
        </p>
        <p class="text-lg font-semibold text-green-700">
            💶 Estimated Cost: €<span id="estimated_cost">0</span>
        </p>
    </div>

    <form id="driveRequestForm" method="POST" action="request_drive_processor.php" class="mt-4">

        <input type="hidden" id="pick_up_latitude" name="pick_up_latitude" required>
        <input type="hidden" id="pick_up_longitude" name="pick_up_longitude" required>
        <input type="hidden" id="destination_latitude" name="destination_latitude" required>
        <input type="hidden" id="destination_longitude" name="destination_longitude" required>
        <input type="hidden" id="estimated_cost_input" name="estimated_cost" required>

        <label class="font-semibold block mb-1">Service Type</label>
        <select name="service_type" required class="w-full p-2 border rounded mb-4">
            <option value="">-- Select Service Type --</option>
            <option value="Simple Drive">Simple Drive</option>
            <option value="luxury">Luxury</option>
            <option value="small-cargo">Small Cargo</option>
            <option value="large-cargo">Large Cargo</option>
        </select>

        <button type="submit"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-lg text-lg shadow">
            Submit Request
        </button>

        <p class="text-sm text-gray-500 mt-3 text-center">
            Requesting as <strong><?= htmlspecialchars($user_username) ?></strong>
        </p>
    </form>
</div>

<script>
const cyprusBounds = L.latLngBounds([34.50, 32.00], [35.80, 34.90]);

var map = L.map('map', {
    maxBounds: cyprusBounds,
    maxBoundsViscosity: 1.0,
}).setView([35.1856, 33.3823], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);

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

function drawRoutes() {
    if (!pickupMarker || !destMarker) return;

    let start = pickupMarker.getLatLng();
    let end = destMarker.getLatLng();

    let url =
        `https://routing.openstreetmap.de/routed-car/route/v1/driving/` +
        `${start.lng},${start.lat};${end.lng},${end.lat}` +
        `?overview=full&geometries=geojson&alternatives=true`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (!data.routes) return;

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
            document.getElementById("routeInfo").classList.remove("hidden");
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
                .then(res => res.json())
                .then(data => {

                    resultsEl.innerHTML = "";

                    if (!data || data.length === 0) {
                        resultsEl.innerHTML = "<div class='p-3 text-gray-500'>No matches</div>";
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
                                pickupMarker.on("dragend", drawRoutes);
                            } else {
                                if (destMarker) map.removeLayer(destMarker);
                                destMarker = L.marker([lat, lon], { draggable: true, icon: redIcon }).addTo(map);
                                document.getElementById("destination_latitude").value = lat;
                                document.getElementById("destination_longitude").value = lon;
                                destMarker.on("dragend", drawRoutes);
                            }

                            drawRoutes();
                        });

                        resultsEl.appendChild(div);
                    });

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

    let form = e.target;
    let data = new FormData(form);

    let response = await fetch(form.action, {
        method: "POST",
        body: data
    });

    let result = await response.json();

    if (result.status === "success") {
        window.location.href = "choose_route_option.php?request=" + result.request_number;
        return;
    }

    alert(result.message || "An error occurred. Please try again.");
});
</script>

</body>
</html>
