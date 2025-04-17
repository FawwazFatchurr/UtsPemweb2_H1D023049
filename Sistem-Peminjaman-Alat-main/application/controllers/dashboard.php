<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('alat_model');
        $this->load->model('booking_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Cek apakah user adalah mahasiswa
        if ($this->session->userdata('role') != 'mahasiswa') {
            redirect('admin/dashboard');
        }
    }
    
    public function index() {
        $data['alat'] = $this->Alat_model->get_all();
        $data['bookings'] = $this->Booking_model->get_by_mahasiswa_id($this->session->userdata('user_id'));
        
        $this->load->view('templates/header');
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function booking($alat_id = NULL) {
        if ($alat_id === NULL) {
            redirect('dashboard');
        }
        
        $alat = $this->Alat_model->get_by_id($alat_id);
        
        if (!$alat) {
            $this->session->set_flashdata('error', 'Alat tidak ditemukan');
            redirect('dashboard');
        }
        
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer|greater_than[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $data['alat'] = $alat;
            
            $this->load->view('templates/header');
            $this->load->view('dashboard/booking', $data);
            $this->load->view('templates/footer');
        } else {
            $tanggal = $this->input->post('tanggal');
            $jumlah = $this->input->post('jumlah');
            
            // Cek ketersediaan alat
            $available = $this->Alat_model->check_availability($alat_id, $tanggal, $jumlah);
            
            if (!$available) {
                $this->session->set_flashdata('error', 'Alat tidak tersedia pada tanggal tersebut atau stok tidak mencukupi');
                redirect('dashboard/booking/' . $alat_id);
            }
            
            // Simpan data booking
            $data = array(
                'mahasiswa_id' => $this->session->userdata('user_id'),
                'alat_id' => $alat_id,
                'tanggal' => $tanggal,
                'jumlah' => $jumlah,
                'status' => 'pending'
            );
            
            $this->Booking_model->add($data);
            
            $this->session->set_flashdata('success', 'Booking berhasil dilakukan. Menunggu verifikasi admin.');
            redirect('dashboard');
        }
    }
    
    public function detail_booking($id) {
        $booking = $this->Booking_model->get_by_id($id);
        
        if (!$booking || $booking->mahasiswa_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Booking tidak ditemukan');
            redirect('dashboard');
        }
        
        $data['booking'] = $booking;
        
        $this->load->view('templates/header');
        $this->load->view('dashboard/detail_booking', $data);
        $this->load->view('templates/footer');
    }
    
    public function cancel_booking($id) {
        $booking = $this->Booking_model->get_by_id($id);
        
        if (!$booking || $booking->mahasiswa_id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Booking tidak ditemukan');
            redirect('dashboard');
        }
        
        // Hanya bisa cancel booking yang masih pending
        if ($booking->status != 'pending') {
            $this->session->set_flashdata('error', 'Booking tidak dapat dibatalkan karena sudah diverifikasi');
            redirect('dashboard');
        }
        
        $this->Booking_model->delete($id);
        
        $this->session->set_flashdata('success', 'Booking berhasil dibatalkan');
        redirect('dashboard');
    }
}