<?php

class VehicleController extends Controller {
    private Vehicle $vehicleModel;
    private Maintenance $maintenanceModel;
    
    public function __construct() {
        $this->vehicleModel = new Vehicle();
        $this->maintenanceModel = new Maintenance();
    }
    
    public function index(): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $vehicles = $this->vehicleModel->getAll();
        $viewMode = $_GET['view'] ?? 'grid'; // grid or list
        
        $this->view('index', [
            'vehicles' => $vehicles,
            'viewMode' => $viewMode
        ]);
    }
    
    public function show(string $id): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $vehicle = $this->vehicleModel->getById((int)$id);
        
        if (!$vehicle) {
            $_SESSION['error'] = 'Vehicle not found';
            $this->redirect('/home');
            return;
        }
        
        $maintenanceItems = $this->maintenanceModel->getByVehicleId((int)$id);
        
        $this->view('vehicle', [
            'vehicle' => $vehicle,
            'maintenanceItems' => $maintenanceItems
        ]);
    }
    
    public function add(): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $name = trim($_POST['name'] ?? '');
        
        if (empty($name)) {
            $_SESSION['error'] = 'Vehicle name is required';
            $this->redirect('/home');
            return;
        }
        
        $data = [
            'name' => $name,
            'year' => trim($_POST['year'] ?? ''),
            'make' => trim($_POST['make'] ?? ''),
            'model' => trim($_POST['model'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'license_plate' => trim($_POST['license_plate'] ?? '')
        ];
        
        $id = $this->vehicleModel->create($data);
        $_SESSION['success'] = 'Vehicle added successfully';
        $this->redirect('/vehicles/' . $id);
    }
    
    public function edit(string $id): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $name = trim($_POST['name'] ?? '');
        
        if (empty($name)) {
            $_SESSION['error'] = 'Vehicle name is required';
            $this->redirect('/vehicles/' . $id);
            return;
        }
        
        $data = [
            'name' => $name,
            'year' => trim($_POST['year'] ?? ''),
            'make' => trim($_POST['make'] ?? ''),
            'model' => trim($_POST['model'] ?? ''),
            'color' => trim($_POST['color'] ?? ''),
            'license_plate' => trim($_POST['license_plate'] ?? '')
        ];
        
        if ($this->vehicleModel->update((int)$id, $data)) {
            $_SESSION['success'] = 'Vehicle updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update vehicle';
        }
        
        $this->redirect('/vehicles/' . $id);
    }
    
    public function delete(string $id): void {
        $this->requireSetup();
        $this->requireAuth();
        
        if ($this->vehicleModel->delete((int)$id)) {
            $_SESSION['success'] = 'Vehicle deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete vehicle';
        }
        
        $this->redirect('/home');
    }
    
    public function export(string $id, string $format): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $vehicle = $this->vehicleModel->getById((int)$id);
        
        if (!$vehicle) {
            $_SESSION['error'] = 'Vehicle not found';
            $this->redirect('/home');
            return;
        }
        
        $maintenanceItems = $this->maintenanceModel->getByVehicleId((int)$id);
        
        if ($format === 'json') {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="vehicle-' . $id . '-export.json"');
            echo json_encode([
                'vehicle' => $vehicle,
                'maintenance_items' => $maintenanceItems
            ], JSON_PRETTY_PRINT);
            exit;
        } elseif ($format === 'html') {
            header('Content-Type: text/html');
            header('Content-Disposition: attachment; filename="vehicle-' . $id . '-export.html"');
            
            $this->view('export', [
                'vehicle' => $vehicle,
                'maintenanceItems' => $maintenanceItems
            ]);
            exit;
        } else {
            $_SESSION['error'] = 'Invalid export format';
            $this->redirect('/vehicles/' . $id);
        }
    }
}
