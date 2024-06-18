<?php
require_once 'koneksi.php';

$sql = "SELECT t.*, u.name AS user_name
        FROM transactions t
        LEFT JOIN users u ON t.user_id = u.id
        WHERE t.ticket_number IS NOT NULL
        AND t.status = 'IN'
        AND NOT EXISTS (
            SELECT 1
            FROM transactions t2
            WHERE t2.ticket_number = t.ticket_number
            AND t2.status = 'OUT'
        )";
$result = $konek->query($sql);
$no = 1;
?>

<div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Vehicle List With Ticket</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ticket No</th>
                            <th>Vehicle No</th>
                            <th>Date / Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Employee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['ticket_number']; ?></td>
                            <td><?php echo $row['vehicle_number']; ?></td>
                            <td><?php echo $row['date'] ." / ". $row['time']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['user_name']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
