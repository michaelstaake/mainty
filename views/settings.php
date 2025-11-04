<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="<?php echo url('/home'); ?>" class="text-2xl font-bold text-gray-800 hover:text-gray-600 transition"><?php echo APP_NAME; ?></a>
            <div class="flex items-center space-x-4">
                <a href="<?php echo url('/settings'); ?>" class="text-gray-600 hover:text-gray-800">
                    <i class="bi bi-gear-fill text-2xl"></i>
                </a>
            </div>
        </div>
    </header>
    
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="<?php echo url('/home'); ?>" class="text-blue-600 hover:text-blue-800">← Back to Vehicles</a>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Settings</h2>
            <a href="<?php echo url('/logout'); ?>" 
               class="inline-flex items-center bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-md transition">
                <i class="bi bi-box-arrow-right mr-2"></i> Logout
            </a>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Tasks Section -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-lightning-charge-fill text-yellow-500 mr-2"></i>
                    Quick Tasks
                </h3>
                <p class="text-sm text-gray-600 mb-4">Predefined maintenance items for quick selection when adding records.</p>
                
                <!-- Add Quick Task Form -->
                <form method="POST" action="<?php echo url('/settings/quick-tasks/add'); ?>" class="mb-4">
                    <div class="flex space-x-2">
                        <input type="text" name="name" required placeholder="New task name"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                            <i class="bi bi-plus-lg"></i> Add
                        </button>
                    </div>
                </form>
                
                <!-- Quick Tasks List -->
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    <?php foreach ($quickTasks as $task): ?>
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded border border-gray-200">
                            <span class="text-gray-700"><?php echo htmlspecialchars($task['name']); ?></span>
                            <form method="POST" action="<?php echo url('/settings/quick-tasks/' . $task['id'] . '/delete'); ?>" 
                                  onsubmit="return confirm('Are you sure you want to delete this quick task?')" class="inline">
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Change Password Section -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-key-fill text-blue-500 mr-2"></i>
                    Change Password
                </h3>
                <p class="text-sm text-gray-600 mb-4">Update your login password.</p>
                
                <form method="POST" action="<?php echo url('/settings/password'); ?>" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="new_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="At least 6 characters">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Powered by Mainty Section -->
        <div class="mt-6 bg-white rounded-lg shadow-sm p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center justify-center">
                <i class="bi bi-github text-gray-700 mr-2"></i>
                Powered by Mainty, a project by Michael Staake and the community.
            </h3>
            <p class="text-sm text-gray-600 mb-3">
                Get the latest version, learn more, or report issues on the official project GitHub.
            </p>
            <a href="https://github.com/michaelstaake/mainty" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                <i class="bi bi-box-arrow-up-right mr-1"></i>
                github.com/michaelstaake/mainty
            </a>
        </div>
    </main>
</body>
</html>
