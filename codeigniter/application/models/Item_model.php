<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    // Get all items with category name
    public function get_all_items() {
        $this->db->select('items.*, categories.category_name');
        $this->db->from('items');
        $this->db->join('categories', 'categories.category_id = items.category_id');
        $this->db->order_by('items.item_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    // Get single item by ID
    public function get_item($item_id) {
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('items');
        return $query->row();
    }
    
    // Add new item
    public function add_item($data) {
        return $this->db->insert('items', $data);
    }
    
    // Update item
    public function update_item($item_id, $data) {
        $this->db->where('item_id', $item_id);
        return $this->db->update('items', $data);
    }
    
    // Delete item
    public function delete_item($item_id) {
        $this->db->where('item_id', $item_id);
        return $this->db->delete('items');
    }
    
    // Check if item code exists (for validation)
    public function check_item_code_exists($item_code, $item_id = 0) {
        $this->db->where('item_code', $item_code);
        if($item_id > 0) {
            $this->db->where('item_id !=', $item_id);
        }
        $query = $this->db->get('items');
        if($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update stock quantity
    public function update_stock($item_id, $quantity) {
        $this->db->set('stock', 'stock + ' . (int)$quantity, FALSE);
        $this->db->where('item_id', $item_id);
        return $this->db->update('items');
    }
    
    // Check if stock is sufficient
    public function is_stock_sufficient($item_id, $quantity) {
        $this->db->select('stock');
        $this->db->where('item_id', $item_id);
        $query = $this->db->get('items');
        $item = $query->row();
        
        if($item && $item->stock >= $quantity) {
            return true;
        }
        return false;
    }
}