<h2 class="mb-4">Booking Alat</h2>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Form Booking Alat</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <?php if($alat->gambar): ?>
                            <img src="<?= base_url('uploads/alat/' . $alat->gambar) ?>" class="img-fluid" alt="<?= $alat->nama ?>">
                        <?php else: ?>
                            <img src="<?= base_url('assets/img/no-image.jpg') ?>" class="img-fluid" alt="No Image">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-8">
                        <h4><?= $alat->nama ?></h4>
                        <p><?= $alat->deskripsi ?></p>
                        <p><strong>Stok Tersedia:</strong> <?= $alat->stok ?></p>
                    </div>
                </div>

                <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

                <?= form_open('dashboard/booking/' . $alat->id); ?>
                    <div class="form-group">
                        <label for="tanggal">Tanggal Peminjaman</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required min="<?= date('Y-m-d') ?>">
                        <small class="form-text text-muted">Pilih tanggal peminjaman (minimal hari ini)</small>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required min="1" max="<?= $alat->stok ?>">
                        <small class="form-text text-muted">Jumlah alat yang akan dipinjam (maksimal <?= $alat->stok ?>)</small>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Booking Sekarang</button>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>