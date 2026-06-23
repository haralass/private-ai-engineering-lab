<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - UCY Carpooling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-container {
            animation: fadeIn 0.5s ease;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-2xl mx-auto px-6 py-4">
            <div class="flex items-center space-x-4">
                <!-- Carpooling Car Icon -->
                <svg class="h-20 w-20" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
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
                    <text x="50" y="92" font-family="Arial, sans-serif" font-size="10" font-weight="bold" text-anchor="middle" fill="#4F46E5">UCY Carpooling</text>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Registration</h1>
                    <p class="text-sm text-gray-600">University of Cyprus - Carpooling System</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-2xl mx-auto px-6 py-8">
        
        <!-- Welcome Message -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg shadow mb-8">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800 mb-1">Welcome!</h3>
                    <p class="text-blue-700">
                        Please fill out the form below to create your account and join the UCY Carpooling community.
                    </p>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="form-container bg-white rounded-xl shadow-lg p-8 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Your Account</h2>
            
            <form id="registrationForm" action="register_user_processor.php" method="post">
                
                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="your.email@ucy.ac.cy"
                        >
                        <p class="text-xs text-gray-500 mt-1">We'll use this to contact you</p>
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="userName" class="block text-sm font-medium text-gray-700 mb-1">
                            Username *
                        </label>
                        <input 
                            type="text" 
                            id="userName" 
                            name="userName" 
                            required 
                            minlength="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Choose a username"
                        >
                        <p class="text-xs text-gray-500 mt-1">At least 3 characters</p>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                            Address *
                        </label>
                        <input 
                            type="text" 
                            id="address" 
                            name="address" 
                            maxlength="100" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Your address"
                        >
                        <p class="text-xs text-gray-500 mt-1">Maximum 100 characters</p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="pswd" class="block text-sm font-medium text-gray-700 mb-1">
                            Password *
                        </label>
                        <input 
                            type="password" 
                            id="pswd" 
                            name="pswd" 
                            required 
                            minlength="8"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Create a strong password"
                        >
                        <div class="password-strength bg-gray-200" id="password-strength"></div>
                        <p class="text-xs text-gray-500 mt-1">At least 8 characters with uppercase, lowercase and numbers</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="pswd2" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password *
                        </label>
                        <input 
                            type="password" 
                            id="pswd2" 
                            name="pswd2" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            placeholder="Re-enter your password"
                        >
                        <p class="text-xs text-red-500 mt-1" id="password-match-error" style="display:none;">
                            Passwords do not match
                        </p>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <label class="flex items-start">
                        <input type="checkbox" id="terms" required class="mt-1 mr-3">
                        <span class="text-sm text-gray-700">
                            I agree to the <a href="#" class="text-indigo-600 hover:underline">Terms and Conditions</a> 
                            and <a href="#" class="text-indigo-600 hover:underline">Privacy Policy</a> of the UCY Carpooling System.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button 
                        type="submit" 
                        name="register"
                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-6 py-4 rounded-lg text-lg font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5"
                    >
                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Create Account
                    </button>
                </div>

                <!-- Already have account -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="login.php" class="text-indigo-600 hover:text-indigo-700 font-semibold">Sign in here</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Additional Links -->
        <div class="mt-8 text-center">
            <div class="flex justify-center space-x-6 text-sm text-gray-600">
                <a href="http://www.ucy.ac.cy/" class="hover:text-indigo-600 transition">University of Cyprus</a>
                <span>•</span>
                <a href="http://www.cs.ucy.ac.cy/" class="hover:text-indigo-600 transition">Dept. of Computer Science</a>
                <span>•</span>
                <a href="#" class="hover:text-indigo-600 transition">Help</a>
            </div>
        </div>
    </div>

    <script>
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
        
        // Listen to ALL possible input events
        const pswd2 = document.getElementById('pswd2');
        const pswd = document.getElementById('pswd');
        
        pswd2.addEventListener('input', checkPasswordMatch);
        pswd2.addEventListener('paste', function() {
            setTimeout(checkPasswordMatch, 50);
        });
        pswd2.addEventListener('change', checkPasswordMatch);
        pswd2.addEventListener('keyup', checkPasswordMatch);
        
        pswd.addEventListener('input', checkPasswordMatch);
        pswd.addEventListener('change', checkPasswordMatch);

        // Form submission validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            if (!document.getElementById('terms').checked) {
                e.preventDefault();
                alert('Please accept the terms and conditions to continue.');
                return false;
            }

            const password = document.getElementById('pswd').value;
            const confirm = document.getElementById('pswd2').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
                return false;
            }
        });

        // Username validation - no spaces
        document.getElementById('userName').addEventListener('input', function(e) {
            this.value = this.value.replace(/\s/g, '');
        });
    </script>
</body>
</html>
