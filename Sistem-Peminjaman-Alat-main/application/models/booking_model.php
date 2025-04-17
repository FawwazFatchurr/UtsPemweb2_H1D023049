<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Ambil semua data booking
    public function get_all() {
        $this->db->select('booking.*, mahasiswa.nim, mahasiswa.nama as mahasiswa_nama, alat.nama as alat_nama');
        $this->db->from('booking');
        $this->db->join('mahasiswa', 'booking.mahasiswa_id = mahasiswa.id');
        $this->db->join('alat', 'booking.alat_id = alat.id');
        $this->db->order_by('booking.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    // Ambil data booking berdasarkan ID
    public function get_by_id($id) {
        $this->db->select('booking.*, mahasiswa.nim, mahasiswa.nama as mahasiswa_nama, alat.nama as alat_nama');
        $this->db->from('booking');
        $this->db->join('mahasiswa', 'booking.mahasiswa_id = mahasiswa.id');
        $this->db->join('alat', 'booking.alat_id = alat.id');
        $this->db->where('booking.id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    
    // Ambil data booking berdasarkan mahasiswa ID
    public function get_by_mahasiswa_id($mahasiswa_id) {
        $this->db->select('booking.*, alat.nama as alat_nama');
        $this->db->from('booking');
        $this->db->join('alat', 'booking.alat_id = alat.id');
        $this->db->where('booking.mahasiswa_id', $mahasiswa_id);
        $this->db->order_by('booking.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }
    
    // Tambah data booking
    public function add($data) {
        $this->db->insert('booking', $data);
        return $this->db->insert_id();
    }
    
    // Update data booking
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('booking', $data);
    }
    
    // Hapus data booking
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('booking');
    }
    
    // Verifikasi booking (approve/reject)
    public function verify($id, $status) {
        $this->db->trans_begin();
        
        // Ambil detail booking
        $booking = $this->get_by_id($id);
        
        if (!$booking) {
            $this->db->trans_rollback();
            return false;
        }
        
        // Update status booking
        $this->db->where('id', $id);
        $this->db->update('booking', array('status' => $status));
        
        // Load model alat untuk update stok
        $this->load->model('Alat_model');
        
        // Jika status approved, kurangi stok alat
        if ($status == 'approved') {
            $is_updated = $this->Alat_model->update_stock($booking->alat_id, $booking->jumlah, true);
            if (!$is_updated) {
                $this->db->trans_rollback();
                return false;
            }
        }
        
        // Jika status sebelumnya approved dan sekarang rejected, kembalikan stok
        if ($booking->status == 'approved' && $status == 'rejected') {
            $is_updated = $this->Alat_model->update_stock($booking->alat_id, $booking->jumlah, false);
            if (!$is_updated) {
                $this->db->trans_rollback();
                return false;
            }
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
}