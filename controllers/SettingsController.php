<?php

class SettingsController extends Controller {
    private QuickTask $quickTaskModel;
    private User $userModel;
    
    public function __construct() {
        $this->quickTaskModel = new QuickTask();
        $this->userModel = new User();
    }
    
    public function index(): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $quickTasks = $this->quickTaskModel->getAll();
        
        $this->view('settings', [
            'quickTasks' => $quickTasks
        ]);
    }
    
    public function changePassword(): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = 'All password fields are required';
            $this->redirect('/settings');
            return;
        }
        
        if (!$this->userModel->verifyPassword($currentPassword)) {
            $_SESSION['error'] = 'Current password is incorrect';
            $this->redirect('/settings');
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'New passwords do not match';
            $this->redirect('/settings');
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'New password must be at least 6 characters';
            $this->redirect('/settings');
            return;
        }
        
        if ($this->userModel->updatePassword($newPassword)) {
            $_SESSION['success'] = 'Password changed successfully';
        } else {
            $_SESSION['error'] = 'Failed to change password';
        }
        
        $this->redirect('/settings');
    }
    
    public function addQuickTask(): void {
        $this->requireSetup();
        $this->requireAuth();
        
        $name = trim($_POST['name'] ?? '');
        
        if (empty($name)) {
            $_SESSION['error'] = 'Task name is required';
            $this->redirect('/settings');
            return;
        }
        
        $id = $this->quickTaskModel->create($name);
        
        if ($id > 0) {
            $_SESSION['success'] = 'Quick task added successfully';
        } else {
            $_SESSION['error'] = 'Task already exists or could not be added';
        }
        
        $this->redirect('/settings');
    }
    
    public function deleteQuickTask(string $id): void {
        $this->requireSetup();
        $this->requireAuth();
        
        if ($this->quickTaskModel->delete((int)$id)) {
            $_SESSION['success'] = 'Quick task deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete quick task';
        }
        
        $this->redirect('/settings');
    }
}
