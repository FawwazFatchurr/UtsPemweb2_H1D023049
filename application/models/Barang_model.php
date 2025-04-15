<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {
    
    private $table = 'barang';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Mendapatkan semua data barang dengan detail kategori
    public function get_all() {
        $this->db->select('barang.*, kategori.nama_kategori');
        $this->db->from($this->table);
        $this->db->join('kategori', 'kategori.id_kategori = barang.id_kategori');
        $this->db->order_by('barang.nama_barang', 'ASC');
        return $this->db->get()->result();
    }
    
    // Mendapatkan barang berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_barang' => $id])->row();
    }
    
    // Mendapatkan barang berdasarkan kode barang
    public function get_by_kode($kode) {
        return $this->db->get_where($this->table, ['kode_barang' => $kode])->row();
    }
    
    // Menyimpan data barang (insert/update)
    public function save($data) {
        // Jika ada ID, update data
        if(isset($data['id_barang'])) {
            $id = $data['id_barang'];
            unset($data['id_barang']);
            $this->db->update($this->table, $data, ['id_barang' => $id]);
            return $id;
        } 
        // Jika tidak ada ID, insert data baru
        else {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }
    
    // Menghapus data barang
    public function delete($id) {
        return $this->db->delete($this->table, ['id_barang' => $id]);
    }
    
    // Mendapatkan stok tersedia dari suatu barang
    public function get_stok_tersedia($id_barang) {
        $this->db->select('stok_tersedia');
        $this->db->from($this->table);
        $this->db->where('id_barang', $id_barang);
        return $this->db->get()->row()->stok_tersedia;
    }
    
    // Update stok barang
    public function update_stok($id_barang, $jumlah, $tipe = 'kurang') {
        $barang = $this->get_by_id($id_barang);
        
        if($tipe == 'kurang') {
            $stok_baru = $barang->stok_tersedia - $jumlah;
            if($stok_baru < 0) {
                return false; // Stok tidak mencukupi
            }
        } else {
            $stok_baru = $barang->stok_tersedia + $jumlah;
            if($stok_baru > $barang->stok_total) {
                return false; // Stok melebihi total
            }
        }
        
        $this->db->update($this->table, ['stok_tersedia' => $stok_baru], ['id_barang' => $id_barang]);
        return true;
    }
    
    // Mendapatkan barang berdasarkan kategori
    public function get_by_kategori($id_kategori) {
        return $this->db->get_where($this->table, ['id_kategori' => $id_kategori])->result();
    }
    
    // Generate kode barang baru
    public function generate_kode() {
        $prefiks = 'BRG-';
        $this->db->select('RIGHT(kode_barang, 4) as kode', FALSE);
        $this->db->from($this->table);
        $this->db->where('kode_barang LIKE', $prefiks . '%');
        $this->db->order_by('kode_barang', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();
        
        if($query->num_rows() > 0) {
            $data = $query->row();
            $kode = intval($data->kode) + 1;
        } else {
            $kode = 1;
        }
        
        $kode_baru = $prefiks . str_pad($kode, 4, '0', STR_PAD_LEFT);
        return $kode_baru;
    }
}