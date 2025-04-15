<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('item_model');
        $this->load->model('category_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        
        // Check if user is logged in
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }
    
    public function index() {
        // Get all items
        $data['items'] = $this->item_model->get_all_items();
        $data['title'] = 'Manage Items';
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('items/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function view($item_id) {
        // Get item details
        $data['item'] = $this->item_model->get_item($item_id);
        
        if(empty($data['item'])) {
            show_404();
        }
        
        // Get category name
        $category = $this->category_model->get_category($data['item']->category_id);
        $data['category_name'] = $category->category_name;
        
        // Set title
        $data['title'] = $data['item']->item_name;
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('items/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function create() {
        // Admin check
        if($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'You do not have permission to add items');
            redirect('items');
        }
        
        // Set title
        $data['title'] = 'Add New Item';
        
        // Get all categories for dropdown
        $data['categories'] = $this->category_model->get_all_categories();
        
        // Form validation rules
        $this->form_validation->set_rules('item_code', 'Item Code', 'required|callback_check_item_code');
        $this->form_validation->set_rules('item_name', 'Item Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer|greater_than_equal_to[0]');
        
        if($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('items/create', $data);
            $this->load->view('templates/footer');
        } else {
            // Create item
            $data = array(
                'item_code' => $this->input->post('item_code'),
                'item_name' => $this->input->post('item_name'),
                'category_id' => $this->input->post('category_id'),
                'stock' => $this->input->post('stock'),
                'description' => $this->input->post('description')
            );
            
            $this->item_model->add_item($data);
            
            // Set message and redirect
            $this->session->set_flashdata('success', 'Item has been added');
            redirect('items');
        }
    }
    
    public function edit($item_id) {
        // Admin check
        if($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'You do not have permission to edit items');
            redirect('items');
        }
        
        // Get item data
        $data['item'] = $this->item_model->get_item($item_id);
        
        if(empty($data['item'])) {
            show_404();
        }
        
        // Set title
        $data['title'] = 'Edit Item';
        
        // Get all categories for dropdown
        $data['categories'] = $this->category_model->get_all_categories();
        
        // Form validation rules
        $this->form_validation->set_rules('item_code', 'Item Code', 'required|callback_check_item_code[' . $item_id . ']');
        $this->form_validation->set_rules('item_name', 'Item Name', 'required');
        $this->form_validation->set_rules('category_id', 'Category', 'required');
        $this->form_validation->set_rules('stock', 'Stock', 'required|integer|greater_than_equal_to[0]');
        
        if($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('items/edit', $data);
            $this->load->view('templates/footer');
        } else {
            // Update item
            $data = array(
                'item_code' => $this->input->post('item_code'),
                'item_name' => $this->input->post('item_name'),
                'category_id' => $this->input->post('category_id'),
                'stock' => $this->input->post('stock'),
                'description' => $this->input->post('description')
            );
            
            $this->item_model->update_item($item_id, $data);
            
            // Set message and redirect
            $this->session->set_flashdata('success', 'Item has been updated');
            redirect('items');
        }
    }
    
    public function delete($item_id) {
        // Admin check
        if($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'You do not have permission to delete items');
            redirect('items');
        }
        
        // Check if item exists
        $item = $this->item_model->get_item($item_id);
        
        if(empty($item)) {
            show_404();
        }
        
        // Delete item
        $this->item_model->delete_item($item_id);
        
        // Set message and redirect
        $this->session->set_flashdata('success', 'Item has been deleted');
        redirect('items');
    }
    
    // Update stock manually
    public function update_stock($item_id) {
        // Admin check
        if($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'You do not have permission to update stock');
            redirect('items');
        }
        
        // Get item
        $data['item'] = $this->item_model->get_item($item_id);
        
        if(empty($data['item'])) {
            show_404();
        }
        
        // Set title
        $data['title'] = 'Update Stock';
        
        // Form validation rules
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer');
        $this->form_validation->set_rules('operation', 'Operation', 'required|in_list[add,subtract]');
        
        if($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('items/update_stock', $data);
            $this->load->view('templates/footer');
        } else {
            // Get form data
            $quantity = $this->input->post('quantity');
            $operation = $this->input->post('operation');
            
            // Adjust quantity based on operation
            if($operation === 'subtract') {
                $quantity = -1 * abs($quantity);
                
                // Check if stock will become negative
                if(($data['item']->stock + $quantity) < 0) {
                    $this->session->set_flashdata('error', 'Not enough stock. Current stock: ' . $data['item']->stock);
                    redirect('items/update_stock/' . $item_id);
                }
            }
            
            // Update stock
            $this->item_model->update_stock($item_id, $quantity);
            
            // Set message and redirect
            $this->session->set_flashdata('success', 'Stock has been updated');
            redirect('items');
        }
    }
    
    // Custom validation callback to check if item code exists
    public function check_item_code($item_code, $item_id = '') {
        if($this->item_model->check_item_code_exists($item_code, $item_id)) {
            $this->form_validation->set_message('check_item_code', 'The item code is already taken');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}