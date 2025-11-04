<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - <?php echo htmlspecialchars($vehicle['name']); ?></title>
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
        
        <!-- Vehicle Info Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <div class="bg-blue-100 p-4 rounded-lg">
                        <i class="bi bi-car-front-fill text-blue-600 text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($vehicle['name']); ?></h2>
                        <div class="space-y-1 text-gray-600">
                            <?php if ($vehicle['year'] || $vehicle['make'] || $vehicle['model']): ?>
                                <p class="flex items-center">
                                    <i class="bi bi-info-circle mr-2"></i>
                                    <?php echo implode(' ', array_filter([$vehicle['year'], $vehicle['make'], $vehicle['model']])); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($vehicle['color']): ?>
                                <p class="flex items-center">
                                    <i class="bi bi-palette mr-2"></i>
                                    <?php echo htmlspecialchars($vehicle['color']); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($vehicle['license_plate']): ?>
                                <p class="flex items-center">
                                    <i class="bi bi-credit-card-2-front mr-2"></i>
                                    <?php echo htmlspecialchars($vehicle['license_plate']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="showEditVehicleModal()" 
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                        <i class="bi bi-pencil"></i> <span class="hidden sm:inline">Edit</span>
                    </button>
                    <div class="relative">
                        <button onclick="toggleExportMenu()" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <i class="bi bi-download"></i> <span class="hidden sm:inline">Export</span>
                        </button>
                        <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10">
                            <a href="<?php echo url('/vehicles/' . $vehicle['id'] . '/export/json'); ?>" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-lg">
                                <i class="bi bi-filetype-json"></i> Export as JSON
                            </a>
                            <a href="<?php echo url('/vehicles/' . $vehicle['id'] . '/export/html'); ?>" 
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-b-lg">
                                <i class="bi bi-filetype-html"></i> Export as HTML
                            </a>
                        </div>
                    </div>
                    <button onclick="confirmDelete()" 
                            class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Add Maintenance Item Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Maintenance Record</h3>
            <form method="POST" action="<?php echo url('/maintenance/add'); ?>">
                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Name *</label>
                        <input type="text" name="name" id="maintenanceName" required autocomplete="off"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div id="suggestions" class="hidden absolute z-10 bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                        <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mileage *</label>
                        <input type="number" name="mileage" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                        <input type="number" step="0.01" name="cost"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Performed By</label>
                        <input type="text" name="performed_by"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parts List</label>
                        <input type="text" name="parts_list"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="bi bi-plus-lg"></i> Add Record
                </button>
            </form>
        </div>
        
        <!-- Maintenance History -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Maintenance History (<?php echo count($maintenanceItems); ?>)</h3>
            
            <?php if (empty($maintenanceItems)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="bi bi-tools text-4xl mb-2"></i>
                    <p>No maintenance records yet</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($maintenanceItems as $item): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-3">
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h4 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <?php if ($item['cost']): ?>
                                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm w-fit">
                                                $<?php echo number_format($item['cost'], 2); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 text-sm text-gray-600 mb-2">
                                        <div>
                                            <i class="bi bi-calendar3"></i>
                                            <?php echo date('M d, Y', strtotime($item['date'])); ?>
                                        </div>
                                        <div>
                                            <i class="bi bi-speedometer"></i>
                                            <?php echo number_format($item['mileage']); ?> miles
                                        </div>
                                        <?php if ($item['performed_by']): ?>
                                            <div>
                                                <i class="bi bi-person"></i>
                                                <?php echo htmlspecialchars($item['performed_by']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($item['parts_list']): ?>
                                            <div>
                                                <i class="bi bi-box"></i>
                                                <?php echo htmlspecialchars($item['parts_list']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($item['description']): ?>
                                        <p class="text-sm text-gray-600 mt-2"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex sm:flex-row gap-2 lg:ml-4">
                                    <button onclick='showEditMaintenanceModal(<?php echo json_encode($item); ?>)' 
                                            class="text-blue-600 hover:text-blue-800 px-2">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="<?php echo url('/maintenance/' . $item['id'] . '/delete'); ?>" 
                                          onsubmit="return confirm('Are you sure you want to delete this record?')" class="inline">
                                        <button type="submit" class="text-red-600 hover:text-red-800 px-2">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Edit Vehicle Modal -->
    <div id="editVehicleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Edit Vehicle</h3>
                <button onclick="hideEditVehicleModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form method="POST" action="<?php echo url('/vehicles/' . $vehicle['id'] . '/edit'); ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" required value="<?php echo htmlspecialchars($vehicle['name']); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <input type="text" name="year" value="<?php echo htmlspecialchars($vehicle['year'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                            <input type="text" name="color" value="<?php echo htmlspecialchars($vehicle['color'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Make</label>
                        <input type="text" name="make" value="<?php echo htmlspecialchars($vehicle['make'] ?? ''); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <input type="text" name="model" value="<?php echo htmlspecialchars($vehicle['model'] ?? ''); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Plate</label>
                        <input type="text" name="license_plate" value="<?php echo htmlspecialchars($vehicle['license_plate'] ?? ''); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex space-x-3 mt-6">
                    <button type="button" onclick="hideEditVehicleModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Maintenance Modal -->
    <div id="editMaintenanceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Edit Maintenance Record</h3>
                <button onclick="hideEditMaintenanceModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form id="editMaintenanceForm" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Name *</label>
                        <input type="text" name="name" id="editName" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                        <input type="date" name="date" id="editDate" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mileage *</label>
                        <input type="number" name="mileage" id="editMileage" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                        <input type="number" step="0.01" name="cost" id="editCost"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Performed By</label>
                        <input type="text" name="performed_by" id="editPerformedBy"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parts List</label>
                        <input type="text" name="parts_list" id="editPartsList"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editDescription" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="button" onclick="hideEditMaintenanceModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Vehicle Form -->
    <form id="deleteVehicleForm" method="POST" action="<?php echo url('/vehicles/' . $vehicle['id'] . '/delete'); ?>" class="hidden"></form>
    
    <script>
        let debounceTimer;
        const nameInput = document.getElementById('maintenanceName');
        const suggestionsDiv = document.getElementById('suggestions');
        
        nameInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value;
            
            if (query.length < 2) {
                suggestionsDiv.classList.add('hidden');
                return;
            }
            
            debounceTimer = setTimeout(() => {
                fetch('<?php echo url('/maintenance/search'); ?>?q=' + encodeURIComponent(query))
                    .then(r => r.json())
                    .then(items => {
                        if (items.length > 0) {
                            suggestionsDiv.innerHTML = items.map(item => 
                                `<div class="px-3 py-2 hover:bg-gray-100 cursor-pointer" onclick="selectSuggestion('${item.replace(/'/g, "\\'")}')">${item}</div>`
                            ).join('');
                            suggestionsDiv.classList.remove('hidden');
                        } else {
                            suggestionsDiv.classList.add('hidden');
                        }
                    });
            }, 300);
        });
        
        function selectSuggestion(value) {
            nameInput.value = value;
            suggestionsDiv.classList.add('hidden');
        }
        
        document.addEventListener('click', function(e) {
            if (!nameInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
        
        function showEditVehicleModal() {
            document.getElementById('editVehicleModal').classList.remove('hidden');
        }
        
        function hideEditVehicleModal() {
            document.getElementById('editVehicleModal').classList.add('hidden');
        }
        
        function showEditMaintenanceModal(item) {
            document.getElementById('editName').value = item.name;
            document.getElementById('editDate').value = item.date;
            document.getElementById('editMileage').value = item.mileage;
            document.getElementById('editCost').value = item.cost || '';
            document.getElementById('editPerformedBy').value = item.performed_by || '';
            document.getElementById('editPartsList').value = item.parts_list || '';
            document.getElementById('editDescription').value = item.description || '';
            document.getElementById('editMaintenanceForm').action = '<?php echo url('/maintenance/'); ?>' + item.id + '/edit';
            document.getElementById('editMaintenanceModal').classList.remove('hidden');
        }
        
        function hideEditMaintenanceModal() {
            document.getElementById('editMaintenanceModal').classList.add('hidden');
        }
        
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this vehicle and all its maintenance records?')) {
                document.getElementById('deleteVehicleForm').submit();
            }
        }
        
        function toggleExportMenu() {
            document.getElementById('exportMenu').classList.toggle('hidden');
        }
        
        document.addEventListener('click', function(e) {
            const exportMenu = document.getElementById('exportMenu');
            if (!e.target.closest('#exportMenu') && !e.target.closest('button[onclick="toggleExportMenu()"]')) {
                exportMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
