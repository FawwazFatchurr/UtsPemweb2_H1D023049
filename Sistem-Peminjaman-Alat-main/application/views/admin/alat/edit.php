<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Alat Praktikum</h2>
    <a href="<?= base_url('admin/alat') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Form Edit Alat</h5>
    </div>
    <div class="card-body">
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
        
        <?= form_open_multipart('admin/alat/edit/' . $alat->id); ?>
            <div class="form-group">
                <label for="nama">Nama Alat <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" required value="<?= set_value('nama', $alat->nama) ?>">
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"><?= set_value('deskripsi', $alat->deskripsi) ?></textarea>
            </div>
            <div class="form-group">
                <label for="stok">Stok <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="stok" name="stok" required min="0" value="<?= set_value('stok', $alat->stok) ?>">
            </div>
            <div class="form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select class="form-control" id="status" name="status" required>
                    <option value="tersedia" <?= set_select('status', 'tersedia', ($alat->status == 'tersedia')) ?>>Tersedia</option>
                    <option value="tidak tersedia" <?= set_select('status', 'tidak tersedia', ($alat->status == 'tidak tersedia')) ?>>Tidak Tersedia</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gambar">Gambar</label>
                <?php if($alat->gambar): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/alat/' . $alat->gambar) ?>" alt="<?= $alat->nama ?>" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control-file" id="gambar" name="gambar">
                <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maks: 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        <?= form_close(); ?>
    </div>
</div>