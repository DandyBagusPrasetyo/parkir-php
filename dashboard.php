<?php
date_default_timezone_set('Asia/Jakarta');

require_once 'koneksi.php';

$sql = "SELECT id, card_code, name FROM members";
$result = $konek->query($sql);
$members = [];
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

$notification_member = '';
$notification_checkin = '';
$notification_checkout = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_code = isset($_POST['card_code']) ? $_POST['card_code'] : null;
    $ticket_number = isset($_POST['ticket_number']) ? $_POST['ticket_number'] : null;
    $session_id = isset($_POST['session_id']) ? $_POST['session_id'] : '';

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

        $response = $notification;
        if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
            echo $response;
            exit();
        }

        $konek->close();
    } elseif ($ticket_number) {
        $vehicle_number = isset($_POST['vehicle_number']) ? $_POST['vehicle_number'] : null;
        //$vehicle_type = isset($_POST['vehicle_type']) ? $_POST['vehicle_type'] : null;
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
            $vehicle_type = $transaction['vehicle_type'];
            if(!$vehicle_number) {
                $vehicle_number = $transaction['vehicle_number'];
            }

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
                $notification_checkout = "
                    <h4>Ticket Information</h4>
                    <table style='width: 100%; color: #fff; background-color: #333; padding: 10px; border-radius: 5px;'>
                    <tr>
                        <td><strong>Ticket No:</strong></td>
                        <td>$ticket_number</td>
                    </tr>
                    <tr>
                        <td><strong>Vehicle Type:</strong></td>
                        <td>$vehicle_type</td>
                    </tr>
                    <tr>
                        <td><strong>Vehicle Number:</strong></td>
                        <td>$vehicle_number</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td style='color: green;'>OUT</td>
                    </tr>
                    <tr>
                        <td><strong>Total Price:</strong></td>
                        <td>Rp $total_price</td>
                    </tr>
                    <tr>
                        <td><strong>Duration:</strong></td>
                        <td>$hours jam</td>
                    </tr>
                    </table>
                    <div style='margin-top: 10px; text-align: center;'>
                    <a href='print_nota.php?ticket_number=$ticket_number&vehicle_number=$vehicle_number&duration=$hours' target='_blank'><button type='submit' class='btn btn-warning'>Print Nota</button></a>
                    </div>";

            } else {
                $notification_checkout = "Error updating transaction: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $notification_checkout = "Transaction with Ticket No: $ticket_number not found or already checked out.";
        }

        $response_checkin = $notification_checkout;

        if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
            echo $response_checkin;
            exit();
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

        $sql = "INSERT INTO transactions (vehicle_type, vehicle_number, date, time, status, payment, price, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $konek->prepare($sql);
        $stmt->bind_param("ssssssii",$vehicle_type, $vehicle_number, $date, $time, $status, $payment, $price, $session_id);

        if ($stmt->execute()) {
            $transaction_id = $stmt->insert_id;

            $time_parts = explode(":", date("H:i:s"));
            $ticket_number = $transaction_id . implode('', $time_parts);

            $sql_update = "UPDATE transactions SET ticket_number = ? WHERE id = ?";
            $stmt_update = $konek->prepare($sql_update);
            $stmt_update->bind_param("ii", $ticket_number, $transaction_id);

            if ($stmt_update->execute()) {
                $notification_checkin = "
                    <h4>Ticket Information</h4>
                    <table style='width: 100%; color: #fff; background-color: #333; padding: 10px; border-radius: 5px;'>
                    <tr>
                        <td><strong>Ticket No:</strong></td>
                        <td>$ticket_number</td>
                    </tr>
                    <tr>
                        <td><strong>Type:</strong></td>
                        <td>$vehicle_type</td>
                    </tr>
                    <tr>
                        <td><strong>Vehicle Number:</strong></td>
                        <td>$vehicle_number</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td style='color: green;'>$status</td>
                    </tr>
                    </table>";

            } else {
                $notification_checkin = "Error updating ticket number: " . $stmt_update->error;
            }

            $stmt_update->close();
        } else {
            $notification_checkin = "Error: " . $stmt->error;
        }

        $response_checkin = $notification_checkin;

        if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
            echo $response_checkin;
            exit();
        }

        $stmt->close();
        $konek->close();
    }
}
?>
<div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Check IN (Non Member)</h6>
                </div>
                <div class="card-body">
                    <form id="checkin_form" action="index.php?page=dashboard" method="POST">
                        <div class="form-group">
                            <label for="vehicle_number">Vehicle Number/Plat Kendaraan (Opsional)</label>
                            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number">
                        </div>
                        <div class="form-group">
                            <label for="vehicle_type">Vehicle Type</label>
                            <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                                <option value="Sepeda Motor">Sepeda Motor (Rp 3.000/Jam)</option>
                                <option value="Mobil">Mobil (Rp 5.000/Jam)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                    </form>
                </div>
                <div class="card-footer">
                    <p id="checkin_notification"></p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Check OUT (Non Member)</h6>
                </div>
                <div class="card-body">
                    <form id="checkout_form" action="index.php?page=dashboard" method="POST">
                        <div class="form-group">
                            <label for="ticket_number">Ticket No</label>
                            <input type="text" class="form-control" id="ticket_number" name="ticket_number" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_number">Vehicle Number/Plat Kendaraan (Opsional)</label>
                            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number">
                        </div>
                        <button type="submit" class="btn btn-primary">Check Out</button>
                    </form>
                </div>
                <div class="card-footer">
                    <p id="checkout_notification"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Check IN/OUT (Member Card)</h6>
                </div>
                <div class="card-body">
                    <form id="member_form" action="index.php?page=dashboard" method="POST">
                        <div class="form-group">
                            <label for="card_code">Card Code</label>
                            <select class="form-control" id="card_code" name="card_code" required>
                                <option value="">Select Member Card</option>
                                <?php foreach ($members as $member): ?>
                                    <option value="<?php echo $member['card_code']; ?>">
                                        <?php echo $member['card_code'] . ' - ' . $member['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Check IN/OUT</button>
                    </form>
                </div>
                <div class="card-footer">
                    <p id="member_notification"></p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
        </div>
    </div>
</div>
