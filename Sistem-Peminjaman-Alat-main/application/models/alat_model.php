<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alat_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Ambil semua data alat
    public function get_all() {
        $query = $this->db->get('alat');
        return $query->result();
    }
    
    // Ambil data alat berdasarkan ID
    public function get_by_id($id) {
        $query = $this->db->get_where('alat', array('id' => $id));
        return $query->row();
    }
    
    // Tambah data alat
    public function add($data) {
        $this->db->insert('alat', $data);
        return $this->db->insert_id();
    }
    
    // Update data alat
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('alat', $data);
    }
    
    // Hapus data alat
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('alat');
    }
    
    // Cek ketersediaan alat pada tanggal tertentu
    public function check_availability($alat_id, $tanggal, $jumlah = 1) {
        // Ambil stok alat
        $alat = $this->get_by_id($alat_id);
        
        if (!$alat) {
            return false;
        }
        
        // Hitung total alat yang sudah dibooking pada tanggal tersebut
        $this->db->select('SUM(jumlah) as total_booked');
        $this->db->from('booking');
        $this->db->where('alat_id', $alat_id);
        $this->db->where('tanggal', $tanggal);
        $this->db->where('status', 'approved');
        $query = $this->db->get();
        $result = $query->row();
        
        $total_booked = $result->total_booked ? $result->total_booked : 0;
        
        // Cek apakah stok cukup
        return ($alat->stok - $total_booked) >= $jumlah;
    }
    
    // Update stok alat setelah booking disetujui/ditolak
    public function update_stock($alat_id, $jumlah, $is_approved) {
        $alat = $this->get_by_id($alat_id);
        
        if (!$alat) {
            return false;
        }
        
        // Jika booking disetujui, kurangi stok
        // Jika booking ditolak dan sebelumnya approved, tambah stok
        if ($is_approved) {
            $this->db->set('stok', 'stok - ' . $jumlah, FALSE);
        } else {
            $this->db->set('stok', 'stok + ' . $jumlah, FALSE);
        }
        
        $this->db->where('id', $alat_id);
        return $this->db->update('alat');
    }
}