<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
    }
    
    public function index() {
        // If user already logged in, redirect to dashboard
        if($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $this->load->view('templates/header');
        $this->load->view('auth/login');
        $this->load->view('templates/footer');
    }
    
    public function login() {
        // Form validation
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            // Get username and password
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            // Login user
            $user = $this->user_model->login($username, $password);
            
            if($user) {
                // Create session
                $user_data = array(
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'full_name' => $user->full_name,
                    'role' => $user->role,
                    'logged_in' => true
                );
                
                $this->session->set_userdata($user_data);
                
                // Set success message and redirect to dashboard
                $this->session->set_flashdata('success', 'You are now logged in');
                redirect('dashboard');
            } else {
                // Set error message and redirect to login
                $this->session->set_flashdata('login_failed', 'Invalid username or password');
                redirect('auth');
            }
        }
    }
    
    public function logout() {
        // Unset user data
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('full_name');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('logged_in');
        
        // Set message and redirect to login
        $this->session->set_flashdata('user_loggedout', 'You are now logged out');
        redirect('auth');
    }
    
    // Check if user is logged in
    public function check_login() {
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }
    
    // Check if user is admin
    public function check_admin() {
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        if($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'You do not have permission to access this page');
            redirect('dashboard');
        }
    }
}