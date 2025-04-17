<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('mahasiswa_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
    }
    
    public function index() {
        // Redirect ke halaman login
        redirect('auth/login');
    }
    
    public function login() {
        // Cek apakah user sudah login
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        
        $this->form_validation->set_rules('nim', 'NIM', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $nim = $this->input->post('nim');
            $password = $this->input->post('password');
            
            $user = $this->Mahasiswa_model->login($nim, $password);
            
            if ($user) {
                // Buat session data
                $user_data = array(
                    'user_id' => $user->id,
                    'nim' => $user->nim,
                    'nama' => $user->nama,
                    'role' => $user->role,
                    'logged_in' => TRUE
                );
                
                $this->session->set_userdata($user_data);
                
                // Redirect berdasarkan role
                if ($user->role == 'admin') {
                    redirect('admin/dashboard');
                } else {
                    redirect('dashboard');
                }
            } else {
                $this->session->set_flashdata('error', 'NIM atau Password salah');
                redirect('auth/login');
            }
        }
    }
    
    public function logout() {
        // Hapus session data
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('nim');
        $this->session->unset_userdata('nama');
        $this->session->unset_userdata('role');
        $this->session->unset_userdata('logged_in');
        
        $this->session->set_flashdata('success', 'Berhasil logout');
        redirect('auth/login');
    }
}