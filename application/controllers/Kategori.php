<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Kategori_model');
        $this->load->library('form_validation');
    }
    
    public function index() {
        $data['title'] = 'Data Kategori Barang';
        $data['kategori'] = $this->Kategori_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('kategori/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function tambah() {
        $data['title'] = 'Tambah Kategori Baru';
        
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('kategori/form', $data);
            $this->load->view('templates/footer');
        } else {
            $save_data = [
                'nama_kategori' => $this->input->post('nama_kategori'),
                'deskripsi' => $this->input->post('deskripsi')
            ];
            
            $this->Kategori_model->save($save_data);
            $this->session->set_flashdata('message', 'Kategori berhasil ditambahkan');
            redirect('kategori');
        }
    }
    
    public function edit($id) {
        $data['kategori'] = $this->Kategori_model->get_by_id($id);
        
        if (!$data['kategori']) {
            $this->session->set_flashdata('error', 'Data kategori tidak ditemukan');
            redirect('kategori');
        }
        
        $data['title'] = 'Edit Kategori';
        
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('kategori/form', $data);
            $this->load->view('templates/footer');
        } else {
            $save_data = [
                'id_kategori' => $id,
                'nama_kategori' => $this->input->post('nama_kategori'),
                'deskripsi' => $this->input->post('deskripsi')
            ];
            
            $this->Kategori_model->save($save_data);
            $this->session->set_flashdata('message', 'Kategori berhasil diperbarui');
            redirect('kategori');
        }
    }
    
    public function hapus($id) {
        $kategori = $this->Kategori_model->get_by_id($id);
        
        if (!$kategori) {
            $this->session->set_flashdata('error', 'Data kategori tidak ditemukan');
            redirect('kategori');
        }
        
        // Cek apakah ada barang dalam kategori ini
        $jumlah_barang = $this->Kategori_model->get_barang_count($id);
        
        if ($jumlah_barang > 0) {
            $this->session->set_flashdata('error', 'Kategori tidak dapat dihapus karena memiliki ' . $jumlah_barang . ' barang terkait');
            redirect('kategori');
        }
        
        $this->Kategori_model->delete($id);
        $this->session->set_flashdata('message', 'Kategori berhasil dihapus');
        redirect('kategori');
    }
}