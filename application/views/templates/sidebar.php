<!-- Sidebar -->
<nav class="col-md-2 d-md-block sidebar" id="sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(1) == 'barang' ? 'active' : '' ?>" href="<?= base_url('barang') ?>">
                    <i class="fas fa-box"></i> Data Barang
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(1) == 'kategori' ? 'active' : '' ?>" href="<?= base_url('kategori') ?>">
                    <i class="fas fa-tags"></i> Kategori Barang
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(1) == 'peminjaman' && $this->uri->segment(2) == '' ? 'active' : '' ?>" href="<?= base_url('peminjaman') ?>">
                    <i class="fas fa-clipboard-check"></i> Data Peminjaman
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'tambah' ? 'active' : '' ?>" href="<?= base_url('peminjaman/tambah') ?>">
                    <i class="fas fa-plus-circle"></i> Peminjaman Baru
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(2) == 'pengembalian' ? 'active' : '' ?>" href="<?= base_url('peminjaman/pengembalian') ?>">
                    <i class="fas fa-undo"></i> Pengembalian
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= $this->uri->segment(1) == 'laporan' ? 'active' : '' ?>" href="<?= base_url('laporan') ?>">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- End Sidebar -->