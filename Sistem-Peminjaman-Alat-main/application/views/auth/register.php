<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Peminjaman Alat Praktikum</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 register-container">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Sistem Peminjaman Alat Praktikum</h4>
                        <p class="mb-0">Pendaftaran Mahasiswa</p>
                    </div>
                    <div class="card-body">
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('error') ?>
                                <button type="button" class="close" data-dismiss="alert">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        
                        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                        
                        <?= form_open('auth/register'); ?>
                            <div class="form-group">
                                <label for="nim">NIM</label>
                                <input type="text" class="form-control" id="nim" name="nim" required>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="jurusan">Jurusan</label>
                                <input type="text" class="form-control" id="jurusan" name="jurusan" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="passconf">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="passconf" name="passconf" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>
                        <?= form_close(); ?>
                        
                        <div class="text-center mt-3">
                            <p>Sudah punya akun? <a href="<?= base_url('auth/login') ?>">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery dan Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
</body>
</html>