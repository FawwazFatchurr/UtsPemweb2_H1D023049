<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mahasiswa_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Ambil semua data mahasiswa
    public function get_all() {
        $query = $this->db->get('mahasiswa');
        return $query->result();
    }
    
    // Ambil data mahasiswa berdasarkan ID
    public function get_by_id($id) {
        $query = $this->db->get_where('mahasiswa', array('id' => $id));
        return $query->row();
    }
    
    // Ambil data mahasiswa berdasarkan NIM
    public function get_by_nim($nim) {
        $query = $this->db->get_where('mahasiswa', array('nim' => $nim));
        return $query->row();
    }
    
    // Tambah data mahasiswa
    public function add($data) {
        $this->db->insert('mahasiswa', $data);
        return $this->db->insert_id();
    }
    
    // Update data mahasiswa
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('mahasiswa', $data);
    }
    
    // Hapus data mahasiswa
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('mahasiswa');
    }
    
    // Cek login
    public function login($nim, $password) {
        $query = $this->db->get_where('mahasiswa', array('nim' => $nim));
        $user = $query->row();
        
        if ($user) {
            // Verifikasi password
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }
}