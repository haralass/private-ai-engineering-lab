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
    <title>Reports & Analytics - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        /* Pulse animation for new data */
        .stat-card.updated {
            animation: pulse 0.6s ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1) translateY(0); }
            50% { transform: scale(1.05) translateY(-2px); }
        }

        .filter-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .report-tab {
            transition: all 0.2s ease;
        }
        .report-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .report-tab:not(.active):hover {
            background: #F3F4F6;
        }
        
        .trend-up {
            color: #10B981;
        }
        .trend-down {
            color: #EF4444;
        }
        
        .export-btn {
            transition: all 0.2s ease;
        }
        .export-btn:hover {
            transform: scale(1.05);
        }

        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
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
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Skeleton Loading */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 4px;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Fade in animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Dark Mode */
        body.dark-mode {
            background: #1a202c;
            color: #e2e8f0;
        }

        body.dark-mode .bg-white {
            background: #2d3748;
            color: #e2e8f0;
        }

        body.dark-mode .text-gray-900 {
            color: #e2e8f0;
        }

        body.dark-mode .text-gray-600 {
            color: #a0aec0;
        }

        body.dark-mode .border-gray-200 {
            border-color: #4a5568;
        }

        body.dark-mode .bg-gray-50 {
            background: #374151;
        }

        /* Comparison Mode */
        .comparison-card {
            position: relative;
            overflow: hidden;
        }

        .comparison-card::after {
            content: 'VS';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 3rem;
            font-weight: bold;
            color: rgba(0,0,0,0.05);
            pointer-events: none;
        }

        /* Search highlight */
        .highlight {
            background: #fef08a;
            padding: 2px 4px;
            border-radius: 2px;
        }

        /* Tooltip */
        .tooltip {
            position: relative;
        }

        .tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            white-space: nowrap;
            font-size: 0.875rem;
            z-index: 1000;
            margin-bottom: 8px;
        }

        .tooltip:hover::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 6px solid transparent;
            border-top-color: #1f2937;
            margin-bottom: 2px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="text-center">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-white text-lg font-semibold">Generating Report...</p>
        </div>
    </div>

    <!-- Header -->
    <div class="bg-white shadow-sm border-b mb-6">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Analytics Icon -->
                    <svg class="h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
                        <p class="text-sm text-gray-600">Admin Dashboard - Business Intelligence</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Dark Mode Toggle -->
                    <button onclick="toggleDarkMode()" class="tooltip p-2 rounded-lg hover:bg-gray-100 transition" data-tooltip="Toggle Dark Mode">
                        <svg id="darkModeIcon" class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>
                    <!-- Auto Refresh Toggle -->
                    <button onclick="toggleAutoRefresh()" id="autoRefreshBtn" class="tooltip p-2 rounded-lg hover:bg-gray-100 transition" data-tooltip="Auto Refresh (Off)">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Logged in as</p>
                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($admin_username) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-8">

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Rides -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Rides</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">1,234</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +12.5% from last month
                        </p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">€45,678</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +8.3% from last month
                        </p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Drivers -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Drivers</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">89</p>
                        <p class="text-sm text-red-600 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            -2.1% from last month
                        </p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="stat-card bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Avg Rating</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">4.8/5.0</p>
                        <p class="text-sm text-green-600 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +0.3 from last month
                        </p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filter-section rounded-xl shadow-xl p-6 mb-8 text-white">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Report Filters</h2>
                <div class="flex items-center space-x-3">
                    <!-- Comparison Mode Toggle -->
                    <label class="inline-flex items-center cursor-pointer bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition">
                        <input type="checkbox" id="comparisonMode" onchange="toggleComparisonMode()" class="mr-2">
                        <span class="text-sm font-semibold">📊 Compare Periods</span>
                    </label>
                    <button onclick="resetFilters()" class="px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg transition">
                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Filters
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Time Period Filter -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Time Period</label>
                    <select id="timePeriod" class="w-full px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_week">This Week</option>
                        <option value="last_week">Last Week</option>
                        <option value="this_month" selected>This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_quarter">This Quarter</option>
                        <option value="last_quarter">Last Quarter</option>
                        <option value="this_year">This Year</option>
                        <option value="last_year">Last Year</option>
                        <option value="custom">Custom Range...</option>
                    </select>
                </div>

                <!-- Service Type Filter -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Service Type</label>
                    <select id="serviceType" class="w-full px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white">
                        <option value="all">All Services</option>
                        <option value="simple">Simple Drive</option>
                        <option value="luxury">Luxury</option>
                        <option value="small_cargo">Small Cargo</option>
                        <option value="large_cargo">Large Cargo</option>
                    </select>
                </div>

                <!-- Location Filter -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Location</label>
                    <select id="location" class="w-full px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white">
                        <option value="all">All Locations</option>
                        <option value="nicosia">Nicosia</option>
                        <option value="limassol">Limassol</option>
                        <option value="larnaca">Larnaca</option>
                        <option value="paphos">Paphos</option>
                        <option value="famagusta">Famagusta</option>
                    </select>
                </div>
            </div>

            <!-- Custom Date Range (Hidden by default) -->
            <div id="customDateRange" class="mt-6 grid grid-cols-2 gap-4" style="display: none;">
                <div>
                    <label class="block text-sm font-semibold mb-2">From Date</label>
                    <input type="date" id="fromDate" class="w-full px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">To Date</label>
                    <input type="date" id="toDate" class="w-full px-4 py-3 rounded-lg text-gray-900 focus:ring-2 focus:ring-white">
                </div>
            </div>

            <div class="mt-6">
                <button onclick="generateReport()" class="w-full px-6 py-4 bg-white text-purple-700 rounded-lg font-bold text-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    <svg class="inline w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Generate Report
                </button>
            </div>
        </div>

        <!-- Report Type Tabs -->
        <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden">
            <div class="border-b border-gray-200">
                <div class="flex overflow-x-auto">
                    <button class="report-tab active px-6 py-4 font-semibold whitespace-nowrap" onclick="switchTab('statistics')">
                        📊 Trip Statistics
                    </button>
                    <button class="report-tab px-6 py-4 font-semibold text-gray-600 whitespace-nowrap" onclick="switchTab('costs')">
                        💰 Cost Analysis
                    </button>
                    <button class="report-tab px-6 py-4 font-semibold text-gray-600 whitespace-nowrap" onclick="switchTab('performance')">
                        🏆 Driver Performance
                    </button>
                    <button class="report-tab px-6 py-4 font-semibold text-gray-600 whitespace-nowrap" onclick="switchTab('revenue')">
                        💵 Revenue Report
                    </button>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-end space-x-3">
                <button onclick="exportToPDF()" class="export-btn px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Export PDF
                </button>
                <button onclick="exportToExcel()" class="export-btn px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </button>
                <button onclick="window.print()" class="export-btn px-4 py-2 bg-gray-600 text-white rounded-lg font-medium hover:bg-gray-700 transition">
                    <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
            </div>

            <!-- Report Content -->
            <div id="reportContent" class="p-6">
                
                <!-- Statistics Tab -->
                <div id="tab-statistics" class="tab-content fade-in">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Trip Statistics Analysis</h3>
                    
                    <!-- Chart -->
                    <div class="bg-white rounded-lg p-6 mb-6">
                        <canvas id="statsChart" height="80"></canvas>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                id="statsTableSearch" 
                                placeholder="🔍 Search by service type..." 
                                oninput="searchTable('statsTable', this.value)"
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="overflow-x-auto">
                        <table id="statsTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Trips</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trend</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Simple Drive
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">745</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">60.4%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="trend-up font-semibold">↑ 8.2%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Luxury
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">289</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">23.4%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="trend-up font-semibold">↑ 12.5%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Small Cargo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">12.6%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="trend-down font-semibold">↓ 3.1%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Large Cargo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">44</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">3.6%</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="trend-up font-semibold">↑ 5.7%</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Costs Tab -->
                <div id="tab-costs" class="tab-content" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Cost Analysis Report</h3>
                    
                    <!-- Chart -->
                    <div class="bg-white rounded-lg p-6 mb-6">
                        <canvas id="costsChart" height="80"></canvas>
                    </div>

                    <!-- Data Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Cost</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Simple Drive
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">€12.45</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€5.00</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€45.80</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Luxury
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">€28.90</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€15.00</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€125.00</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Small Cargo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">€18.75</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€10.00</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€65.00</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Large Cargo
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">€42.30</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€25.00</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€180.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Tab -->
                <div id="tab-performance" class="tab-content fade-in" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Driver Performance Report</h3>
                    
                    <!-- Top Performers -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl p-6 text-white">
                            <div class="text-6xl mb-2">🥇</div>
                            <h4 class="text-lg font-semibold">Top Driver</h4>
                            <p class="text-2xl font-bold mt-2">John Doe</p>
                            <p class="text-sm opacity-90">156 trips | 4.9★</p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-300 to-gray-500 rounded-xl p-6 text-white">
                            <div class="text-6xl mb-2">🥈</div>
                            <h4 class="text-lg font-semibold">2nd Place</h4>
                            <p class="text-2xl font-bold mt-2">Maria Smith</p>
                            <p class="text-sm opacity-90">142 trips | 4.8★</p>
                        </div>
                        <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl p-6 text-white">
                            <div class="text-6xl mb-2">🥉</div>
                            <h4 class="text-lg font-semibold">3rd Place</h4>
                            <p class="text-2xl font-bold mt-2">Alex Johnson</p>
                            <p class="text-sm opacity-90">138 trips | 4.8★</p>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                id="performanceTableSearch" 
                                placeholder="🔍 Search drivers by name..." 
                                oninput="searchTable('performanceTable', this.value)"
                                class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Full Rankings -->
                    <div class="overflow-x-auto">
                        <table id="performanceTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Driver</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed Trips</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="bg-yellow-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">John Doe</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-yellow-500">★★★★★</span> 4.9
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">€8,945</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">2</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Maria Smith</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">142</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-yellow-500">★★★★★</span> 4.8
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">€8,234</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">3</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Alex Johnson</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">138</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-yellow-500">★★★★★</span> 4.8
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">€7,892</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Revenue Tab -->
                <div id="tab-revenue" class="tab-content" style="display: none;">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Revenue Report</h3>
                    
                    <!-- Monthly Revenue Chart -->
                    <div class="bg-white rounded-lg p-6 mb-6">
                        <h4 class="text-lg font-semibold mb-4">Monthly Revenue (Current Year)</h4>
                        <canvas id="revenueChart" height="80"></canvas>
                    </div>

                    <!-- Yearly Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                            <h4 class="text-sm font-medium text-gray-600">2025 (Current)</h4>
                            <p class="text-3xl font-bold text-gray-900 mt-2">€45,678</p>
                            <p class="text-sm text-gray-500 mt-1">1,234 trips</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-gray-400">
                            <h4 class="text-sm font-medium text-gray-600">2024</h4>
                            <p class="text-3xl font-bold text-gray-900 mt-2">€523,456</p>
                            <p class="text-sm text-gray-500 mt-1">14,892 trips</p>
                        </div>
                        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-gray-300">
                            <h4 class="text-sm font-medium text-gray-600">2023</h4>
                            <p class="text-3xl font-bold text-gray-900 mt-2">€487,234</p>
                            <p class="text-sm text-gray-500 mt-1">13,567 trips</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        // Initialize Charts
        let statsChart, costsChart, revenueChart;
        let autoRefreshEnabled = false;
        let autoRefreshInterval = null;
        let comparisonModeEnabled = false;

        function initCharts() {
            // Statistics Chart
            const statsCtx = document.getElementById('statsChart').getContext('2d');
            statsChart = new Chart(statsCtx, {
                type: 'bar',
                data: {
                    labels: ['Simple Drive', 'Luxury', 'Small Cargo', 'Large Cargo'],
                    datasets: [{
                        label: 'Number of Trips',
                        data: [745, 289, 156, 44],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(249, 115, 22, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(168, 85, 247)',
                            'rgb(34, 197, 94)',
                            'rgb(249, 115, 22)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed.y / total) * 100).toFixed(1);
                                    label += ` (${percentage}%)`;
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const service = ['Simple Drive', 'Luxury', 'Small Cargo', 'Large Cargo'][index];
                            alert(`Drilling down to ${service} details...`);
                            // TODO: Implement drill-down functionality
                        }
                    }
                }
            });

            // Costs Chart
            const costsCtx = document.getElementById('costsChart').getContext('2d');
            costsChart = new Chart(costsCtx, {
                type: 'line',
                data: {
                    labels: ['Simple Drive', 'Luxury', 'Small Cargo', 'Large Cargo'],
                    datasets: [{
                        label: 'Average Cost (€)',
                        data: [12.45, 28.90, 18.75, 42.30],
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Avg Cost: €' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '€' + value;
                                }
                            }
                        }
                    }
                }
            });

            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: '2025 Revenue (€)',
                        data: [38500, 42300, 45678, null, null, null, null, null, null, null, null, null],
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }, {
                        label: '2024 Revenue (€)',
                        data: [35200, 38900, 41200, 43500, 45800, 48200, 50100, 52300, 48900, 46700, 44500, 42800],
                        backgroundColor: 'rgba(156, 163, 175, 0.2)',
                        borderColor: 'rgb(156, 163, 175)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': €' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '€' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize on page load
        window.addEventListener('load', initCharts);

        // Dark Mode Toggle
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const icon = document.getElementById('darkModeIcon');
            if (document.body.classList.contains('dark-mode')) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>';
                localStorage.setItem('darkMode', 'enabled');
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            document.getElementById('darkModeIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>';
        }

        // Auto Refresh Toggle
        function toggleAutoRefresh() {
            autoRefreshEnabled = !autoRefreshEnabled;
            const btn = document.getElementById('autoRefreshBtn');
            
            if (autoRefreshEnabled) {
                btn.classList.add('bg-green-100');
                btn.setAttribute('data-tooltip', 'Auto Refresh (On - 30s)');
                autoRefreshInterval = setInterval(() => {
                    generateReport();
                    showNotification('Report refreshed automatically');
                }, 30000); // 30 seconds
            } else {
                btn.classList.remove('bg-green-100');
                btn.setAttribute('data-tooltip', 'Auto Refresh (Off)');
                if (autoRefreshInterval) {
                    clearInterval(autoRefreshInterval);
                }
            }
        }

        // Comparison Mode Toggle
        function toggleComparisonMode() {
            comparisonModeEnabled = !comparisonModeEnabled;
            if (comparisonModeEnabled) {
                showNotification('Comparison mode enabled! Select a second period to compare.');
                // TODO: Show comparison date pickers
            } else {
                showNotification('Comparison mode disabled');
            }
        }

        // Table Search Function
        function searchTable(tableId, searchValue) {
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                let found = false;
                const cells = rows[i].getElementsByTagName('td');
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;
                    
                    if (cellText.toLowerCase().indexOf(searchValue.toLowerCase()) > -1) {
                        found = true;
                        // Highlight matching text
                        if (searchValue) {
                            const regex = new RegExp(`(${searchValue})`, 'gi');
                            cells[j].innerHTML = cells[j].textContent.replace(regex, '<span class="highlight">$1</span>');
                        }
                    }
                }
                
                rows[i].style.display = found ? '' : 'none';
            }
        }

        // Show Notification
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in';
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Tab switching
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });

            // Remove active class from all buttons
            document.querySelectorAll('.report-tab').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('text-gray-600');
            });

            // Show selected tab with animation
            const selectedTab = document.getElementById('tab-' + tabName);
            selectedTab.style.display = 'block';
            selectedTab.classList.add('fade-in');

            // Add active class to clicked button
            event.target.classList.add('active');
            event.target.classList.remove('text-gray-600');
        }

        // Show/hide custom date range
        document.getElementById('timePeriod').addEventListener('change', function() {
            const customRange = document.getElementById('customDateRange');
            if (this.value === 'custom') {
                customRange.style.display = 'grid';
            } else {
                customRange.style.display = 'none';
            }
        });

        // Reset filters
        function resetFilters() {
            document.getElementById('timePeriod').value = 'this_month';
            document.getElementById('serviceType').value = 'all';
            document.getElementById('location').value = 'all';
            document.getElementById('customDateRange').style.display = 'none';
            document.getElementById('fromDate').value = '';
            document.getElementById('toDate').value = '';
            document.getElementById('comparisonMode').checked = false;
            comparisonModeEnabled = false;
            showNotification('Filters reset');
        }

        // Generate report with loading
        function generateReport() {
            const timePeriod = document.getElementById('timePeriod').value;
            const serviceType = document.getElementById('serviceType').value;
            const location = document.getElementById('location').value;

            // Show loading overlay
            document.getElementById('loadingOverlay').classList.add('active');

            // Simulate API call
            setTimeout(() => {
                // TODO: Make AJAX call to fetch data from database
                console.log('Generating report with filters:', {
                    timePeriod,
                    serviceType,
                    location,
                    comparisonMode: comparisonModeEnabled
                });

                // Hide loading overlay
                document.getElementById('loadingOverlay').classList.remove('active');

                // Update stat cards with animation
                document.querySelectorAll('.stat-card').forEach(card => {
                    card.classList.add('updated');
                    setTimeout(() => card.classList.remove('updated'), 600);
                });

                // Show success message
                showNotification('Report generated successfully!');
            }, 1500);
        }

        // Export functions
        function exportToPDF() {
            showNotification('Generating PDF export...');
            setTimeout(() => {
                alert('PDF Export ready! (TODO: Implement actual PDF generation with jsPDF)');
            }, 1000);
        }

        function exportToExcel() {
            showNotification('Generating Excel export...');
            setTimeout(() => {
                alert('Excel Export ready! (TODO: Implement actual Excel generation with SheetJS)');
            }, 1000);
        }
    </script>

</body>
</html>
