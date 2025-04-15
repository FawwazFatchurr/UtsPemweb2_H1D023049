<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Kategori_model');
        $this->load->library('form_validation');
    }
    
    public function index() {
        $data['title'] = 'Data Barang';
        $data['barang'] = $this->Barang_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('barang/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function tambah() {
        $data['title'] = 'Tambah Barang Baru';
        $data['kategori'] = $this->Kategori_model->get_all();
        $data['kode_barang'] = $this->Barang_model->generate_kode();
        
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|is_unique[barang.kode_barang]');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('stok_total', 'Stok Total', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('barang/form', $data);
            $this->load->view('templates/footer');
        } else {
            $save_data = [
                'kode_barang' => $this->input->post('kode_barang'),
                'nama_barang' => $this->input->post('nama_barang'),
                'id_kategori' => $this->input->post('id_kategori'),
                'stok_total' => $this->input->post('stok_total'),
                'stok_tersedia' => $this->input->post('stok_total'), // Stok awal sama dengan stok total
                'satuan' => $this->input->post('satuan'),
                'deskripsi' => $this->input->post('deskripsi')
            ];
            
            $this->Barang_model->save($save_data);
            $this->session->set_flashdata('message', 'Barang berhasil ditambahkan');
            redirect('barang');
        }
    }
    
    public function edit($id) {
        $data['barang'] = $this->Barang_model->get_by_id($id);
        
        if (!$data['barang']) {
            $this->session->set_flashdata('error', 'Data barang tidak ditemukan');
            redirect('barang');
        }
        
        $data['title'] = 'Edit Barang';
        $data['kategori'] = $this->Kategori_model->get_all();
        
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('stok_total', 'Stok Total', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('barang/form', $data);
            $this->load->view('templates/footer');
        } else {
            $stok_total_baru = $this->input->post('stok_total');
            $stok_total_lama = $data['barang']->stok_total;
            $stok_tersedia_lama = $data['barang']->stok_tersedia;
            
            // Hitung stok tersedia baru berdasarkan perubahan stok total
            $selisih_stok = $stok_total_baru - $stok_total_lama;
            $stok_tersedia_baru = $stok_tersedia_lama + $selisih_stok;
            
            // Pastikan stok tersedia tidak negatif
            if ($stok_tersedia_baru < 0) {
                $stok_tersedia_baru = 0;
            }
            
            $save_data = [
                'id_barang' => $id,
                'kode_barang' => $this->input->post('kode_barang'),
                'nama_barang' => $this->input->post('nama_barang'),
                'id_kategori' => $this->input->post('id_kategori'),
                'stok_total' => $stok_total_baru,
                'stok_tersedia' => $stok_tersedia_baru,
                'satuan' => $this->input->post('satuan'),
                'deskripsi' => $this->input->post('deskripsi')
            ];
            
            $this->Barang_model->save($save_data);
            $this->session->set_flashdata('message', 'Barang berhasil diperbarui');
            redirect('barang');
        }
    }
    
    public function hapus($id) {
        $barang = $this->Barang_model->get_by_id($id);
        
        if (!$barang) {
            $this->session->set_flashdata('error', 'Data barang tidak ditemukan');
            redirect('barang');
        }
        
        // Cek apakah barang sedang dipinjam
        $this->load->model('Peminjaman_model');
        $dipinjam = $this->db->get_where('peminjaman_detail', [
            'id_barang' => $id,
            'status' => 'dipinjam'
        ])->num_rows();
        
        if ($dipinjam > 0) {
            $this->session->set_flashdata('error', 'Barang tidak dapat dihapus karena sedang dipinjam');
            redirect('barang');
        }
        
        $this->Barang_model->delete($id);
        $this->session->set_flashdata('message', 'Barang berhasil dihapus');
        redirect('barang');
    }
    
    // Method untuk mendapatkan data barang via AJAX
    public function get_data_barang() {
        $id = $this->input->post('id_barang');
        $barang = $this->Barang_model->get_by_id($id);
        
        if ($barang) {
            $response = [
                'status' => true,
                'data' => $barang
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Data barang tidak ditemukan'
            ];
        }
        
        echo json_encode($response);
    }
}