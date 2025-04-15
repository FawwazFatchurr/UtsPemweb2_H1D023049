<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('loan_model');
        $this->load->model('category_model');
        $this->load->helper('url');
        $this->load->library('session');
        
        // Check if user is logged in
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }
    
    public function index() {
        // Update loan status (mark overdue items)
        $this->loan_model->update_loan_status();
        
        // Get statistics data
        $data['loan_stats'] = $this->loan_model->get_loan_statistics();
        $data['item_count'] = count($this->item_model->get_all_items());
        $data['category_count'] = count($this->category_model->get_all_categories());
        
        // Get recent loans
        $this->db->select('loans.*, items.item_name, users.full_name');
        $this->db->from('loans');
        $this->db->join('items', 'items.item_id = loans.item_id');
        $this->db->join('users', 'users.user_id = loans.user_id');
        $this->db->order_by('loan_date', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        $data['recent_loans'] = $query->result();
        
        // Get items with low stock
        $this->db->select('items.*, categories.category_name');
        $this->db->from('items');
        $this->db->join('categories', 'categories.category_id = items.category_id');
        $this->db->where('stock <', 5);  // Items with stock less than 5
        $this->db->order_by('stock', 'ASC');
        $query = $this->db->get();
        $data['low_stock_items'] = $query->result();
        
        // Load views
        $data['title'] = 'Dashboard';
        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}