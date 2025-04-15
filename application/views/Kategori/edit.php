<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Kategori</h1>
    <a href="<?= base_url('kategori') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Kategori</h6>
            </div>
            <div class="card-body">
                <?= form_open('kategori/update/'.$kategori->id_kategori); ?>
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control <?= form_error('nama_kategori') ? 'is-invalid' : '' ?>" 
                               id="nama_kategori" name="nama_kategori" 
                               value="<?= set_value('nama_kategori', $kategori->nama_kategori) ?>" 
                               placeholder="Masukkan nama kategori" required>
                        <div class="invalid-feedback">
                            <?= form_error('nama_kategori') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control <?= form_error('deskripsi') ? 'is-invalid' : '' ?>" 
                                  id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Masukkan deskripsi kategori"><?= set_value('deskripsi', $kategori->deskripsi) ?></textarea>
                        <div class="invalid-feedback">
                            <?= form_error('deskripsi') ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('kategori') ?>" class="btn btn-secondary">Batal</a>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>