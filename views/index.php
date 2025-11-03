<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Vehicles</title>
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
        
        <!-- Header with view toggle and add button -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-700">My Vehicles</h2>
            <div class="flex items-center space-x-3">
                <div class="flex bg-white rounded-lg shadow-sm">
                    <a href="<?php echo url('/home?view=grid'); ?>" 
                       class="px-3 py-2 <?php echo $viewMode === 'grid' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-l-lg transition">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </a>
                    <a href="<?php echo url('/home?view=list'); ?>" 
                       class="px-3 py-2 <?php echo $viewMode === 'list' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-r-lg transition">
                        <i class="bi bi-list-ul"></i>
                    </a>
                </div>
                <button onclick="showAddVehicleModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
                    <i class="bi bi-plus-lg"></i>
                    <span>Add Vehicle</span>
                </button>
            </div>
        </div>
        
        <!-- Vehicles Display -->
        <?php if (empty($vehicles)): ?>
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="bi bi-car-front text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No vehicles yet</h3>
                <p class="text-gray-500 mb-4">Get started by adding your first vehicle</p>
                <button onclick="showAddVehicleModal()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    Add Your First Vehicle
                </button>
            </div>
        <?php else: ?>
            <?php if ($viewMode === 'grid'): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <a href="<?php echo url('/vehicles/' . $vehicle['id']); ?>" 
                           class="bg-white rounded-lg shadow-sm hover:shadow-md transition p-6 block">
                            <div class="flex items-start justify-between mb-3">
                                <div class="bg-blue-100 p-3 rounded-lg">
                                    <i class="bi bi-car-front-fill text-blue-600 text-2xl"></i>
                                </div>
                                <span class="text-sm text-gray-500"><?php echo $vehicle['maintenance_count']; ?> records</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($vehicle['name']); ?></h3>
                            <div class="space-y-1 text-sm text-gray-600">
                                <?php if ($vehicle['year'] || $vehicle['make'] || $vehicle['model']): ?>
                                    <p><?php echo implode(' ', array_filter([$vehicle['year'], $vehicle['make'], $vehicle['model']])); ?></p>
                                <?php endif; ?>
                                <?php if ($vehicle['license_plate']): ?>
                                    <p><i class="bi bi-credit-card-2-front"></i> <?php echo htmlspecialchars($vehicle['license_plate']); ?></p>
                                <?php endif; ?>
                                <?php if ($vehicle['last_maintenance_date']): ?>
                                    <p class="text-xs text-gray-500 mt-2">Last service: <?php echo date('M d, Y', strtotime($vehicle['last_maintenance_date'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License Plate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Records</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Service</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($vehicles as $vehicle): ?>
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='<?php echo url('/vehicles/' . $vehicle['id']); ?>'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="bg-blue-100 p-2 rounded">
                                                <i class="bi bi-car-front-fill text-blue-600"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($vehicle['name']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?php echo implode(' ', array_filter([$vehicle['year'], $vehicle['make'], $vehicle['model']])) ?: '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?php echo htmlspecialchars($vehicle['license_plate']) ?: '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?php echo $vehicle['maintenance_count']; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <?php echo $vehicle['last_maintenance_date'] ? date('M d, Y', strtotime($vehicle['last_maintenance_date'])) : '-'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    
    <!-- Add Vehicle Modal -->
    <div id="addVehicleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Add New Vehicle</h3>
                <button onclick="hideAddVehicleModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form method="POST" action="<?php echo url('/vehicles/add'); ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <input type="text" name="year" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <input type="text" name="color" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                        <input type="text" name="make" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <input type="text" name="model" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Plate</label>
                        <input type="text" name="license_plate" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="hideAddVehicleModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                        Add Vehicle
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showAddVehicleModal() {
            document.getElementById('addVehicleModal').classList.remove('hidden');
        }
        
        function hideAddVehicleModal() {
            document.getElementById('addVehicleModal').classList.add('hidden');
        }
    </script>
</body>
</html>
