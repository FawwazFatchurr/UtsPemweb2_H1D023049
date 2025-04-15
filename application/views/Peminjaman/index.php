<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Peminjaman</h1>
    <a href="<?= base_url('peminjaman/tambah') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Pinjam Barang
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Peminjaman Aktif</h6>
        <div class="dropdown no-arrow">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter fa-sm fa-fw text-gray-400"></i> Filter
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item <?= $this->uri->segment(2) == '' ? 'active' : '' ?>" href="<?= base_url('peminjaman') ?>">Semua</a>
                <a class="dropdown-item <?= $this->uri->segment(2) == 'status' && $this->uri->segment(3) == 'dipinjam' ? 'active' : '' ?>" href="<?= base_url('peminjaman/status/dipinjam') ?>">Dipinjam</a>
                <a class="dropdown-item <?= $this->uri->segment(2) == 'status' && $this->uri->segment(3) == 'terlambat' ? 'active' : '' ?>" href="<?= base_url('peminjaman/status/terlambat') ?>">Terlambat</a>
                <a class="dropdown-item <?= $this->uri->segment(2) == 'status' && $this->uri->segment(3) == 'dikembalikan' ? 'active' : '' ?>" href="<?= base_url('peminjaman/status/dikembalikan') ?>">Dikembalikan</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Peminjaman</th>
                        <th>Peminjam</th>
                        <th>Barang</th>
                        <th>Tgl. Pinjam</th>
                        <th>Tgl. Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($peminjaman) && !empty($peminjaman)): ?>
                        <?php $no = 1; foreach($peminjaman as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $p->kode_peminjaman ?></td>
                            <td><?= $p->nama_peminjam ?></td>
                            <td><?= $p->nama_barang ?> (<?= $p->jumlah ?>)</td>
                            <td><?= date('d/m/Y', strtotime($p->tanggal_pinjam)) ?></td>
                            <td>
                                <?php if($p->tanggal_kembali): ?>
                                    <?= date('d/m/Y', strtotime($p->tanggal_kembali)) ?>
                                <?php else: ?>
                                    <span class="text-warning">
                                        <?= date('d/m/Y', strtotime($p->estimasi_tanggal_kembali)) ?>
                                        <?php 
                                            $tglEstimasi = new DateTime($p->estimasi_tanggal_kembali);
                                            $today = new DateTime();
                                            if($today > $tglEstimasi && $p->status == 'dipinjam'): 
                                        ?>
                                            <span class="badge badge-danger">Terlambat</span>
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($p->status == 'dipinjam'): ?>
                                    <span class="badge badge-info">Dipinjam</span>
                                <?php elseif($p->status == 'dikembalikan'): ?>
                                    <span class="badge badge-success">Dikembalikan</span>
                                <?php elseif($p->status == 'terlambat'): ?>
                                    <span class="badge badge-danger">Terlambat</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('peminjaman/detail/'.$p->id_peminjaman) ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if($p->status == 'dipinjam' || $p->status == 'terlambat'): ?>
                                <a href="<?= base_url('pengembalian/proses/'.$p->id_peminjaman) ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-undo"></i> Kembalikan
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>