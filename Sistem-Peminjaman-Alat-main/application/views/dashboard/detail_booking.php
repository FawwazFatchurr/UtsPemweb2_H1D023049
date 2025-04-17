<h2 class="mb-4">Detail Booking</h2>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Informasi Booking</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Detail Peminjaman</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID Booking</th>
                                <td><?= $booking->id ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Booking</th>
                                <td><?= date('d F Y', strtotime($booking->created_at)) ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Peminjaman</th>
                                <td><?= date('d F Y', strtotime($booking->tanggal)) ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if($booking->status == 'pending'): ?>
                                        <span class="badge badge-warning">Menunggu</span>
                                    <?php elseif($booking->status == 'approved'): ?>
                                        <span class="badge badge-success">Disetujui</span>
                                    <?php elseif($booking->status == 'rejected'): ?>
                                        <span class="badge badge-danger">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Detail Alat</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama Alat</th>
                                <td><?= $booking->nama_alat ?></td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td><?= $booking->jumlah ?></td>
                            </tr>
                            <?php if(!empty($booking->keterangan)): ?>
                            <tr>
                                <th>Keterangan</th>
                                <td><?= $booking->keterangan ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">Kembali ke Dashboard</a>
                    <?php if($booking->status == 'pending'): ?>
                        <a href="<?= base_url('dashboard/cancel_booking/' . $booking->id) ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">Batalkan Booking</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>