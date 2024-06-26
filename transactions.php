<?php
require_once 'koneksi.php';

$sql = "SELECT transactions.*, members.card_code, users.name AS user_name
        FROM transactions
        LEFT JOIN members ON transactions.member_id = members.id
        LEFT JOIN users ON transactions.user_id = users.id";
$result = $konek->query($sql);
?>

<div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transactions Logs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ticket No</th>
                            <th>Member Code</th>
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
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['ticket_number']; ?></td>
                            <td><?php echo $row['card_code']; ?></td>
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
