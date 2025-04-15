<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all users
    public function get_all_users() {
        $query = $this->db->get('users');
        return $query->result();
    }
    
    // Get single user by ID
    public function get_user($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('users');
        return $query->row();
    }
    
    // Get user by username
    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }
    
    // Register user
    public function register($data) {
        return $this->db->insert('users', $data);
    }
    
    // Login user
    public function login($username, $password) {
        // Get user
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        $user = $query->row();
        
        // Check if user exists
        if(!$user) {
            return false;
        }
        
        // Check password
        if(password_verify($password, $user->password)) {
            return $user;
        } else {
            return false;
        }
    }
    
    // Check if username exists
    public function check_username_exists($username, $user_id = 0) {
        $this->db->where('username', $username);
        if($user_id > 0) {
            $this->db->where('user_id !=', $user_id);
        }
        $query = $this->db->get('users');
        if($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update user
    public function update_user($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }
    
    // Delete user
    public function delete_user($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->delete('users');
    }
}