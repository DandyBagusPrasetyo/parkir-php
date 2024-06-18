<?php
require_once 'koneksi.php';

$notification_member = '';
$notification_checkin = '';
$notification_checkout = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_code = isset($_POST['card_code']) ? $_POST['card_code'] : null;
    $ticket_number = isset($_POST['ticket_number']) ? $_POST['ticket_number'] : null;

    if($card_code) {
        $sql = "SELECT * FROM members WHERE card_code = ?";
        $stmt = $konek->prepare($sql);
        $stmt->bind_param("s", $card_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $member = $result->fetch_assoc();

        if ($member) {
            $member_id = $member['id'];
            $vehicle_number = $member['vehicle_number'];

            $sql = "SELECT * FROM transactions WHERE member_id = ? ORDER BY id DESC LIMIT 1";
            $stmt = $konek->prepare($sql);
            $stmt->bind_param("i", $member_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $last_transaction = $result->fetch_assoc();

            $status = 'IN';
            if ($last_transaction) {
                if ($last_transaction['status'] == 'IN') {
                    $status = 'OUT';
                }
            }

            $date = date("Y-m-d");
            $time = date("H:i:s");
            $payment = 'Cash';
            $price = 0;

            $sql = "INSERT INTO transactions (member_id, user_id, vehicle_number, date, time, status, payment, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $konek->prepare($sql);
            $stmt->bind_param("iissssss", $member_id, $session_id, $vehicle_number, $date, $time, $status, $payment, $price);

            if ($stmt->execute()) {
                if($status == 'IN') {
                    $notification = "<span style='color: green'>Card No: $card_code, Status: $status";
                } else {
                    $notification = "<span style='color: red'>Card No: $card_code, Status: $status";
                }
            } else {
                $notification = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $notification = "Member not found.";
        }
        $konek->close();
    } elseif ($ticket_number) {
        $vehicle_number = isset($_POST['vehicle_number']) ? $_POST['vehicle_number'] : null;
        $vehicle_type = isset($_POST['vehicle_type']) ? $_POST['vehicle_type'] : null;
        $date_out = date("Y-m-d");
        $time_out = date("H:i:s");

        $sql = "SELECT * FROM transactions WHERE ticket_number = ? AND status = 'IN'";
        $stmt = $konek->prepare($sql);
        $stmt->bind_param("i", $ticket_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $transaction = $result->fetch_assoc();

        if ($transaction) {
            $date_in = $transaction['date'];
            $time_in = $transaction['time'];
            $price_per_hour = $transaction['price'];

            $datetime_in = new DateTime("$date_in $time_in");
            $datetime_out = new DateTime("$date_out $time_out");
            $interval = $datetime_in->diff($datetime_out);
            $hours = $interval->h + ($interval->days * 24);
            if ($interval->i > 0 || $interval->s > 0) {
                $hours += 1;
            }

            $total_price = $hours * $price_per_hour;

            $sql = "UPDATE transactions SET status = 'OUT', date = ?, time = ?, price = ? WHERE ticket_number = ? AND status = 'IN'";
            $stmt = $konek->prepare($sql);
            $stmt->bind_param("ssii", $date_out, $time_out, $total_price, $ticket_number);

            if ($stmt->execute()) {
                $notification_checkout = "<span style='color: green'>Ticket No: $ticket_number, Status: OUT, Total Price: Rp $total_price ($hours jam)</span>";
            } else {
                $notification_checkout = "Error updating transaction: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $notification_checkout = "Transaction with Ticket No: $ticket_number not found or already checked out.";
        }

        $konek->close();
    } else {
        $vehicle_number = isset($_POST['vehicle_number']) ? $_POST['vehicle_number'] : null;
        $vehicle_type = $_POST['vehicle_type'];
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $status = 'IN';
        $payment = 'Cash';

        $price = ($vehicle_type == 'Sepeda Motor') ? 3000 : 5000;

        $sql = "INSERT INTO transactions (vehicle_number, date, time, status, payment, price, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $konek->prepare($sql);
        $stmt->bind_param("sssssii", $vehicle_number, $date, $time, $status, $payment, $price, $session_id);

        if ($stmt->execute()) {
            $transaction_id = $stmt->insert_id;

            $time_parts = explode(":", date("H:i:s"));
            $ticket_number = $transaction_id . implode('', $time_parts);

            $sql_update = "UPDATE transactions SET ticket_number = ? WHERE id = ?";
            $stmt_update = $konek->prepare($sql_update);
            $stmt_update->bind_param("ii", $ticket_number, $transaction_id);

            if ($stmt_update->execute()) {
                $notification_checkin = "<span style='color: green'>Ticket No: $ticket_number, Type: $vehicle_type, Status: $status";
            } else {
                $notification_checkin = "Error updating ticket number: " . $stmt_update->error;
            }

            $stmt_update->close();
        } else {
            $notification_checkin = "Error: " . $stmt->error;
        }

        $stmt->close();
        $konek->close();
    }
}
?>
<div>
    <div class="row">
        <div class="col-lg-4">
        </div>
        <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Check IN/OUT (Member Card)</h6>
                    </div>
                    <div class="card-body">
                        <form action="index.php?page=dashboard" method="POST">
                            <div class="form-group">
                                <label for="card_code">Card Code</label>
                                <input type="text" class="form-control" id="card_code" name="card_code" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Check IN/OUT</button>
                        </form>
                    </div>
                    <?php if (isset($notification)): ?>
                    <div class="card-footer">
                        <p><?php echo $notification; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <div class="col-lg-4">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Check IN (Non Member)</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?page=dashboard" method="POST">
                        <div class="form-group">
                            <label for="vehicle_number">Vehicle Number/Plat Kendaraan (Opsional)</label>
                            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number">
                        </div>
                        <div class="form-group">
                            <label for="vehicle_type">Vehicle type</label>
                            <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                                <option value="Sepeda Motor">Sepeda Motor (Rp 3.000/Jam)</option>
                                <option value="Mobil">Mobil (Rp 5.000/Jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                    </form>
                </div>
                <?php if ($notification_checkin): ?>
                <div class="card-footer">
                    <p><?php echo $notification_checkin; ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Check OUT (Non Member)</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?page=dashboard" method="POST">
                        <div class="form-group">
                            <label for="ticket_number">Ticket No</label>
                            <input type="text" class="form-control" id="ticket_number" name="ticket_number" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_number">Vehicle Number/Plat Kendaraan (Opsional)</label>
                            <input type="email" class="form-control" id="vehicle_number" name="vehicle_number">
                        </div>
                        <button type="submit" class="btn btn-primary">Check Out</button>
                    </form>
                </div>
                <?php if ($notification_checkout): ?>
                <div class="card-footer">
                    <p><?php echo $notification_checkout; ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
