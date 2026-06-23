<?php
session_start();

// ============================================
// SECURITY: SESSION CHECK (FROM OLD VERSION)
// ============================================
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$user_username = htmlspecialchars($_SESSION['username']);

// ============================================
// CSRF PROTECTION
// ============================================
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Κράτηση Διαδρομής - Book a Ride</title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Leaflet Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Custom CSS -->
<link rel="stylesheet" href="book_ride.css">

</head>
<body class="bg-gray-100 min-h-screen p-4 md:p-6">

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">🚗 Κράτηση Διαδρομής</h1>
                <p class="text-gray-600 mt-1">Book a Ride - Choose your route</p>
            </div>
            <button id="resetBtn" 
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors hidden"
                title="Επαναφορά">
                🔄 Reset
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        
        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Search Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            
            <!-- Pickup Search -->
            <div class="search-box relative">
                <label class="font-semibold text-gray-700 mb-2 block">
                    📍 Σημείο Αναχώρησης / Pickup
                </label>
                <div class="relative">
                    <input 
                        id="pickupSearch"
                        type="text"
                        placeholder="Αναζήτηση ή συντεταγμένες..."
                        class="w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg 
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                               transition-all outline-none"
                        autocomplete="off">
                    <span class="absolute left-3 top-3.5 text-gray-400 text-lg">🔍</span>
                    <div id="pickupLoading" class="absolute right-3 top-3.5 hidden">
                        <div class="spinner"></div>
                    </div>
                </div>
                <button 
                    id="useLocationBtn" 
                    type="button"
                    class="mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1">
                    📍 Χρήση τρέχουσας τοποθεσίας
                </button>
                <div id="pickupResults" class="results-box"></div>
            </div>

            <!-- Destination Search -->
            <div class="search-box relative">
                <label class="font-semibold text-gray-700 mb-2 block">
                    🎯 Προορισμός / Destination
                </label>
                <div class="relative">
                    <input 
                        id="destSearch"
                        type="text"
                        placeholder="Αναζήτηση ή συντεταγμένες..."
                        class="w-full pl-10 pr-10 py-3 border-2 border-gray-300 rounded-lg 
                               focus:border-green-500 focus:ring-2 focus:ring-green-200 
                               transition-all outline-none"
                        autocomplete="off">
                    <span class="absolute left-3 top-3.5 text-gray-400 text-lg">🔍</span>
                    <div id="destLoading" class="absolute right-3 top-3.5 hidden">
                        <div class="spinner"></div>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    💡 Δέχεται: Διεύθυνση, 35.1856,33.3823, 35°11'08"N 33°22'56"E
                </p>
                <div id="destResults" class="results-box"></div>
            </div>

        </div>

        <!-- Selected Locations Display -->
        <div id="selectedInfo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="space-y-2">
                <div class="flex items-start gap-2">
                    <span class="text-green-600 font-bold mt-0.5">✓</span>
                    <div class="flex-1">
                        <p class="text-xs text-gray-600 font-semibold">Αναχώρηση:</p>
                        <p id="pickupDisplay" class="text-sm text-gray-800"></p>
                    </div>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-green-600 font-bold mt-0.5">✓</span>
                    <div class="flex-1">
                        <p class="text-xs text-gray-600 font-semibold">Προορισμός:</p>
                        <p id="destDisplay" class="text-sm text-gray-800"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div id="map" class="mb-4"></div>

        <!-- Route Info -->
        <div id="routeInfo" class="hidden bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-4 mb-4">
            <div class="flex flex-wrap gap-4 justify-around text-center">
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Απόσταση</p>
                    <p class="text-2xl font-bold text-blue-700">
                        <span id="distance_km">0</span> km
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Διάρκεια</p>
                    <p class="text-2xl font-bold text-purple-700">
                        ~<span id="estimated_time">0</span> min
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 font-semibold">Κόστος</p>
                    <p class="text-2xl font-bold text-green-700">
                        €<span id="estimated_cost">0.00</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Booking Form -->
        <form id="driveRequestForm" method="POST" action="request_drive_processor.php">
            
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <!-- Hidden Fields -->
            <input type="hidden" id="pick_up_latitude" name="pick_up_latitude" required>
            <input type="hidden" id="pick_up_longitude" name="pick_up_longitude" required>
            <input type="hidden" id="pick_up_address" name="pick_up_address">
            <input type="hidden" id="destination_latitude" name="destination_latitude" required>
            <input type="hidden" id="destination_longitude" name="destination_longitude" required>
            <input type="hidden" id="destination_address" name="destination_address">
            <input type="hidden" id="distance" name="distance">
            <input type="hidden" id="estimated_cost_hidden" name="estimated_cost">

            <!-- Service Type -->
            <div class="mb-4">
                <label class="font-semibold text-gray-700 mb-2 block">
                    🚙 Τύπος Υπηρεσίας / Service Type
                </label>
                <select 
                    id="serviceType"
                    name="service_type" 
                    required 
                    class="w-full p-3 border-2 border-gray-300 rounded-lg 
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                           transition-all outline-none">
                    <option value="">-- Επιλέξτε Τύπο --</option>
                    <option value="simple">🚗 Simple - €0.80/km (Base: €3.00)</option>
                    <option value="luxury">💎 Luxury - €1.50/km (Base: €5.00)</option>
                    <option value="small-cargo">📦 Small Cargo - €1.00/km (Base: €4.00)</option>
                    <option value="large-cargo">🚚 Large Cargo - €1.80/km (Base: €6.00)</option>
                </select>
            </div>

            <!-- Comments -->
            <div class="mb-4">
                <label class="font-semibold text-gray-700 mb-2 block">
                    💬 Σχόλια / Comments (προαιρετικό)
                </label>
                <textarea 
                    id="comments"
                    name="comments"
                    rows="3"
                    placeholder="Προσθέστε οποιαδήποτε ειδική οδηγία..."
                    class="w-full p-3 border-2 border-gray-300 rounded-lg 
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                           transition-all outline-none resize-none"></textarea>
            </div>

            <!-- Submit Button -->
            <button 
                id="submitBtn"
                type="submit"
                disabled
                class="w-full py-4 rounded-lg text-lg font-bold transition-all
                       disabled:bg-gray-300 disabled:text-gray-500 disabled:cursor-not-allowed
                       enabled:bg-blue-600 enabled:hover:bg-blue-700 enabled:text-white 
                       enabled:shadow-lg enabled:hover:shadow-xl">
                <span id="submitText">📝 Υποβολή Αιτήματος</span>
                <span id="submitLoading" class="hidden">⏳ Υποβολή...</span>
            </button>

            <!-- User Info -->
            <p class="text-center text-sm text-gray-500 mt-3">
                Αίτηση ως: <strong class="text-gray-700"><?= $user_username ?></strong>
            </p>

        </form>

    </div>
</div>

<!-- Custom JavaScript -->
<script src="book_ride.js"></script>

</body>
</html>
