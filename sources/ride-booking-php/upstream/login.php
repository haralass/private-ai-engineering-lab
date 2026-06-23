<?php
session_start();

// If already logged in, redirect to book_ride
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: book_ride.php");
    exit;
}

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // DEMO CREDENTIALS (change these!)
    $valid_username = 'admin';
    $valid_password = 'password123';
    
    // TODO: Replace with database check
    // Example: $user = checkUserCredentials($username, $password);
    
    if ($username === $valid_username && $password === $valid_password) {
        // Login successful
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = 1; // Set real user ID from database
        
        header("Location: book_ride.php");
        exit;
    } else {
        $error = 'Λάθος όνομα χρήστη ή κωδικός!';
    }
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Book a Ride</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-500 to-purple-600 min-h-screen flex items-center justify-center p-4">

<div class="max-w-md w-full">
    <!-- Logo/Title -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">🚗 Book a Ride</h1>
        <p class="text-blue-100">Σύστημα Κράτησης Διαδρομών</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Σύνδεση</h2>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <p class="text-sm">❌ <?= htmlspecialchars($error) ?></p>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Username -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">
                    👤 Όνομα Χρήστη
                </label>
                <input 
                    type="text" 
                    name="username" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg 
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                           transition-all outline-none"
                    placeholder="Εισάγετε όνομα χρήστη"
                    autocomplete="username">
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    🔒 Κωδικός Πρόσβασης
                </label>
                <input 
                    type="password" 
                    name="password" 
                    required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg 
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 
                           transition-all outline-none"
                    placeholder="Εισάγετε κωδικό"
                    autocomplete="current-password">
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold 
                       py-3 rounded-lg transition-all shadow-lg hover:shadow-xl 
                       transform hover:-translate-y-0.5">
                🚀 Σύνδεση
            </button>
        </form>

        <!-- Demo Info -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-blue-800 font-semibold mb-1">🔍 Demo Credentials:</p>
            <p class="text-xs text-blue-700">Username: <code class="bg-blue-100 px-2 py-0.5 rounded">admin</code></p>
            <p class="text-xs text-blue-700">Password: <code class="bg-blue-100 px-2 py-0.5 rounded">password123</code></p>
        </div>

        <!-- Footer Links -->
        <div class="mt-6 text-center">
            <a href="#" class="text-sm text-blue-600 hover:text-blue-700">
                Ξεχάσατε τον κωδικό σας;
            </a>
        </div>
    </div>

    <!-- Copyright -->
    <p class="text-center text-white text-sm mt-6 opacity-75">
        © 2025 Book a Ride System
    </p>
</div>

</body>
</html>
