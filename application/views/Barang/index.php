<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Barang</h1>
    <a href="<?= base_url('barang/tambah') ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Barang
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Barang</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($barang as $b): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $b->kode_barang ?></td>
                        <td><?= $b->nama_barang ?></td>
                        <td><?= $b->nama_kategori ?></td>
                        <td><?= $b->stok ?></td>
                        <td>
                            <?php if($b->stok <= 0): ?>
                                <span class="badge badge-danger">Habis</span>
                            <?php elseif($b->stok <= 5): ?>
                                <span class="badge badge-warning">Menipis</span>
                            <?php else: ?>
                                <span class="badge badge-success">Tersedia</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('barang/detail/'.$b->id_barang) ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= base_url('barang/edit/'.$b->id_barang) ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="hapusBarang('<?= $b->id_barang ?>')" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($barang)): ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data barang</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function hapusBarang(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: "Apakah anda yakin ingin menghapus barang ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('barang/hapus/') ?>" + id;
        }
    })
}
</script>