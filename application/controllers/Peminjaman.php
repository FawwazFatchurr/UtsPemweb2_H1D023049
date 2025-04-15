<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Peminjaman_model');
        $this->load->model('Barang_model');
        $this->load->library('form_validation');
    }
    
    public function index() {
        $data['title'] = 'Data Peminjaman';
        $data['peminjaman'] = $this->Peminjaman_model->get_all();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function tambah() {
        $data['title'] = 'Form Peminjaman Baru';
        $data['kode_peminjaman'] = $this->Peminjaman_model->generate_kode();
        $data['barang'] = $this->Barang_model->get_all();
        
        $this->form_validation->set_rules('nama_peminjam', 'Nama Peminjam', 'required');
        $this->form_validation->set_rules('tanggal_pinjam', 'Tanggal Pinjam', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('peminjaman/form', $data);
            $this->load->view('templates/footer');
        } else {
            // Data peminjaman
            $peminjaman = [
                'kode_peminjaman' => $this->input->post('kode_peminjaman'),
                'nama_peminjam' => $this->input->post('nama_peminjam'),
                'kontak_peminjam' => $this->input->post('kontak_peminjam'),
                'tanggal_pinjam' => $this->input->post('tanggal_pinjam'),
                'catatan' => $this->input->post('catatan'),
                'status' => 'dipinjam'
            ];
            
            // Data detail peminjaman
            $detail = [];
            $item_barang = $this->input->post('id_barang');
            $item_jumlah = $this->input->post('jumlah');
            
            // Validasi item barang
            if (empty($item_barang)) {
                $this->session->set_flashdata('error', 'Belum ada barang yang dipilih untuk dipinjam');
                redirect('peminjaman/tambah');
            }
            
            // Validasi stok
            $error = false;
            for ($i = 0; $i < count($item_barang); $i++) {
                $id_barang = $item_barang[$i];
                $jumlah = $item_jumlah[$i];
                
                $stok_tersedia = $this->Barang_model->get_stok_tersedia($id_barang);
                
                if ($jumlah > $stok_tersedia) {
                    $barang = $this->Barang_model->get_by_id($id_barang);
                    $this->session->set_flashdata('error', 'Stok ' . $barang->nama_barang . ' tidak mencukupi. Tersedia: ' . $stok_tersedia);
                    $error = true;
                    break;
                }
                
                $detail[] = [
                    'id_barang' => $id_barang,
                    'jumlah' => $jumlah,
                    'status' => 'dipinjam'
                ];
            }
            
            if ($error) {
                redirect('peminjaman/tambah');
                return;
            }
            
            // Simpan peminjaman
            $result = $this->Peminjaman_model->save($peminjaman, $detail);
            
            if ($result) {
                $this->session->set_flashdata('message', 'Peminjaman berhasil disimpan');
                redirect('peminjaman');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan peminjaman');
                redirect('peminjaman/tambah');
            }
        }
    }
    
    public function detail($id) {
        $data['peminjaman'] = $this->Peminjaman_model->get_by_id($id);
        
        if (!$data['peminjaman']) {
            $this->session->set_flashdata('error', 'Data peminjaman tidak ditemukan');
            redirect('peminjaman');
        }
        
        $data['title'] = 'Detail Peminjaman';
        $data['detail'] = $this->Peminjaman_model->get_detail($id);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/detail', $data);
        $this->load->view('templates/footer');
    }
    
    public function pengembalian() {
        $data['title'] = 'Pengembalian Barang';
        $data['peminjaman'] = $this->Peminjaman_model->get_active();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/pengembalian', $data);
        $this->load->view('templates/footer');
    }
    
    public function proses_pengembalian() {
        $id_detail = $this->input->post('id_detail');
        $tanggal_kembali = $this->input->post('tanggal_kembali');
        $catatan = $this->input->post('catatan');
        
        if (empty($id_detail)) {
            $this->session->set_flashdata('error', 'Tidak ada item yang dipilih');
            redirect('peminjaman/pengembalian');
        }
        
        $success = true;
        $error_message = '';
        
        foreach ($id_detail as $id) {
            $result = $this->Peminjaman_model->kembalikan($id, $tanggal_kembali, $catatan);
            
            if (!$result) {
                $success = false;
                $error_message = 'Gagal memproses pengembalian untuk beberapa item';
                break;
            }
        }
        
        if ($success) {
            $this->session->set_flashdata('message', 'Pengembalian barang berhasil diproses');
        } else {
            $this->session->set_flashdata('error', $error_message);
        }
        
        redirect('peminjaman/pengembalian');
    }
    
    public function laporan() {
        $data['title'] = 'Laporan Peminjaman';
        
        $tanggal_mulai = $this->input->get('tanggal_mulai') ?? date('Y-m-d', strtotime('-30 days'));
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');
        
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['peminjaman'] = $this->Peminjaman_model->get_laporan($tanggal_mulai, $tanggal_akhir);
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('peminjaman/laporan', $data);
        $this->load->view('templates/footer');
    }
    
    // Method untuk mendapatkan detail peminjaman via AJAX
    public function get_detail_peminjaman() {
        $id = $this->input->post('id_peminjaman');
        $peminjaman = $this->Peminjaman_model->get_by_id($id);
        $detail = $this->Peminjaman_model->get_detail($id);
        
        if ($peminjaman) {
            $response = [
                'status' => true,
                'peminjaman' => $peminjaman,
                'detail' => $detail
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ];
        }
        
        echo json_encode($response);
    }
}