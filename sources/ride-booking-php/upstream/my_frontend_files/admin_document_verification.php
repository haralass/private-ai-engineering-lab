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
    <title>Document Verification - Admin Panel</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-white shadow-sm border-b mb-6">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Admin Icon -->
                    <svg class="h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Document Verification</h1>
                        <p class="text-sm text-gray-600">Admin Panel - Review Driver Documents</p>
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
                    <p class="text-sm text-blue-700">Select a driver from the list to review their pending documents. Mark each document as valid or invalid, add review notes, and submit your verification.</p>
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
                            Pending Drivers
                        </h2>
                        <p class="text-xs text-indigo-100 mt-1">Click to review documents</p>
                    </div>

                    <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                        <!-- Driver Card 1 - Example -->
                        <div class="driver-card p-4 cursor-pointer border-l-4 border-transparent" onclick="selectDriver(1, 'John Doe')">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">John Doe</h3>
                                    <p class="text-xs text-gray-500">johndoe@example.com</p>
                                    <p class="text-xs text-gray-400 mt-1">Reg: 2024-11-20</p>
                                </div>
                                <div>
                                    <span class="badge-pending px-2 py-1 rounded-full text-xs font-semibold">
                                        5 Pending
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Card 2 - Example -->
                        <div class="driver-card p-4 cursor-pointer border-l-4 border-transparent" onclick="selectDriver(2, 'Maria Smith')">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">Maria Smith</h3>
                                    <p class="text-xs text-gray-500">maria.smith@example.com</p>
                                    <p class="text-xs text-gray-400 mt-1">Reg: 2024-11-19</p>
                                </div>
                                <div>
                                    <span class="badge-pending px-2 py-1 rounded-full text-xs font-semibold">
                                        8 Pending
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Card 3 - Example -->
                        <div class="driver-card p-4 cursor-pointer border-l-4 border-transparent" onclick="selectDriver(3, 'Alex Johnson')">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">Alex Johnson</h3>
                                    <p class="text-xs text-gray-500">alex.j@example.com</p>
                                    <p class="text-xs text-gray-400 mt-1">Reg: 2024-11-18</p>
                                </div>
                                <div>
                                    <span class="badge-pending px-2 py-1 rounded-full text-xs font-semibold">
                                        3 Pending
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Add more driver cards here dynamically from database -->
                    </div>
                </div>
            </div>

            <!-- Right Panel: Document Review -->
            <div class="lg:col-span-2">
                <div id="noSelection" class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Driver Selected</h3>
                    <p class="text-gray-500">Please select a driver from the list to review their documents</p>
                </div>

                <div id="documentReview" class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="display: none;">
                    
                    <!-- Driver Info Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold" id="selectedDriverName">-</h2>
                                <p class="text-purple-100 text-sm mt-1">Driver ID: <span id="selectedDriverId">-</span></p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold" id="pendingCount">0</div>
                                <div class="text-purple-100 text-sm">Pending Documents</div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Form -->
                    <form id="verificationForm" method="POST" action="process_document_verification.php" class="p-6">
                        <input type="hidden" name="driver_id" id="driver_id_input">
                        
                        <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                            
                            <!-- Document 1: ID/Passport -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                            </svg>
                                            ID / Passport
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: ID-123456 | Uploaded: 2024-11-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=1" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_id_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_id_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Invalid</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Document 2: Driver License -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            Driver License
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: DL-789012 | Uploaded: 2024-11-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=2" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_license_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_license_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Invalid</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Document 3: Police Clearance -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            Police Clearance Certificate
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: PC-345678 | Uploaded: 2024-11-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=3" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_police_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_police_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Invalid</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Document 4: Medical Certificate -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Medical Certificate
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: MC-901234 | Uploaded: 2024-11-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=4" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_medical_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_medical_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Invalid</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Document 5: Mental Health Certificate -->
                            <div class="document-row p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 flex items-center">
                                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            Mental Health Certificate
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">Document #: MH-567890 | Uploaded: 2024-11-20</p>
                                    </div>
                                    <span class="badge-pending px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <a href="view_document.php?doc_id=5" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Document
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-end space-x-3">
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                                            <input type="radio" name="doc_mental_status" value="valid" class="w-4 h-4 text-green-600 focus:ring-green-500">
                                            <span class="ml-2 text-sm font-medium text-gray-700">Valid</span>
                                        </label>
                                        <label class="inline-flex items-center cursor-pointer px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                            <input type="radio" name="doc_mental_status" value="invalid" class="w-4 h-4 text-red-600 focus:ring-red-500">
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
                                placeholder="Add any comments or notes about the verification (e.g., reasons for rejection, issues found, recommendations...)"
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
                                Submit Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function selectDriver(driverId, driverName) {
            // Remove selection from all cards
            document.querySelectorAll('.driver-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selection to clicked card
            event.currentTarget.classList.add('selected');

            // Hide "no selection" message
            document.getElementById('noSelection').style.display = 'none';

            // Show document review panel
            document.getElementById('documentReview').style.display = 'block';

            // Update driver info
            document.getElementById('selectedDriverName').textContent = driverName;
            document.getElementById('selectedDriverId').textContent = driverId;
            document.getElementById('driver_id_input').value = driverId;

            // Update pending count (example)
            document.getElementById('pendingCount').textContent = '5';

            // Scroll to review panel on mobile
            if (window.innerWidth < 1024) {
                document.getElementById('documentReview').scrollIntoView({ behavior: 'smooth' });
            }

            // TODO: Load actual documents from database via AJAX
        }

        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
                document.getElementById('verificationForm').reset();
            }
        }

        // Form validation before submit
        document.getElementById('verificationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Check if at least one document status is selected
            const hasSelection = 
                document.querySelector('input[name="doc_id_status"]:checked') ||
                document.querySelector('input[name="doc_license_status"]:checked') ||
                document.querySelector('input[name="doc_police_status"]:checked') ||
                document.querySelector('input[name="doc_medical_status"]:checked') ||
                document.querySelector('input[name="doc_mental_status"]:checked');

            if (!hasSelection) {
                alert('Please review at least one document before submitting.');
                return false;
            }

            if (confirm('Are you sure you want to submit this verification? This action will update the database.')) {
                // TODO: Submit form via AJAX or allow normal form submission
                this.submit();
            }
        });
    </script>

</body>
</html>
