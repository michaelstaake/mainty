<?php

class MaintenanceController extends Controller {
    private Maintenance $maintenanceModel;
    
    public function __construct() {
        $this->maintenanceModel = new Maintenance();
    }
    
    public function add(): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $vehicleId = (int)($_POST['vehicle_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $mileage = str_replace(',', '', trim($_POST['mileage'] ?? ''));
        
        if (empty($name) || empty($date) || empty($mileage)) {
            $_SESSION['error'] = 'Name, date, and mileage are required';
            $this->redirect('/vehicles/' . $vehicleId);
            return;
        }
        
        $data = [
            'vehicle_id' => $vehicleId,
            'name' => $name,
            'date' => $date,
            'mileage' => (int)$mileage,
            'description' => trim($_POST['description'] ?? ''),
            'cost' => !empty($_POST['cost']) ? (float)$_POST['cost'] : null,
            'parts_list' => trim($_POST['parts_list'] ?? ''),
            'performed_by' => trim($_POST['performed_by'] ?? '')
        ];
        
        $this->maintenanceModel->create($data);
        $_SESSION['success'] = 'Maintenance item added successfully';
        $this->redirect('/vehicles/' . $vehicleId);
    }
    
    public function edit(string $id): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $item = $this->maintenanceModel->getById((int)$id);
        
        if (!$item) {
            $_SESSION['error'] = 'Maintenance item not found';
            $this->redirect('/home');
            return;
        }
        
        $name = trim($_POST['name'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $mileage = str_replace(',', '', trim($_POST['mileage'] ?? ''));
        
        if (empty($name) || empty($date) || empty($mileage)) {
            $_SESSION['error'] = 'Name, date, and mileage are required';
            $this->redirect('/vehicles/' . $item['vehicle_id']);
            return;
        }
        
        $data = [
            'name' => $name,
            'date' => $date,
            'mileage' => (int)$mileage,
            'description' => trim($_POST['description'] ?? ''),
            'cost' => !empty($_POST['cost']) ? (float)$_POST['cost'] : null,
            'parts_list' => trim($_POST['parts_list'] ?? ''),
            'performed_by' => trim($_POST['performed_by'] ?? '')
        ];
        
        if ($this->maintenanceModel->update((int)$id, $data)) {
            $_SESSION['success'] = 'Maintenance item updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update maintenance item';
        }
        
        $this->redirect('/vehicles/' . $item['vehicle_id']);
    }
    
    public function delete(string $id): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $item = $this->maintenanceModel->getById((int)$id);
        
        if (!$item) {
            $_SESSION['error'] = 'Maintenance item not found';
            $this->redirect('/home');
            return;
        }
        
        $vehicleId = $item['vehicle_id'];
        
        if ($this->maintenanceModel->delete((int)$id)) {
            $_SESSION['success'] = 'Maintenance item deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete maintenance item';
        }
        
        $this->redirect('/vehicles/' . $vehicleId);
    }
    
    public function search(): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json([]);
            return;
        }
        
        $results = $this->maintenanceModel->searchByName($query);
        $this->json($results);
    }
}
