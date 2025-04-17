<h2 class="mb-4">Detail Booking</h2>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Booking</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">NIM</th>
                            <td><?= $booking->nim ?></td>
                        </tr>
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <td><?= $booking->mahasiswa_nama ?></td>
                        </tr>
                        <tr>
                            <th>Nama Alat</th>
                            <td><?= $booking->alat_nama ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Peminjaman</th>
                            <td><?= date('d-m-Y', strtotime($booking->tanggal)) ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td><?= $booking->jumlah ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if ($booking->status == 'pending'): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif ($booking->status == 'approved'): ?>
                                    <span class="badge bg-success">Disetujui</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Booking</th>
                            <td><?= date('d-m-Y H:i', strtotime($booking->created_at)) ?></td>
                        </tr>
                    </table>
                </div>
                
                <div class="mt-3">
                    <?php if ($booking->status == 'pending'): ?>
                        <a href="<?= base_url('admin/verify_booking/' . $booking->id . '/approved') ?>" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin menyetujui booking ini?')">Setujui</a>
                        <a href="<?= base_url('admin/verify_booking/' . $booking->id . '/rejected') ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak booking ini?')">Tolak</a>
                    <?php endif; ?>
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>