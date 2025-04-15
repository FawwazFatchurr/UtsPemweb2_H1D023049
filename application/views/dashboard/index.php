<!-- Main Content -->
<main role="main" class="col-md-10 ml-sm-auto col-lg-10 px-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-calendar-alt"></i> <?= date('d F Y') ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Barang</h6>
                            <h3 class="display-4"><?= $total_barang ?></h3>
                        </div>
                        <i class="fas fa-box fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('barang') ?>" class="text-white">Lihat Detail</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Kategori</h6>
                            <h3 class="display-4"><?= $total_kategori ?></h3>
                        </div>
                        <i class="fas fa-tags fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('kategori') ?>" class="text-white">Lihat Detail</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Peminjaman Aktif</h6>
                            <h3 class="display-4"><?= $total_peminjaman_aktif ?></h3>
                        </div>
                        <i class="fas fa-clipboard-check fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?= base_url('peminjaman') ?>" class="text-white">Lihat Detail</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Item Dipinjam</h6>
                            <h3 class="display-4"><?= $total_dipinjam ?></h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="#stok-terbatas" class="text-white">Lihat Detail</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Barang Stok Terbatas</h5>
                    <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Tersedia</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($barang_stok_terbatas)) : ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada barang dengan stok terbatas</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($barang_stok_terbatas as $barang) : ?>
                                        <tr>
                                            <td><?= $barang->kode_barang ?></td>
                                            <td><?= $barang->nama_barang ?></td>
                                            <td class="text-danger font-weight-bold"><?= $barang->stok_tersedia ?></td>
                                            <td>
                                                <span class="badge badge-warning">Stok Terbatas</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Peminjaman Terbaru</h5>
                    <a href="<?= base_url('peminjaman') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Peminjam</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($peminjaman_terbaru)) : ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data peminjaman</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($peminjaman_terbaru as $pinjam) : ?>
                                        <tr>
                                            <td><?= $pinjam->kode_peminjaman ?></td>
                                            <td><?= $pinjam->nama_peminjam ?></td>
                                            <td><?= date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) ?></td>
                                            <td>
                                                <?php if ($pinjam->status == 'dipinjam') : ?>
                                                    <span class="badge badge-info">Dipinjam</span>
                                                <?php else : ?>
                                                    <span class="badge badge-success">Dikembalikan</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Barang Paling Banyak Dipinjam</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Total Dipinjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($barang_populer)) : ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($barang_populer as $item) : ?>
                                        <tr>
                                            <td><?= $item->kode_barang ?></td>
                                            <td><?= $item->nama_barang ?></td>
                                            <td>
                                                <span class="badge badge-primary"><?= $item->total_dipinjam ?> kali</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>