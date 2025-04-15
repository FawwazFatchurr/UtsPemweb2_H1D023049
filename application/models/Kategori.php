<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends CI_Model {
    
    private $table = 'kategori';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Mendapatkan semua data kategori
    public function get_all() {
        $this->db->order_by('nama_kategori', 'ASC');
        return $this->db->get($this->table)->result();
    }
    
    // Mendapatkan kategori berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_kategori' => $id])->row();
    }
    
    // Menyimpan data kategori (insert/update)
    public function save($data) {
        // Jika ada ID, update data
        if(isset($data['id_kategori'])) {
            $id = $data['id_kategori'];
            unset($data['id_kategori']);
            $this->db->update($this->table, $data, ['id_kategori' => $id]);
            return $id;
        } 
        // Jika tidak ada ID, insert data baru
        else {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
    }
    
    // Menghapus data kategori
    public function delete($id) {
        return $this->db->delete($this->table, ['id_kategori' => $id]);
    }
    
    // Mendapatkan jumlah barang dalam kategori
    public function get_barang_count($id_kategori) {
        $this->db->from('barang');
        $this->db->where('id_kategori', $id_kategori);
        return $this->db->count_all_results();
    }
}