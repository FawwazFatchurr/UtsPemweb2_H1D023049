<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Barang</h1>
    <a href="<?= base_url('barang') ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Barang</h6>
            </div>
            <div class="card-body">
                <?= form_open('barang/update/'.$barang->id_barang); ?>
                    <div class="form-group">
                        <label for="kode_barang">Kode Barang</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" 
                               value="<?= $barang->kode_barang ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" class="form-control <?= form_error('nama_barang') ? 'is-invalid' : '' ?>" 
                               id="nama_barang" name="nama_barang" 
                               value="<?= set_value('nama_barang', $barang->nama_barang) ?>" 
                               placeholder="Masukkan nama barang" required>
                        <div class="invalid-feedback">
                            <?= form_error('nama_barang') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="id_kategori">Kategori</label>
                        <select class="form-control <?= form_error('id_kategori') ? 'is-invalid' : '' ?>" 
                                id="id_kategori" name="id_kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach($kategori as $k): ?>
                                <option value="<?= $k->id_kategori ?>" 
                                        <?= set_select('id_kategori', $k->id_kategori, ($barang->id_kategori == $k->id_kategori)) ?>>
                                    <?= $k->nama_kategori ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= form_error('id_kategori') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control <?= form_error('deskripsi') ? 'is-invalid' : '' ?>" 
                                  id="deskripsi" name="deskripsi" rows="3" 
                                  placeholder="Masukkan deskripsi barang"><?= set_value('deskripsi', $barang->deskripsi) ?></textarea>
                        <div class="invalid-feedback">
                            <?= form_error('deskripsi') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" class="form-control <?= form_error('stok') ? 'is-invalid' : '' ?>" 
                               id="stok" name="stok" value="<?= set_value('stok', $barang->stok) ?>"
                               placeholder="Masukkan jumlah stok" min="0" required>
                        <div class="invalid-feedback">
                            <?= form_error('stok') ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('barang') ?>" class="btn btn-secondary">Batal</a>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>