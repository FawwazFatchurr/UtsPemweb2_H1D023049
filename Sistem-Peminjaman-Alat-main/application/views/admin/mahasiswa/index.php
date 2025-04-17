<h2 class="mb-4">Data Mahasiswa</h2>

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Mahasiswa</h5>
        <a href="<?= base_url('admin/add_mahasiswa') ?>" class="btn btn-light btn-sm">Tambah Mahasiswa</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mahasiswa)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data mahasiswa.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($mahasiswa as $m): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $m->nim ?></td>
                                <td><?= $m->nama ?></td>
                                <td><?= $m->email ?></td>
                                <td><?= ucfirst($m->role) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/edit_mahasiswa/' . $m->id) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('admin/delete_mahasiswa/' . $m->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>