<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Peminjaman_model');
        $this->load->model('Barang_model');
    }
    
    public function index() {
        $data['title'] = 'Dashboard Laporan';
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('laporan/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function barang_dipinjam() {
        $data['title'] = 'Laporan Barang Dipinjam';
        
        $tanggal_mulai = $this->input->get('tanggal_mulai') ?? date('Y-m-d', strtotime('-30 days'));
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');
        
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_akhir'] = $tanggal_akhir;
        
        // Ambil data peminjaman aktif
        $this->db->select('p.id_peminjaman, p.kode_peminjaman, p.nama_peminjam, p.tanggal_pinjam, p.status');
        $this->db->select('pd.id_detail, pd.jumlah, pd.tanggal_kembali as detail_tanggal_kembali');
        $this->db->select('b.id_barang, b.kode_barang, b.nama_barang');
        $this->db->select('k.nama_kategori');
        $this->db->from('peminjaman p');
        $this->db->join('peminjaman_detail pd', 'pd.id_peminjaman = p.id_peminjaman');
        $this->db->join('barang b', 'b.id_barang = pd.id_barang');
        $this->db->join('kategori k', 'k.id_kategori = b.id_kategori');
        $this->db->where('p.tanggal_pinjam >=', $tanggal_mulai);
        $this->db->where('p.tanggal_pinjam <=', $tanggal_akhir);
        $this->db->where('pd.status', 'dipinjam');
        $this->db->order_by('p.tanggal_pinjam', 'DESC');
        
        $data['barang_dipinjam'] = $this->db->get()->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('laporan/barang_dipinjam', $data);
        $this->load->view('templates/footer');
    }
    
    public function stok_barang() {
        $data['title'] = 'Laporan Stok Barang';
        
        $id_kategori = $this->input->get('id_kategori');
        
        $this->db->select('b.*, k.nama_kategori');
        $this->db->from('barang b');
        $this->db->join('kategori k', 'k.id_kategori = b.id_kategori');
        
        if ($id_kategori) {
            $this->db->where('b.id_kategori', $id_kategori);
        }
        
        $this->db->order_by('b.nama_barang', 'ASC');
        $data['barang'] = $this->db->get()->result();
        
        $data['kategori'] = $this->db->get('kategori')->result();
        $data['id_kategori'] = $id_kategori;
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('laporan/stok_barang', $data);
        $this->load->view('templates/footer');
    }
    
    public function riwayat_peminjaman() {
        $data['title'] = 'Riwayat Peminjaman';
        
        $tanggal_mulai = $this->input->get('tanggal_mulai') ?? date('Y-m-d', strtotime('-30 days'));
        $tanggal_akhir = $this->input->get('tanggal_akhir') ?? date('Y-m-d');
        $status = $this->input->get('status');
        
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['status'] = $status;
        
        $this->db->select('p.*, COUNT(pd.id_detail) as jumlah_item');
        $this->db->from('peminjaman p');
        $this->db->join('peminjaman_detail pd', 'pd.id_peminjaman = p.id_peminjaman');
        $this->db->where('p.tanggal_pinjam >=', $tanggal_mulai);
        $this->db->where('p.tanggal_pinjam <=', $tanggal_akhir);
        
        if ($status) {
            $this->db->where('p.status', $status);
        }
        
        $this->db->group_by('p.id_peminjaman');
        $this->db->order_by('p.tanggal_pinjam', 'DESC');
        
        $data['peminjaman'] = $this->db->get()->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('laporan/riwayat_peminjaman', $data);
        $this->load->view('templates/footer');
    }
    
    // Method untuk data laporan real-time
    public function get_dashboard_data() {
        // Total barang
        $total_barang = $this->db->count_all('barang');
        
        // Total kategori
        $total_kategori = $this->db->count_all('kategori');
        
        // Total peminjaman aktif
        $this->db->where('status', 'dipinjam');
        $total_peminjaman_aktif = $this->db->count_all_results('peminjaman');
        
        // Barang dengan stok menipis (kurang dari 5)
        $this->db->where('stok_tersedia <', 5);
        $barang_menipis = $this->db->count_all_results('barang');
        
        // Barang yang sedang dipinjam
        $this->db->where('status', 'dipinjam');
        $total_item_dipinjam = $this->db->count_all_results('peminjaman_detail');
        
        $response = [
            'total_barang' => $total_barang,
            'total_kategori' => $total_kategori,
            'total_peminjaman_aktif' => $total_peminjaman_aktif,
            'barang_menipis' => $barang_menipis,
            'total_item_dipinjam' => $total_item_dipinjam
        ];
        
        echo json_encode($response);
    }
}