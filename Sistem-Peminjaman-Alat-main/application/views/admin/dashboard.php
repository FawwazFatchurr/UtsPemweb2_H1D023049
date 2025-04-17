<h2 class="mb-4">Dashboard Admin</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-tools fa-3x mb-3"></i>
                <h4>Total Alat</h4>
                <h2><?= $total_alat ?></h2>
                <a href="<?= base_url('admin/alat') ?>" class="btn btn-light mt-3">Kelola Alat</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <i class="fas fa-user-graduate fa-3x mb-3"></i>
                <h4>Total Mahasiswa</h4>
                <h2><?= $total_mahasiswa ?></h2>
                <a href="<?= base_url('admin/mahasiswa') ?>" class="btn btn-light mt-3">Kelola Mahasiswa</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                <h4>Booking Pending</h4>
                <h2><?= $total_pending ?></h2>
                <a href="<?= base_url('admin/booking') ?>" class="btn btn-light mt-3">Kelola Booking</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Booking Terbaru</h5>
            </div>
            <div class="card-body">
                <?php if(empty($recent_bookings)): ?>
                    <div class="alert alert-info">
                        Tidak ada booking terbaru.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mahasiswa</th>
                                    <th>Alat</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($recent_bookings as $booking): ?>
                                    <tr>
                                        <td><?= $booking->id ?></td>
                                        <td><?= $booking->nama_mahasiswa ?> (<?= $booking->nim ?>)</td>
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
                                            <a href="<?= base_url('admin/booking/detail/' . $booking->id) ?>" class="btn btn-sm btn-info">Detail</a>
                                            <?php if($booking->status == 'pending'): ?>
                                                <a href="<?= base_url('admin/booking/approve/' . $booking->id) ?>" class="btn btn-sm btn-success">Setujui</a>
                                                <a href="<?= base_url('admin/booking/reject/' . $booking->id) ?>" class="btn btn-sm btn-danger">Tolak</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="<?= base_url('admin/booking') ?>" class="btn btn-primary">Lihat Semua Booking</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>