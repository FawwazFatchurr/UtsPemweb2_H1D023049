<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Detail Barang</h1>
    <a href="<?= base_url('barang') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Barang</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <tr>
                            <th width="30%">Kode Barang</th>
                            <td><?= $barang->kode_barang ?></td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td><?= $barang->nama_barang ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td><?= $barang->nama_kategori ?></td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td><?= $barang->deskripsi ? $barang->deskripsi : '<em>Tidak ada deskripsi</em>' ?></td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>
                                <?php if($barang->stok <= 0): ?>
                                    <span class="badge badge-danger"><?= $barang->stok ?> (Habis)</span>
                                <?php elseif($barang->stok <= 5): ?>
                                    <span class="badge badge-warning"><?= $barang->stok ?> (Menipis)</span>
                                <?php else: ?>
                                    <span class="badge badge-success"><?= $barang->stok ?> (Tersedia)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status Ketersediaan</th>
                            <td>
                                <?php 
                                    $totalDipinjam = isset($dipinjam) ? $dipinjam : 0;
                                    $sisaStok = $barang->stok - $totalDipinjam;
                                ?>
                                <div class="progress">
                                    <?php if($barang->stok > 0): ?>
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?= ($sisaStok/$barang->stok)*100 ?>%" 
                                             aria-valuenow="<?= $sisaStok ?>" aria-valuemin="0" aria-valuemax="<?= $barang->stok ?>">
                                            <?= $sisaStok ?> tersedia
                                        </div>
                                        <?php if($totalDipinjam > 0): ?>
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: <?= ($totalDipinjam/$barang->stok)*100 ?>%" 
                                             aria-valuenow="<?= $totalDipinjam ?>" aria-valuemin="0" aria-valuemax="<?= $barang->stok ?>">
                                            <?= $totalDipinjam ?> dipinjam
                                        </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="progress-bar bg-danger" role="progressbar" 
                                             style="width: 100%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0">
                                            Tidak tersedia
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="mt-3">
                    <a href="<?= base_url('barang/edit/'.$barang->id_barang) ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="javascript:void(0)" onclick="hapusBarang('<?= $barang->id_barang ?>')" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status Peminjaman</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Tersedia <span class="float-right"><?= $sisaStok ?> dari <?= $barang->stok ?></span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= ($sisaStok/$barang->stok)*100 ?>%"></div>
                </div>
                <h4 class="small font-weight-bold">Dipinjam <span class="float-right"><?= $totalDipinjam ?> dari <?= $barang->stok ?></span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= ($totalDipinjam/$barang->stok)*100 ?>%"></div>
                </div>
                
                <?php if($barang->stok > 0): ?>
                <div class="text-center mt-4">
                    <a href="<?= base_url('peminjaman/tambah/'.$barang->id_barang) ?>" class="btn btn-primary">
                        <i class="fas fa-handshake"></i> Pinjam Barang Ini
                    </a>
                </div>
                <?php else: ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> Barang ini tidak tersedia untuk dipinjam.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Riwayat Peminjaman</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered datatable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Nama Peminjam</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($riwayat) && !empty($riwayat)): ?>
                        <?php $no = 1; foreach($riwayat as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d/m/Y', strtotime($r->tanggal_pinjam)) ?></td>
                            <td>
                                <?= $r->tanggal_kembali ? date('d/m/Y', strtotime($r->tanggal_kembali)) : '<span class="text-warning">Belum dikembalikan</span>' ?>
                            </td>
                            <td><?= $r->nama_peminjam ?></td>
                            <td><?= $r->jumlah ?></td>
                            <td>
                                <?php if($r->status == 'dipinjam'): ?>
                                    <span class="badge badge-info">Dipinjam</span>
                                <?php elseif($r->status == 'dikembalikan'): ?>
                                    <span class="badge badge-success">Dikembalikan</span>
                                <?php elseif($r->status == 'terlambat'): ?>
                                    <span class="badge badge-danger">Terlambat</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada riwayat peminjaman</td>
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