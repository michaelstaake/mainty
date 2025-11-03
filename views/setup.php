<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center"><?php echo APP_NAME; ?> Setup</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
            <h2 class="font-semibold text-blue-900 mb-2">System Requirements</h2>
            <ul class="space-y-1 text-sm">
                <li class="flex items-center">
                    <i class="bi bi-check-circle-fill text-green-600 mr-2"></i>
                    <span>PHP Version: <?php echo PHP_VERSION; ?> 
                        <?php if (version_compare(PHP_VERSION, '8.0.0', '>=')): ?>
                            <span class="text-green-600">(✓ Meets requirement)</span>
                        <?php else: ?>
                            <span class="text-red-600">(✗ Requires 8.0+)</span>
                        <?php endif; ?>
                    </span>
                </li>
                <li class="flex items-center">
                    <?php if (extension_loaded('pdo_sqlite')): ?>
                        <i class="bi bi-check-circle-fill text-green-600 mr-2"></i>
                        <span>SQLite: <span class="text-green-600">Available (✓)</span></span>
                    <?php else: ?>
                        <i class="bi bi-x-circle-fill text-red-600 mr-2"></i>
                        <span>SQLite: <span class="text-red-600">Not Available (✗)</span></span>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        
        <?php 
        $canSetup = version_compare(PHP_VERSION, '8.0.0', '>=') && extension_loaded('pdo_sqlite');
        ?>
        
        <?php if (!$canSetup): ?>
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-4">
                <p class="font-semibold">System requirements not met!</p>
                <p class="text-sm mt-1">Please ensure PHP 8.0+ and SQLite extension are available.</p>
            </div>
        <?php else: ?>
            <form method="POST" action="<?php echo url('/setup'); ?>" class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Create Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter password (min 6 characters)">
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Confirm password">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                    Complete Setup
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
