<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peminjaman_model extends CI_Model {
    
    private $table = 'peminjaman';
    private $detail_table = 'peminjaman_detail';
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Barang_model');
    }
    
    // Mendapatkan semua data peminjaman
    public function get_all() {
        $this->db->order_by('tanggal_pinjam', 'DESC');
        return $this->db->get($this->table)->result();
    }
    
    // Mendapatkan peminjaman berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_peminjaman' => $id])->row();
    }
    
    // Mendapatkan detail peminjaman
    public function get_detail($id_peminjaman) {
        $this->db->select('pd.*, b.nama_barang, b.kode_barang');
        $this->db->from($this->detail_table . ' pd');
        $this->db->join('barang b', 'b.id_barang = pd.id_barang');
        $this->db->where('pd.id_peminjaman', $id_peminjaman);
        return $this->db->get()->result();
    }
    
    // Menyimpan data peminjaman baru
    public function save($data, $detail) {
        $this->db->trans_begin();
        
        // Simpan data peminjaman
        $this->db->insert($this->table, $data);
        $id_peminjaman = $this->db->insert_id();
        
        // Simpan detail peminjaman
        foreach ($detail as $item) {
            $item['id_peminjaman'] = $id_peminjaman;
            
            // Update stok barang
            if (!$this->Barang_model->update_stok($item['id_barang'], $item['jumlah'], 'kurang')) {
                $this->db->trans_rollback();
                return false; // Stok tidak mencukupi
            }
            
            $this->db->insert($this->detail_table, $item);
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $id_peminjaman;
        }
    }
    
    // Proses pengembalian barang
    public function kembalikan($id_detail, $tanggal_kembali, $catatan = '') {
        // Dapatkan detail peminjaman
        $detail = $this->db->get_where($this->detail_table, ['id_detail' => $id_detail])->row();
        
        if (!$detail) {
            return false;
        }
        
        $this->db->trans_begin();
        
        // Update status detail peminjaman
        $this->db->update($this->detail_table, [
            'status' => 'dikembalikan',
            'tanggal_kembali' => $tanggal_kembali,
            'catatan' => $catatan
        ], ['id_detail' => $id_detail]);
        
        // Update stok barang
        $this->Barang_model->update_stok($detail->id_barang, $detail->jumlah, 'tambah');
        
        // Cek apakah semua item sudah dikembalikan
        $belum_kembali = $this->db->get_where($this->detail_table, [
            'id_peminjaman' => $detail->id_peminjaman,
            'status' => 'dipinjam'
        ])->num_rows();
        
        // Jika semua sudah dikembalikan, update status peminjaman
        if ($belum_kembali == 0) {
            $this->db->update($this->table, [
                'status' => 'dikembalikan',
                'tanggal_kembali' => $tanggal_kembali
            ], ['id_peminjaman' => $detail->id_peminjaman]);
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    
    // Mendapatkan peminjaman aktif (belum dikembalikan)
    public function get_active() {
        $this->db->where('status', 'dipinjam');
        $this->db->order_by('tanggal_pinjam', 'DESC');
        return $this->db->get($this->table)->result();
    }
    
    // Mendapatkan laporan peminjaman berdasarkan periode
    public function get_laporan($tanggal_mulai, $tanggal_akhir) {
        $this->db->select('p.*, COUNT(pd.id_detail) as jumlah_item');
        $this->db->from($this->table . ' p');
        $this->db->join($this->detail_table . ' pd', 'pd.id_peminjaman = p.id_peminjaman');
        $this->db->where('p.tanggal_pinjam >=', $tanggal_mulai);
        $this->db->where('p.tanggal_pinjam <=', $tanggal_akhir);
        $this->db->group_by('p.id_peminjaman');
        $this->db->order_by('p.tanggal_pinjam', 'DESC');
        return $this->db->get()->result();
    }
    
    // Generate kode peminjaman baru
    public function generate_kode() {
        $today = date('Ymd');
        $prefix = 'PJM-' . $today . '-';
        
        $this->db->select('RIGHT(kode_peminjaman, 4) as kode', FALSE);
        $this->db->from($this->table);
        $this->db->where('kode_peminjaman LIKE', $prefix . '%');
        $this->db->order_by('kode_peminjaman', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            $data = $query->row();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;
        }
        
        $kode_baru = $prefix . str_pad($kode, 4, '0', STR_PAD_LEFT);
        return $kode_baru;
    }
    
    // Update status peminjaman yang terlambat
    public function update_status_terlambat() {
        $today = date('Y-m-d');
        $this->db->update($this->table, ['status' => 'terlambat'], [
            'tanggal_kembali <' => $today,
            'status' => 'dipinjam'
        ]);
        return $this->db->affected_rows();
    }
}