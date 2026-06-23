<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration - UCY Carpooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Step indicators - perfect circles with fallback */
        .step-indicator {
            transition: all 0.3s ease;
            background-color: #d1d5db;
            color: #4b5563;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }
        .step-indicator.active {
            background-color: #4F46E5;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
            transform: scale(1.1);
        }
        .step-indicator.completed {
            background-color: #10B981;
            color: white;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.3s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .file-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        /* Progress bar connector */
        .progress-connector {
            height: 2px;
            background-color: #d1d5db;
            flex: 1;
            margin: 0 0.5rem;
            align-self: center;
        }
        .progress-connector.active {
            background-color: #10B981;
        }
        /* Step container */
        .step-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Carpooling Car Icon -->
                    <svg class="h-20 w-20" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Car body -->
                        <rect x="15" y="45" width="70" height="25" rx="5" fill="#4F46E5"/>
                        <path d="M 20 45 L 30 30 L 70 30 L 80 45 Z" fill="#6366F1"/>
                        
                        <!-- Windows -->
                        <rect x="32" y="35" width="15" height="8" rx="2" fill="#E0E7FF"/>
                        <rect x="53" y="35" width="15" height="8" rx="2" fill="#E0E7FF"/>
                        
                        <!-- Wheels -->
                        <circle cx="30" cy="70" r="8" fill="#1F2937"/>
                        <circle cx="30" cy="70" r="5" fill="#6B7280"/>
                        <circle cx="70" cy="70" r="8" fill="#1F2937"/>
                        <circle cx="70" cy="70" r="5" fill="#6B7280"/>
                        
                        <!-- People icons (carpooling) -->
                        <circle cx="40" cy="50" r="3" fill="#FCD34D"/>
                        <circle cx="50" cy="50" r="3" fill="#FCD34D"/>
                        <circle cx="60" cy="50" r="3" fill="#FCD34D"/>
                        
                        <!-- UCY text below -->
                        <text x="50" y="92" font-family="Arial, sans-serif" font-size="10" font-weight="bold" text-anchor="middle" fill="#4F46E5">UCY Carpooling</text>
                    </svg>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Driver Registration</h1>
                        <p class="text-sm text-gray-600">University of Cyprus - Carpooling System</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-6 py-8">
        
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center max-w-4xl mx-auto">
                <div class="step-container">
                    <div class="step-indicator active" data-step="1">1</div>
                    <p class="text-xs text-gray-600 font-medium mt-2">Personal Info</p>
                </div>
                <div class="progress-connector"></div>
                <div class="step-container">
                    <div class="step-indicator" data-step="2">2</div>
                    <p class="text-xs text-gray-600 font-medium mt-2">ID Details</p>
                </div>
                <div class="progress-connector"></div>
                <div class="step-container">
                    <div class="step-indicator" data-step="3">3</div>
                    <p class="text-xs text-gray-600 font-medium mt-2">Driver License</p>
                </div>
                <div class="progress-connector"></div>
                <div class="step-container">
                    <div class="step-indicator" data-step="4">4</div>
                    <p class="text-xs text-gray-600 font-medium mt-2">Certificates</p>
                </div>
                <div class="progress-connector"></div>
                <div class="step-container">
                    <div class="step-indicator" data-step="5">5</div>
                    <p class="text-xs text-gray-600 font-medium mt-2">Review</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form id="driverRegistrationForm" action="register_driver_processor.php" method="post" enctype="multipart/form-data">
                
                <!-- Step 1: Personal Information -->
                <div class="form-step active" data-step="1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Personal Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">We'll use this to contact you</p>
                        </div>

                        <div>
                            <label for="userName" class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                            <input type="text" id="userName" name="userName" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                            <input type="text" id="address" name="address" maxlength="100" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <p class="text-xs text-red-500 mt-1" id="age-error" style="display:none;">You must be at least 18 years old</p>
                        </div>

                        <div>
                            <label for="pswd" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" id="pswd" name="pswd" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <div class="password-strength bg-gray-200" id="password-strength"></div>
                            <p class="text-xs text-gray-500 mt-1">At least 8 characters with uppercase, lowercase and numbers</p>
                        </div>

                        <div>
                            <label for="pswd2" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" id="pswd2" name="pswd2" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <p class="text-xs text-red-500 mt-1" id="password-match-error" style="display:none;">Passwords do not match</p>
                        </div>
                    </div>
                </div>

                <!-- Step 2: ID Details -->
                <div class="form-step" data-step="2">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">ID Details</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700 mb-1">ID Number *</label>
                            <input type="text" id="id_number" name="id_number" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="id_date_of_publish" class="block text-sm font-medium text-gray-700 mb-1">Date of Issue *</label>
                                <input type="date" id="id_date_of_publish" name="id_date_of_publish" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="id_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                <input type="date" id="id_expiry_date" name="id_expiry_date" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="id_file" class="block text-sm font-medium text-gray-700 mb-1">Upload ID Document *</label>
                            <input type="file" id="id_file" name="id_file" accept=".pdf,.jpg,.jpeg,.png" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-500 mt-1">Max file size: 5MB (PDF, JPG, PNG)</p>
                            <div id="id_file_preview"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Driver License -->
                <div class="form-step" data-step="3">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Driver License Details</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700 mb-1">License Number *</label>
                            <input type="text" id="license_number" name="license_number" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="license_date_of_publish" class="block text-sm font-medium text-gray-700 mb-1">Date of Issue *</label>
                                <input type="date" id="license_date_of_publish" name="license_date_of_publish" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>

                            <div>
                                <label for="license_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                <input type="date" id="license_expiry_date" name="license_expiry_date" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="driver_license_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Driver License *</label>
                            <input type="file" id="driver_license_file" name="driver_license_file" accept=".pdf,.jpg,.jpeg,.png" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-500 mt-1">Max file size: 5MB (PDF, JPG, PNG)</p>
                            <div id="driver_license_file_preview"></div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Certificates -->
                <div class="form-step" data-step="4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Required Certificates</h2>
                    
                    <div class="space-y-6">
                        <!-- Police Clearance -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-3">Police Clearance Certificate</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="police_clearance_number" class="block text-sm font-medium text-gray-700 mb-1">Certificate Number *</label>
                                    <input type="text" id="police_clearance_number" name="police_clearance_number" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="police_clearance_date_of_publish" class="block text-sm font-medium text-gray-700 mb-1">Issue Date *</label>
                                        <input type="date" id="police_clearance_date_of_publish" name="police_clearance_date_of_publish" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="police_clearance_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                        <input type="date" id="police_clearance_expiry_date" name="police_clearance_expiry_date" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                </div>

                                <div>
                                    <label for="police_clearance_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Certificate *</label>
                                    <input type="file" id="police_clearance_file" name="police_clearance_file" accept=".pdf,.jpg,.jpeg,.png" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <div id="police_clearance_file_preview"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Certificate -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-3">Medical Certificate</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="medical_certificate_number" class="block text-sm font-medium text-gray-700 mb-1">Certificate Number *</label>
                                    <input type="text" id="medical_certificate_number" name="medical_certificate_number" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="medical_certificate_date_of_publish" class="block text-sm font-medium text-gray-700 mb-1">Issue Date *</label>
                                        <input type="date" id="medical_certificate_date_of_publish" name="medical_certificate_date_of_publish" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="medical_certificate_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                        <input type="date" id="medical_certificate_expiry_date" name="medical_certificate_expiry_date" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                </div>

                                <div>
                                    <label for="medical_certificate_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Certificate *</label>
                                    <input type="file" id="medical_certificate_file" name="medical_certificate_file" accept=".pdf,.jpg,.jpeg,.png" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <div id="medical_certificate_file_preview"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Mental Health Certificate -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-3">Mental Health Certificate</h3>
                            <div class="space-y-3">
                                <div>
                                    <label for="mental_health_certificate_number" class="block text-sm font-medium text-gray-700 mb-1">Certificate Number *</label>
                                    <input type="text" id="mental_health_certificate_number" name="mental_health_certificate_number" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label for="mental_health_certificate_date_of_publish" class="block text-sm font-medium text-gray-700 mb-1">Issue Date *</label>
                                        <input type="date" id="mental_health_certificate_date_of_publish" name="mental_health_certificate_date_of_publish" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label for="mental_health_certificate_expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date *</label>
                                        <input type="date" id="mental_health_certificate_expiry_date" name="mental_health_certificate_expiry_date" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                </div>

                                <div>
                                    <label for="mental_health_certificate_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Certificate *</label>
                                    <input type="file" id="mental_health_certificate_file" name="mental_health_certificate_file" accept=".pdf,.jpg,.jpeg,.png" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <div id="mental_health_certificate_file_preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Review & Submit -->
                <div class="form-step" data-step="5">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Review Your Information</h2>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-600 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-green-900 mb-2">Almost Done!</h3>
                                <p class="text-sm text-green-800">Please review your information below before submitting. You can go back to any step to make changes.</p>
                            </div>
                        </div>
                    </div>

                    <div id="review-content" class="space-y-4">
                        <!-- Will be populated by JavaScript -->
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="flex items-start">
                            <input type="checkbox" id="terms-checkbox" required class="mt-1 mr-3">
                            <span class="text-sm text-gray-700">
                                I confirm that all the information provided is accurate and I agree to the 
                                <a href="#" class="text-indigo-600 hover:underline">Terms and Conditions</a> 
                                of the UCY Carpooling System.
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8 pt-6 border-t">
                    <button type="button" id="prevBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium" style="display:none;">
                        ← Previous
                    </button>
                    <div></div>
                    <button type="button" id="nextBtn" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                        Next →
                    </button>
                    <button type="submit" id="submitBtn" name="register_driver" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium" style="display:none;">
                        Submit Registration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 5;

        // Password strength checker
        document.getElementById('pswd').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthBar = document.getElementById('password-strength');
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            const colors = ['#ef4444', '#f59e0b', '#eab308', '#84cc16', '#22c55e'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];
            
            strengthBar.style.width = widths[strength - 1] || '0%';
            strengthBar.style.backgroundColor = colors[strength - 1] || '#e5e7eb';
        });

        // Password match checker - works with typing AND paste
        function checkPasswordMatch() {
            const password = document.getElementById('pswd').value;
            const confirm = document.getElementById('pswd2').value;
            const errorMsg = document.getElementById('password-match-error');
            const confirmField = document.getElementById('pswd2');
            
            if (confirm && password !== confirm) {
                errorMsg.style.display = 'block';
                confirmField.setCustomValidity('Passwords do not match');
            } else {
                errorMsg.style.display = 'none';
                confirmField.setCustomValidity('');
            }
        }
        
        // Listen to ALL possible input events for password fields
        const pswd2 = document.getElementById('pswd2');
        const pswd = document.getElementById('pswd');
        
        pswd2.addEventListener('input', checkPasswordMatch);
        pswd2.addEventListener('paste', function() {
            // Delay to let paste complete
            setTimeout(checkPasswordMatch, 50);
        });
        pswd2.addEventListener('change', checkPasswordMatch);
        pswd2.addEventListener('keyup', checkPasswordMatch);
        
        // Also check when main password changes
        pswd.addEventListener('input', checkPasswordMatch);
        pswd.addEventListener('change', checkPasswordMatch);

        // Age validation
        document.getElementById('date_of_birth').addEventListener('change', function(e) {
            const birthDate = new Date(e.target.value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const errorMsg = document.getElementById('age-error');
            
            if (age < 18) {
                errorMsg.style.display = 'block';
            } else {
                errorMsg.style.display = 'none';
            }
        });

        // File preview
        function setupFilePreview(inputId, previewId) {
            document.getElementById(inputId).addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById(previewId);
                
                if (file) {
                    // Check file size (5MB max)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size exceeds 5MB. Please choose a smaller file.');
                        e.target.value = '';
                        preview.innerHTML = '';
                        return;
                    }
                    
                    preview.innerHTML = `
                        <div class="mt-2 p-2 bg-gray-50 rounded border text-sm">
                            <strong>Selected:</strong> ${file.name} (${(file.size / 1024).toFixed(2)} KB)
                        </div>
                    `;
                    
                    // Show image preview if it's an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML += `<img src="${e.target.result}" class="file-preview border rounded mt-2">`;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        }

        // Setup file previews for all file inputs
        setupFilePreview('id_file', 'id_file_preview');
        setupFilePreview('driver_license_file', 'driver_license_file_preview');
        setupFilePreview('police_clearance_file', 'police_clearance_file_preview');
        setupFilePreview('medical_certificate_file', 'medical_certificate_file_preview');
        setupFilePreview('mental_health_certificate_file', 'mental_health_certificate_file_preview');

        // Date validation (expiry should be after issue)
        function setupDateValidation(issueId, expiryId) {
            const issueInput = document.getElementById(issueId);
            const expiryInput = document.getElementById(expiryId);
            
            function validate() {
                if (issueInput.value && expiryInput.value) {
                    if (new Date(expiryInput.value) <= new Date(issueInput.value)) {
                        expiryInput.setCustomValidity('Expiry date must be after issue date');
                    } else {
                        expiryInput.setCustomValidity('');
                    }
                }
            }
            
            issueInput.addEventListener('change', validate);
            expiryInput.addEventListener('change', validate);
        }

        setupDateValidation('id_date_of_publish', 'id_expiry_date');
        setupDateValidation('license_date_of_publish', 'license_expiry_date');
        setupDateValidation('police_clearance_date_of_publish', 'police_clearance_expiry_date');
        setupDateValidation('medical_certificate_date_of_publish', 'medical_certificate_expiry_date');
        setupDateValidation('mental_health_certificate_date_of_publish', 'mental_health_certificate_expiry_date');

        // Step navigation
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
            
            // Show current step
            document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');
            
            // Update step indicators and connectors
            const indicators = document.querySelectorAll('.step-indicator');
            const connectors = document.querySelectorAll('.progress-connector');
            
            indicators.forEach((indicator, index) => {
                indicator.classList.remove('active', 'completed');
                const stepNum = index + 1;
                
                if (stepNum < step) {
                    indicator.classList.add('completed');
                } else if (stepNum === step) {
                    indicator.classList.add('active');
                }
            });
            
            // Update connectors
            connectors.forEach((connector, index) => {
                if (index < step - 1) {
                    connector.classList.add('active');
                } else {
                    connector.classList.remove('active');
                }
            });
            
            // Update buttons
            document.getElementById('prevBtn').style.display = step === 1 ? 'none' : 'block';
            document.getElementById('nextBtn').style.display = step === totalSteps ? 'none' : 'block';
            document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';
            
            // Populate review if on last step
            if (step === 5) {
                populateReview();
            }
            
            // Scroll to top
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function validateCurrentStep() {
            const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
            const inputs = currentStepElement.querySelectorAll('input[required]');
            
            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return false;
                }
            }
            return true;
        }

        document.getElementById('nextBtn').addEventListener('click', function() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });

        document.getElementById('prevBtn').addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Populate review section
        function populateReview() {
            const form = document.getElementById('driverRegistrationForm');
            const reviewContent = document.getElementById('review-content');
            
            reviewContent.innerHTML = `
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3">Personal Information</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div><span class="text-gray-600">Email:</span> <strong>${form.email.value}</strong></div>
                        <div><span class="text-gray-600">Username:</span> <strong>${form.userName.value}</strong></div>
                        <div class="col-span-2"><span class="text-gray-600">Address:</span> <strong>${form.address.value}</strong></div>
                        <div><span class="text-gray-600">Date of Birth:</span> <strong>${form.date_of_birth.value}</strong></div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3">ID Details</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div><span class="text-gray-600">ID Number:</span> <strong>${form.id_number.value}</strong></div>
                        <div><span class="text-gray-600">Issue Date:</span> <strong>${form.id_date_of_publish.value}</strong></div>
                        <div><span class="text-gray-600">Expiry Date:</span> <strong>${form.id_expiry_date.value}</strong></div>
                        <div><span class="text-gray-600">File:</span> <strong>${form.id_file.files[0]?.name || 'N/A'}</strong></div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3">Driver License</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div><span class="text-gray-600">License Number:</span> <strong>${form.license_number.value}</strong></div>
                        <div><span class="text-gray-600">Issue Date:</span> <strong>${form.license_date_of_publish.value}</strong></div>
                        <div><span class="text-gray-600">Expiry Date:</span> <strong>${form.license_expiry_date.value}</strong></div>
                        <div><span class="text-gray-600">File:</span> <strong>${form.driver_license_file.files[0]?.name || 'N/A'}</strong></div>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3">Certificates</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">Police Clearance:</span> <strong>${form.police_clearance_number.value}</strong> (${form.police_clearance_file.files[0]?.name || 'N/A'})</div>
                        <div><span class="text-gray-600">Medical Certificate:</span> <strong>${form.medical_certificate_number.value}</strong> (${form.medical_certificate_file.files[0]?.name || 'N/A'})</div>
                        <div><span class="text-gray-600">Mental Health Certificate:</span> <strong>${form.mental_health_certificate_number.value}</strong> (${form.mental_health_certificate_file.files[0]?.name || 'N/A'})</div>
                    </div>
                </div>
            `;
        }

        // Form submission
        document.getElementById('driverRegistrationForm').addEventListener('submit', function(e) {
            if (!document.getElementById('terms-checkbox').checked) {
                e.preventDefault();
                alert('Please accept the terms and conditions to continue.');
                return false;
            }
        });

        // Initialize
        showStep(1);
    </script>
</body>
</html>
