<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($vehicle['name']); ?> - Maintenance Export</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-white p-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo APP_NAME; ?></h1>
            <h2 class="text-xl text-gray-600">Vehicle Maintenance Export</h2>
        </div>
        
        <!-- Vehicle Information -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Vehicle Information</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="font-medium text-gray-700">Name:</span>
                    <span class="text-gray-600"><?php echo htmlspecialchars($vehicle['name']); ?></span>
                </div>
                <?php if ($vehicle['year']): ?>
                    <div>
                        <span class="font-medium text-gray-700">Year:</span>
                        <span class="text-gray-600"><?php echo htmlspecialchars($vehicle['year']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($vehicle['make']): ?>
                    <div>
                        <span class="font-medium text-gray-700">Make:</span>
                        <span class="text-gray-600"><?php echo htmlspecialchars($vehicle['make']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($vehicle['model']): ?>
                    <div>
                        <span class="font-medium text-gray-700">Model:</span>
                        <span class="text-gray-600"><?php echo htmlspecialchars($vehicle['model']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($vehicle['color']): ?>
                    <div>
                        <span class="font-medium text-gray-700">Color:</span>
                        <span class="text-gray-600"><?php echo htmlspecialchars($vehicle['color']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($vehicle['license_plate']): ?>
                    <div>
                        <span class="font-medium text-gray-700">License Plate:</span>
                        <span class="text-gray-600"><?php echo htmlspecialchars($vehicle['license_plate']); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Maintenance History -->
        <div>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Maintenance History</h3>
            
            <?php if (empty($maintenanceItems)): ?>
                <p class="text-gray-500 text-center py-8">No maintenance records available.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($maintenanceItems as $item): ?>
                        <div class="border border-gray-300 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($item['name']); ?></h4>
                                <?php if ($item['cost']): ?>
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded">
                                        $<?php echo number_format($item['cost'], 2); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm text-gray-600 mb-2">
                                <div>
                                    <strong>Date:</strong> <?php echo date('M d, Y', strtotime($item['date'])); ?>
                                </div>
                                <div>
                                    <strong>Mileage:</strong> <?php echo number_format($item['mileage']); ?> miles
                                </div>
                                <?php if ($item['performed_by']): ?>
                                    <div>
                                        <strong>Performed By:</strong> <?php echo htmlspecialchars($item['performed_by']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($item['parts_list']): ?>
                                    <div>
                                        <strong>Parts:</strong> <?php echo htmlspecialchars($item['parts_list']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($item['description']): ?>
                                <div class="mt-2 text-sm text-gray-600">
                                    <strong>Description:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($item['description'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Exported on <?php echo date('F j, Y \a\t g:i A'); ?></p>
            <p>Generated by <?php echo APP_NAME; ?></p>
        </div>
    </div>
</body>
</html>
