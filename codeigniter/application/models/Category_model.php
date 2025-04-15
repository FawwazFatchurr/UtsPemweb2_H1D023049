<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all categories
    public function get_all_categories() {
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get('categories');
        return $query->result();
    }
    
    // Get single category by ID
    public function get_category($category_id) {
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('categories');
        return $query->row();
    }
    
    // Add new category
    public function add_category($data) {
        return $this->db->insert('categories', $data);
    }
    
    // Update category
    public function update_category($category_id, $data) {
        $this->db->where('category_id', $category_id);
        return $this->db->update('categories', $data);
    }
    
    // Delete category
    public function delete_category($category_id) {
        $this->db->where('category_id', $category_id);
        return $this->db->delete('categories');
    }
    
    // Check if category name exists (for validation)
    public function check_category_exists($category_name, $category_id = 0) {
        $this->db->where('category_name', $category_name);
        if($category_id > 0) {
            $this->db->where('category_id !=', $category_id);
        }
        $query = $this->db->get('categories');
        if($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get item count by category
    public function get_item_count($category_id) {
        $this->db->where('category_id', $category_id);
        $this->db->from('items');
        return $this->db->count_all_results();
    }
}