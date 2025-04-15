<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
        $this->load->model('Peminjaman_model');
    }
    
    public function index() {
        $data['title'] = 'Dashboard';
        
        // Jumlah barang dan kategori
        $data['total_barang'] = $this->db->count_all('barang');
        $data['total_kategori'] = $this->db->count_all('kategori');
        
        // Jumlah peminjaman aktif
        $this->db->where('status', 'dipinjam');
        $data['total_peminjaman'] = $this->db->count_all_results('peminjaman');
        $data['total_peminjaman_aktif'] = $data['total_peminjaman']; // Menambahkan variabel yang sama untuk menampilkan jumlah peminjaman aktif
        
        // Total barang yang sedang dipinjam
        $this->db->where('status', 'dipinjam');
        $data['total_dipinjam'] = $this->db->count_all_results('peminjaman_detail');
        
        // Barang dengan stok terbatas (< 5)
        $this->db->where('stok_tersedia <', 5);
        $this->db->where('stok_tersedia >', 0);
        $data['barang_stok_terbatas'] = $this->db->get('barang')->result();
        
        // Peminjaman terbaru
        $this->db->order_by('tanggal_pinjam', 'DESC');
        $this->db->limit(5);
        $data['peminjaman_terbaru'] = $this->db->get('peminjaman')->result();
        
        // Barang paling banyak dipinjam
        $this->db->select('b.kode_barang, b.nama_barang, COUNT(pd.id_detail) as total_dipinjam');
        $this->db->from('peminjaman_detail pd');
        $this->db->join('barang b', 'b.id_barang = pd.id_barang');
        $this->db->group_by('pd.id_barang');
        $this->db->order_by('total_dipinjam', 'DESC');
        $this->db->limit(5);
        $data['barang_populer'] = $this->db->get()->result();
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}