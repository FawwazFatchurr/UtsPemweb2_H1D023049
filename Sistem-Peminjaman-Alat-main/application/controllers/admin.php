<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Mahasiswa_model');
        $this->load->model('Alat_model');
        $this->load->model('Booking_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('url');
        
        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        // Cek apakah user adalah admin
        if ($this->session->userdata('role') != 'admin') {
            redirect('dashboard');
        }
    }
    
    public function dashboard() {
        $data['bookings'] = $this->Booking_model->get_all();
        
        $this->load->view('templates/header');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('templates/footer');
    }
    
    // CRUD Mahasiswa
    public function mahasiswa() {
        $data['mahasiswa'] = $this->Mahasiswa_model->get_all();
        
        $this->load->view('templates/header');
        $this->load->view('admin/mahasiswa/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function add_mahasiswa() {
        $this->form_validation->set_rules('nim', 'NIM', 'required|is_unique[mahasiswa.nim]');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('admin/mahasiswa/add');
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'nim' => $this->input->post('nim'),
                'nama' => $this->input->post('nama'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role')
            );
            
            $this->Mahasiswa_model->add($data);
            
            $this->session->set_flashdata('success', 'Data mahasiswa berhasil ditambahkan');
            redirect('admin/mahasiswa');
        }
    }
    
    public function edit_mahasiswa($id) {
        $mahasiswa = $this->Mahasiswa_model->get_by_id($id);
        
        if (!$mahasiswa) {
            $this->session->set_flashdata('error', 'Data mahasiswa tidak ditemukan');
            redirect('admin/mahasiswa');
        }
        
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        
        if ($this->form_validation->run() == FALSE) {
            $data['mahasiswa'] = $mahasiswa;
            
            $this->load->view('templates/header');
            $this->load->view('admin/mahasiswa/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role')
            );
            
            // Update password jika diisi
            if ($this->input->post('password') != '') {
                $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            }
            
            $this->Mahasiswa_model->update($id, $data);
            
            $this->session->set_flashdata('success', 'Data mahasiswa berhasil diupdate');
            redirect('admin/mahasiswa');
        }
    }
    
    public function delete_mahasiswa($id) {
        $mahasiswa = $this->Mahasiswa_model->get_by_id($id);
        
        if (!$mahasiswa) {
            $this->session->set_flashdata('error', 'Data mahasiswa tidak ditemukan');
            redirect('admin/mahasiswa');
        }
        
        $this->Mahasiswa_model->delete($id);
        
        $this->session->set_flashdata('success', 'Data mahasiswa berhasil dihapus');
        redirect('admin/mahasiswa');
    }
    
    // CRUD Alat
    public function alat() {
        $data['alat'] = $this->Alat_model->get_all();
        
        $this->load->view('templates/header');
        $this->load->view('admin/alat/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function add_alat() {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer|greater_than_equal_to[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('admin/alat/add');
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'deskripsi' => $this->input->post('deskripsi'),
                'stok' => $this->input->post('stok')
            );
            
            // Upload gambar jika ada
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = uniqid();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $data['gambar'] = $upload_data['file_name'];
                }
            }
            
            $this->Alat_model->add($data);
            
            $this->session->set_flashdata('success', 'Data alat berhasil ditambahkan');
            redirect('admin/alat');
        }
    }
    
    public function edit_alat($id) {
        $alat = $this->Alat_model->get_by_id($id);
        
        if (!$alat) {
            $this->session->set_flashdata('error', 'Data alat tidak ditemukan');
            redirect('admin/alat');
        }
        
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('stok', 'Stok', 'required|integer|greater_than_equal_to[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $data['alat'] = $alat;
            
            $this->load->view('templates/header');
            $this->load->view('admin/alat/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'deskripsi' => $this->input->post('deskripsi'),
                'stok' => $this->input->post('stok')
            );
            
            // Upload gambar jika ada
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 2048;
                $config['file_name'] = uniqid();
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('gambar')) {
                    // Hapus gambar lama
                    if ($alat->gambar != 'default.jpg') {
                        if (file_exists('./uploads/' . $alat->gambar)) {
                            unlink('./uploads/' . $alat->gambar);
                        }
                    }                 
                    $upload_data = $this->upload->data();
                                        $data['gambar'] = $upload_data['file_name'];
                                    }
                                }
                                
                                $this->Alat_model->update($id, $data);
                                
                                $this->session->set_flashdata('success', 'Data alat berhasil diupdate');
                                redirect('admin/alat');
                            }
                        }
                        
                        public function delete_alat($id) {
                            $alat = $this->Alat_model->get_by_id($id);
                            
                            if (!$alat) {
                                $this->session->set_flashdata('error', 'Data alat tidak ditemukan');
                                redirect('admin/alat');
                            }
                            
                            // Hapus gambar
                            if ($alat->gambar != 'default.jpg') {
                                if (file_exists('./uploads/' . $alat->gambar)) {
                                    unlink('./uploads/' . $alat->gambar);
                                }
                            }
                            
                            $this->Alat_model->delete($id);
                            
                            $this->session->set_flashdata('success', 'Data alat berhasil dihapus');
                            redirect('admin/alat');
                        }
                        
                        // Verifikasi Booking
                        public function verify_booking($id, $status) {
                            $booking = $this->Booking_model->get_by_id($id);
                            
                            if (!$booking) {
                                $this->session->set_flashdata('error', 'Data booking tidak ditemukan');
                                redirect('admin/dashboard');
                            }
                            
                            if ($status != 'approved' && $status != 'rejected') {
                                $this->session->set_flashdata('error', 'Status tidak valid');
                                redirect('admin/dashboard');
                            }
                            
                            $result = $this->Booking_model->verify($id, $status);
                            
                            if ($result) {
                                $this->session->set_flashdata('success', 'Booking berhasil ' . ($status == 'approved' ? 'disetujui' : 'ditolak'));
                            } else {
                                $this->session->set_flashdata('error', 'Gagal memverifikasi booking');
                            }
                            
                            redirect('admin/dashboard');
                        }
                        
                        public function detail_booking($id) {
                            $booking = $this->Booking_model->get_by_id($id);
                            
                            if (!$booking) {
                                $this->session->set_flashdata('error', 'Data booking tidak ditemukan');
                                redirect('admin/dashboard');
                            }
                            
                            $data['booking'] = $booking;
                            
                            $this->load->view('templates/header');
                            $this->load->view('admin/detail_booking', $data);
                            $this->load->view('templates/footer');
                        }
                    }