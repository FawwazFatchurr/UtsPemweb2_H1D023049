<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Alat Praktikum</h2>
    <a href="<?= base_url('admin/alat/add') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Alat
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Daftar Alat Praktikum</h5>
    </div>
    <div class="card-body">
    <?php if(empty($alat)): ?>
            <div class="alert alert-info">
                Tidak ada data alat praktikum.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Deskripsi</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($alat as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $item->nama ?></td>
                                <td><?= substr($item->deskripsi, 0, 50) . (strlen($item->deskripsi) > 50 ? '...' : '') ?></td>
                                <td><?= $item->stok ?></td>
                                <td>
                                    <?php if($item->status == 'tersedia'): ?>
                                        <span class="badge badge-success">Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Tidak Tersedia</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/alat/edit/' . $item->id) ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= base_url('admin/alat/delete/' . $item->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus alat ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>