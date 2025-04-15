<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loans extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('loan_model');
        $this->load->model('item_model');
        $this->load->model('user_model');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        
        // Check if user is logged in
        if(!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }
    
    public function index() {
        // Update loan status (mark overdue items)
        $this->loan_model->update_loan_status();
        
        // Get all loans
        $data['loans'] = $this->loan_model->get_all_loans();
        $data['title'] = 'Manage Loans';
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('loans/index', $data);
        $this->load->view('templates/footer');
    }
    
    public function active() {
        // Update loan status (mark overdue items)
        $this->loan_model->update_loan_status();
        
        // Get active loans
        $data['loans'] = $this->loan_model->get_active_loans();
        $data['title'] = 'Active Loans';
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('loans/active', $data);
        $this->load->view('templates/footer');
    }
    
    public function view($loan_id) {
        // Get loan details
        $data['loan'] = $this->loan_model->get_loan($loan_id);
        
        if(empty($data['loan'])) {
            show_404();
        }
        
        // Set title
        $data['title'] = 'Loan Details';
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('loans/view', $data);
        $this->load->view('templates/footer');
    }
    
    public function create() {
        // Set title
        $data['title'] = 'Borrow Item';
        
        // Get all items with available stock for dropdown
        $this->db->select('items.*, categories.category_name');
        $this->db->from('items');
        $this->db->join('categories', 'categories.category_id = items.category_id');
        $this->db->where('items.stock >', 0);
        $this->db->order_by('items.item_name', 'ASC');
        $query = $this->db->get();
        $data['items'] = $query->result();
        
        // Get all users for dropdown
        $data['users'] = $this->user_model->get_all_users();
        
        // Form validation rules
        $this->form_validation->set_rules('item_id', 'Item', 'required');
        $this->form_validation->set_rules('user_id', 'User', 'required');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than[0]|callback_check_stock');
        $this->form_validation->set_rules('loan_date', 'Loan Date', 'required');
        $this->form_validation->set_rules('due_date', 'Due Date', 'required|callback_check_due_date');
        
        if($this->form_validation->run() === FALSE) {
            $this->load->view('templates/header', $data);
            $this->load->view('loans/create', $data);
            $this->load->view('templates/footer');
        } else {
            // Create loan record
            $data = array(
                'item_id' => $this->input->post('item_id'),
                'user_id' => $this->input->post('user_id'),
                'quantity' => $this->input->post('quantity'),
                'loan_date' => $this->input->post('loan_date'),
                'due_date' => $this->input->post('due_date'),
                'notes' => $this->input->post('notes'),
                'status' => 'borrowed'
            );
            
            if($this->loan_model->add_loan($data)) {
                // Set message and redirect
                $this->session->set_flashdata('success', 'Item has been borrowed successfully');
                redirect('loans');
            } else {
                $this->session->set_flashdata('error', 'Failed to process loan. Please try again.');
                redirect('loans/create');
            }
        }
    }
    
    public function return_item($loan_id) {
        // Get loan details
        $loan = $this->loan_model->get_loan($loan_id);
        
        if(empty($loan)) {
            show_404();
        }
        
        // Check if already returned
        if($loan->status === 'returned') {
            $this->session->set_flashdata('error', 'This item has already been returned');
            redirect('loans');
        }
        
        // Process return
        $return_date = date('Y-m-d'); // Default to today
        
        if($this->loan_model->return_item($loan_id, $return_date)) {
            $this->session->set_flashdata('success', 'Item has been returned successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to process return. Please try again.');
        }
        
        redirect('loans');
    }
    
    public function report() {
        // Set title
        $data['title'] = 'Loan Reports';
        
        // Get date filters
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : date('Y-m-d', strtotime('-30 days'));
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : date('Y-m-d');
        
        // Get loans within date range
        $data['loans'] = $this->loan_model->get_loans_by_date_range($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('loans/report', $data);
        $this->load->view('templates/footer');
    }
    
    // Custom validation callback to check if stock is sufficient
    public function check_stock($quantity) {
        $item_id = $this->input->post('item_id');
        
        if($this->item_model->is_stock_sufficient($item_id, $quantity)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_stock', 'The selected item does not have sufficient stock');
            return FALSE;
        }
    }
    
    // Custom validation callback to check due date
    public function check_due_date($due_date) {
        $loan_date = $this->input->post('loan_date');
        
        if(strtotime($due_date) < strtotime($loan_date)) {
            $this->form_validation->set_message('check_due_date', 'Due date cannot be earlier than loan date');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}