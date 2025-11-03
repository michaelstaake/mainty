<?php

class AuthController extends Controller {
    public function login(): void {
        $this->requireSetup();
        
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/home');
            return;
        }
        
        $this->view('login');
    }
    
    public function authenticate(): void {
        $this->requireSetup();
        
        $password = $_POST['password'] ?? '';
        
        if (empty($password)) {
            $_SESSION['error'] = 'Password is required';
            $this->redirect('/login');
            return;
        }
        
        $user = new User();
        
        if ($user->verifyPassword($password)) {
            $_SESSION['user_id'] = $user->getId();
            $this->redirect('/home');
        } else {
            $_SESSION['error'] = 'Invalid password';
            $this->redirect('/login');
        }
    }
    
    public function logout(): void {
        session_destroy();
        $this->redirect('/login');
    }
}
