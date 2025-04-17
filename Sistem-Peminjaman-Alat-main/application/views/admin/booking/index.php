<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Booking</h2>
    <div>
        <a href="<?= base_url('admin/booking/export') ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Daftar Booking</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <ul class="nav nav-tabs" id="bookingTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab">Menunggu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab">Disetujui</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab">Ditolak</a>
                </li>
            </ul>
        </div>
        
        <div class="tab-content" id="bookingTabContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <?php if(empty($bookings)): ?>
                    <div class="alert alert-info">
                        Tidak ada data booking.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mahasiswa</th>
                                    <th>Alat</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($bookings as $booking): ?>
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
                                        <td><?= date('d-m-Y H:i', strtotime($booking->created_at)) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/booking/detail/' . $booking->id) ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <?php if($booking->status == 'pending'): ?>
                                                <a href="<?= base_url('admin/booking/approve/' . $booking->id) ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Setujui
                                                </a>
                                                <a href="<?= base_url('admin/booking/reject/' . $booking->id) ?>" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Tolak
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Tab Content untuk status lainnya mirip dengan yang di atas dengan filter sesuai status -->
            <div class="tab-pane fade" id="pending" role="tabpanel">
                <!-- Isi untuk tab pending -->
            </div>
            <div class="tab-pane fade" id="approved" role="tabpanel">
                <!-- Isi untuk tab approved -->
            </div>
            <div class="tab-pane fade" id="rejected" role="tabpanel">
                <!-- Isi untuk tab rejected -->
            </div>
        </div>
    </div>
</div>