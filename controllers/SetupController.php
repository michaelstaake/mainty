<?php

class SetupController extends Controller {
    public function index(): void {
        // Check if database is already initialized
        if (Database::isInitialized()) {
            $this->redirect('/home');
            return;
        }
        
        $this->view('setup');
    }
    
    public function setup(): void {
        if (Database::isInitialized()) {
            $this->redirect('/home');
            return;
        }
        
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($password)) {
            $_SESSION['error'] = 'Password is required';
            $this->redirect('/setup');
            return;
        }
        
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match';
            $this->redirect('/setup');
            return;
        }
        
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('/setup');
            return;
        }
        
        if (Database::initialize($password)) {
            $_SESSION['success'] = 'Setup completed successfully! Please login.';
            $this->redirect('/login');
        } else {
            $_SESSION['error'] = 'Setup failed. Please try again.';
            $this->redirect('/setup');
        }
    }
}
