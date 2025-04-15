<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - Inventory System' : 'Inventory System'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <style>
        body {
            padding-top: 4.5rem;
            padding-bottom: 2rem;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
        }
        
        .sidebar {
            position: fixed;
            top: 56px;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #f8f9fa;
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: .5rem 1rem;
        }
        
        .sidebar .nav-link.active {
            color: #2470dc;
        }
        
        .sidebar .nav-link:hover {
            color: #007bff;
        }
        
        .sidebar .nav-link .feather {
            margin-right: 4px;
            color: #999;
        }
        
        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            padding: 1rem;
            font-weight: bold;
            color: #6c757d;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0,0,0,.125);
            font-weight: bold;
        }
        
        .dashboard-card {
            text-align: center;
            padding: 1.5rem;
        }
        
        .dashboard-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .dashboard-card .number {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .dashboard-card .label {
            font-size: 1rem;
            color: #6c757d;
        }
        
        .badge-borrowed {
            background-color: #17a2b8;
            color: #fff;
        }
        
        .badge-returned {
            background-color: #28a745;
            color: #fff;
        }
        
        .badge-overdue {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url() ?>">Inventory System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <?php if($this->session->userdata('logged_in')) : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $this->uri->segment(1) == 'items' ? 'active' : '' ?>" href="<?= base_url('items') ?>">
                                <i class="fas fa-box"></i> Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $this->uri->segment(1) == 'categories' ? 'active' : '' ?>" href="<?= base_url('categories') ?>">
                                <i class="fas fa-tags"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= $this->uri->segment(1) == 'loans' ? 'active' : '' ?>" href="#" id="loansDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-handshake"></i> Loans
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= base_url('loans') ?>">All Loans</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('loans/active') ?>">Active Loans</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('loans/create') ?>">Borrow Item</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('loans/report') ?>">Loan Reports</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if($this->session->userdata('logged_in')) : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= $this->session->userdata('full_name') ?>
                                <span class="badge bg-secondary"><?= $this->session->userdata('role') ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('auth') ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if($this->session->userdata('logged_in')) : ?>
                <!-- Sidebar Menu -->
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="sidebar-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link <?= $this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $this->uri->segment(1) == 'items' ? 'active' : '' ?>" href="<?= base_url('items') ?>">
                                    <i class="fas fa-box"></i> Items
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $this->uri->segment(1) == 'categories' ? 'active' : '' ?>" href="<?= base_url('categories') ?>">
                                    <i class="fas fa-tags"></i> Categories
                                </a>
                            </li>
                            
                            <div class="sidebar-heading">Loans</div>
                            <li class="nav-item">
                                <a class="nav-link <?= $this->uri->segment(1) == 'loans' && $this->uri->segment(2) == '' ? 'active' : '' ?>" href="<?= base_url('loans') ?>">
                                    <i class="fas fa-list"></i> All Loans
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $this->uri->segment(2) == 'active' ? 'active' : '' ?>" href="<?= base_url('loans/active') ?>">
                                    <i class="fas fa-clock"></i> Active Loans
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $this->uri->segment(2) == 'create' ? 'active' : '' ?>" href="<?= base_url('loans/create') ?>">
                                    <i class="fas fa-handshake"></i> Borrow Item
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $this->uri->segment(2) == 'report' ? 'active' : '' ?>" href="<?= base_url('loans/report') ?>">
                                    <i class="fas fa-chart-bar"></i> Loan Reports
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            <?php endif; ?>
                
            <!-- Main Content -->
            <main class="<?= $this->session->userdata('logged_in') ? 'col-md-9 ms-sm-auto col-lg-10 px-md-4' : 'col-12' ?>">
                <div class="pt-3 pb-2 mb-3">
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $this->session->flashdata('success'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(validation_errors()): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= validation_errors(); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>