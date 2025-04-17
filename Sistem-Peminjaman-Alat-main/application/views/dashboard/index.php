<h2 class="mb-4">Dashboard Mahasiswa</h2>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Daftar Alat Praktikum</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php if(empty($alat)): ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                Tidak ada alat praktikum yang tersedia saat ini.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($alat as $item): ?>
                            <div class="col-md-4">
                                <div class="card">
                                    <?php if($item->gambar): ?>
                                        <img src="<?= base_url('uploads/alat/' . $item->gambar) ?>" class="card-img-top" alt="<?= $item->nama ?>">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/img/no-image.jpg') ?>" class="card-img-top" alt="No Image">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $item->nama ?></h5>
                                        <p class="card-text"><?= $item->deskripsi ?></p>
                                        <p class="card-text">
                                            <small class="text-muted">Stok: <?= $item->stok ?></small>
                                        </p>
                                        <?php if($item->status == 'tersedia' && $item->stok > 0): ?>
                                            <a href="<?= base_url('dashboard/booking/' . $item->id) ?>" class="btn btn-primary">Booking</a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary" disabled>Tidak Tersedia</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Riwayat Booking Saya</h5>
            </div>
            <div class="card-body">
                <?php if(empty($bookings)): ?>
                    <div class="alert alert-info">
                        Anda belum memiliki riwayat booking.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Alat</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($bookings as $booking): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $booking->nama_alat ?></td>
                                        <td><?= date('d F Y', strtotime($booking->tanggal)) ?></td>
                                        <td><?= $booking->jumlah ?></td>
                                        <td>
                                            <?php if($booking->status == 'pending'): ?>
                                                <span class="badge badge-warning">Menunggu</span>
                                            <?php elseif($booking->status == 'approved'): ?>
                                                <span class="badge badge-success">Disetujui</span>
                                            <?php elseif($booking->status == 'rejected'): ?>
                                                <span class="badge badge-danger">Ditolak</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('dashboard/detail_booking/' . $booking->id) ?>" class="btn btn-sm btn-info">Detail</a>
                                            <?php if($booking->status == 'pending'): ?>
                                                <a href="<?= base_url('dashboard/cancel_booking/' . $booking->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">Batal</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>