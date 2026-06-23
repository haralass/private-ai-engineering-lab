<?php
session_start();

// Authentication check (Administrator only)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if user is administrator
// TODO: Add proper role check from database
// if ($_SESSION['role'] !== 'Administrator') {
//     die("Access Denied: Administrator access required");
// }

$admin_username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Verification - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .driver-card {
            transition: all 0.3s ease;
        }
        .driver-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .driver-card.selected {
            border-color: #4F46E5;
            background: linear-gradient(to right, #EEF2FF, #ffffff);
        }
        .document-row {
            transition: background 0.2s ease;
        }
        .document-row:hover {
            background: #F9FAFB;
        }
        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
        }
        .badge-approved {
            background: #D1FAE5;
            color: #065F46;
        }
        .badge-rejected {
            background: #FEE2E2;
            color: #991B1B;
        }
        .vehicle-spec-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.75rem;
            text-align: center;
        }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.75rem;
        }
        .photo-item {
            aspect-ratio: 1;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px solid #E5E7EB;
            cursor: pointer;
            transition: all 0.2s;
        }
        .photo-item:hover {
            transform: scale(1.05);
            border-color: #4F46E5;
        }
        .photo-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-white shadow-sm border-b mb-6">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Vehicle Icon -->
                    <svg class="h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                    </svg>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Vehicle Verification</h1>
                        <p class="text-sm text-gray-600">Admin Panel - Review Driver Vehicles & Documents</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Logged in as</p>
                    <p class="font-semibold text-gray-900"><?= htmlspecialchars($admin_username) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-8">

        <!-- Info Banner -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg shadow mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-blue-800">Instructions</h3>
                    <p class="text-sm text-blue-700">Select a driver to review their vehicle(s). Check all documents, photos, and specifications. Mark each item as valid or invalid, add review notes, and submit your verification.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Panel: Drivers List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white p-4">
                        <h2 class="text-lg font-bold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Pending Vehicle Reviews
                        </h2>
                        <p class="text-xs text-indigo-100 mt-1">Click to review vehicles</p>
                    </div>

                    <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                        <!-- Driver Card 1 - Example -->
                        <div class="driver-card p-4 cursor-pointer border-l-4 border-transparent" onclick="selectDriver(1, 'John Doe', 2)">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">John Doe</h3>
                                    <p class="text-xs text-gray-500">johndoe@example.com</p>
                                </div>
                                <div>
                                    <span class="badge-pending px-2 py-1 rounded-full text-xs font-semibold">
                                        2 Vehicles
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center text-xs text-gray-600 space-x-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending
                                </span>
                                <span>Reg: 2024-11-20</span>
                            </div>
                        </div>

                        <!-- Driver Card 2 - Example -->
                        <div class="driver-card p-4 cursor-pointer border-l-4 border-transparent" onclick="selectDriver(2, 'Maria Smith', 1)">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">Maria Smith</h3>
                                    <p class="text-xs text-gray-500">maria.smith@example.com</p>
                                </div>
                                <div>
                                    <span class="badge-pending px-2 py-1 rounded-full text-xs font-semibold">
                                        1 Vehicle
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center text-xs text-gray-600 space-x-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending
                                </span>
                                <span>Reg: 2024-11-19</span>
                            </div>
                        </div>

                        <!-- Driver Card 3 - Example -->
                        <div class="driver-card p-4 cursor-pointer border-l-4 border-transparent" onclick="selectDriver(3, 'Alex Johnson', 3)">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">Alex Johnson</h3>
                                    <p class="text-xs text-gray-500">alex.j@example.com</p>
                                </div>
                                <div>
                                    <span class="badge-pending px-2 py-1 rounded-full text-xs font-semibold">
                                        3 Vehicles
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center text-xs text-gray-600 space-x-3">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending
                                </span>
                                <span>Reg: 2024-11-18</span>
                            </div>
                        </div>

                        <!-- Add more driver cards here dynamically from database -->
                    </div>
                </div>
            </div>

            <!-- Right Panel: Vehicle Review -->
            <div class="lg:col-span-2">
                <div id="noSelection" class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Driver Selected</h3>
                    <p class="text-gray-500">Please select a driver from the list to review their vehicle(s)</p>
                </div>

                <div id="vehicleReview" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="display: none;">
                    
                    <!-- Driver Info Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold" id="selectedDriverName">-</h2>
                                <p class="text-purple-100 text-sm mt-1">Driver ID: <span id="selectedDriverId">-</span></p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold" id="vehicleCount">0</div>
                                <div class="text-purple-100 text-sm">Vehicles to Review</div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Selection Tabs -->
                    <div class="border-b border-gray-200 bg-gray-50">
                        <div class="flex overflow-x-auto" id="vehicleTabs">
                            <button class="vehicle-tab px-6 py-3 font-medium text-sm border-b-2 border-indigo-600 text-indigo-600 bg-white whitespace-nowrap" data-vehicle="1">
                                🚗 Vehicle #1
                            </button>
                            <button class="vehicle-tab px-6 py-3 font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-white whitespace-nowrap" data-vehicle="2">
                                🚙 Vehicle #2
                            </button>
                        </div>
                    </div>

                    <!-- Vehicle Form -->
                    <form id="verificationForm" method="POST" action="process_vehicle_verification.php" class="p-6">
                        <input type="hidden" name="driver_id" id="driver_id_input">
                        <input type="hidden" name="vehicle_id" id="vehicle_id_input" value="1">

                        <div class="space-y-6 max-h-[600px] overflow-y-auto pr-2">
                            
                            <!-- Vehicle Specifications Section -->
                            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-xl border-2 border-indigo-200">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    Vehicle Specifications
                                </h3>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="vehicle-spec-box">
                                        <div class="text-2xl font-bold">Sedan</div>
                                        <div class="text-xs opacity-90 mt-1">Vehicle Type</div>
                                    </div>
                                    <div class="vehicle-spec-box">
                                        <div class="text-2xl font-bold">5</div>
                                        <div class="text-xs opacity-90 mt-1">Passenger Seats</div>
                                    </div>
                                    <div class="vehicle-spec-box">
                                        <div class="text-2xl font-bold">450L</div>
                                        <div class="text-xs opacity-90 mt-1">Cargo Volume</div>
                                    </div>
                                    <div class="vehicle-spec-box">
                                        <div class="text-2xl font-bold">250kg</div>
                                        <div class="text-xs opacity-90 mt-1">Max Weight</div>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-white rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        <strong>Make & Model:</strong> Toyota Camry 2022<br>
                                        <strong>License Plate:</strong> CY-ABC-1234<br>
                                        <strong>Color:</strong> Silver
                                    </p>
                                </div>
                            </div>

                            <!-- Vehicle Photos Section -->
                            <div class="bg-white p-6 rounded-xl border-2 border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Vehicle Photos
                                    <span class="ml-auto text-sm font-normal text-gray-500">Click to view full size</span>
                                </h3>

                                <div class="photo-grid">
                                    <div class="photo-item" onclick="viewPhoto('front')">
                                        <img src="https://via.placeholder.com/150?text=Front+View" alt="Front">
                                        <div class="text-xs text-center py-1 bg-gray-100">Front</div>
                                    </div>
                                    <div class="photo-item" onclick="viewPhoto('back')">
                                        <img src="https://via.placeholder.com/150?text=Back+View" alt="Back">
                                        <div class="text-xs text-center py-1 bg-gray-100">Back</div>
                                    </div>
                                    <div class="photo-item" onclick="viewPhoto('side')">
                                        <img src="https://via.placeholder.com/150?text=Side+View" alt="Side">
                                        <div class="text-xs text-center py-1 bg-gray-100">Side</div>
                                    </div>
                                    <div class="photo-item" onclick="viewPhoto('interior')">
                                        <img src="https://via.placeholder.com/150?text=Interior" alt="Interior">
                                        <div class="text-xs text-center py-1 bg-gray-100">Interior</div>
                                    </div>
                                </div>

                                <div class="mt-4 flex items-center justify-end space-x-3">
                                    <label class="inline-flex items-center cursor-pointer px-4 py-2 rounded-lg hover:bg-green-50 transition">
                                        <input type="radio" name="photos_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Photos Valid</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer px-4 py-2 rounded-lg hover:bg-red-50 transition">
                                        <input type="radio" name="photos_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm font-medium text-gray-700">Photos Invalid</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Document 1: Vehicle Registration (Άδεια Κυκλοφορίας) -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Vehicle Registration (Άδεια Κυκλοφορίας)
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: VR-123456 | Uploaded: 2024-11-20 | Expires: 2025-11-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=v1" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_registration_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_registration_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Invalid</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Document 2: MOT Certificate -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            MOT Certificate (Πιστοποιητικό ΜΟΤ)
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: MOT-789012 | Uploaded: 2024-11-20 | Expires: 2025-05-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=v2" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_mot_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_mot_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Invalid</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Document 3: Vehicle Classification Certificate -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                            </svg>
                                            Vehicle Classification (Πιστοποιητικό Ταξινόμησης)
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: VC-345678 | Uploaded: 2024-11-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=v3" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_classification_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_classification_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Invalid</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Review Notes -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <svg class="inline w-5 h-5 text-gray-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Review Notes / Comments
                            </label>
                            <textarea 
                                name="review_notes" 
                                rows="4" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Add any comments about the vehicle verification (e.g., issues found, recommendations, reasons for rejection...)"
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-1">These notes will be visible to other administrators and saved in the verification log</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex items-center justify-between pt-6 border-t border-gray-200">
                            <button 
                                type="button" 
                                onclick="resetForm()"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset Form
                            </button>

                            <button 
                                type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit Vehicle Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function selectDriver(driverId, driverName, vehicleCount) {
            // Remove selection from all cards
            document.querySelectorAll('.driver-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selection to clicked card
            event.currentTarget.classList.add('selected');

            // Hide "no selection" message
            document.getElementById('noSelection').style.display = 'none';

            // Show vehicle review panel
            document.getElementById('vehicleReview').style.display = 'block';

            // Update driver info
            document.getElementById('selectedDriverName').textContent = driverName;
            document.getElementById('selectedDriverId').textContent = driverId;
            document.getElementById('driver_id_input').value = driverId;
            document.getElementById('vehicleCount').textContent = vehicleCount;

            // Scroll to review panel on mobile
            if (window.innerWidth < 1024) {
                document.getElementById('vehicleReview').scrollIntoView({ behavior: 'smooth' });
            }

            // TODO: Load actual vehicles from database via AJAX
        }

        function viewPhoto(photoType) {
            // TODO: Implement full-size photo viewer/lightbox
            alert('View full-size photo: ' + photoType);
        }

        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
                document.getElementById('verificationForm').reset();
            }
        }

        // Vehicle tab switching
        document.querySelectorAll('.vehicle-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab styling
                document.querySelectorAll('.vehicle-tab').forEach(t => {
                    t.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-white');
                    t.classList.add('border-transparent', 'text-gray-500');
                });
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-indigo-600', 'text-indigo-600', 'bg-white');

                // Update hidden input
                const vehicleId = this.getAttribute('data-vehicle');
                document.getElementById('vehicle_id_input').value = vehicleId;

                // TODO: Load vehicle data via AJAX based on vehicleId
            });
        });

        // Form validation before submit
        document.getElementById('verificationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Check if at least photos or one document status is selected
            const hasSelection = 
                document.querySelector('input[name="photos_status"]:checked') ||
                document.querySelector('input[name="doc_registration_status"]:checked') ||
                document.querySelector('input[name="doc_mot_status"]:checked') ||
                document.querySelector('input[name="doc_classification_status"]:checked');

            if (!hasSelection) {
                alert('Please review at least one item (photos or documents) before submitting.');
                return false;
            }

            if (confirm('Are you sure you want to submit this vehicle verification? This action will update the database.')) {
                // TODO: Submit form via AJAX or allow normal form submission
                this.submit();
            }
        });
    </script>

</body>
</html>
