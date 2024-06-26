<?php
require_once 'koneksi.php';

$ticket_number = $_GET['ticket_number'] ?? null;
$vehicle_number = $_GET['vehicle_number'] ?? null;
$duration = $_GET['duration'];

if ($ticket_number) {
    $stmt = $konek->prepare("SELECT * FROM transactions WHERE ticket_number = ?");
    $stmt->bind_param("i", $ticket_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        if(!$vehicle_number) {
            $veh_num = $transaction['vehicle_number'];
        } else {
            $veh_num = $vehicle_number;
        }

        echo "
        <html>
        <head>
            <title>Print Nota</title>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { width: 80%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9; }
                h4 { text-align: center; }
                table { width: 100%; margin-top: 20px; border-collapse: collapse; }
                th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
                th { background-color: #f2f2f2; }
                .btn { display: block; width: 100px; margin: 20px auto; padding: 10px; background-color: #007bff; color: #fff; text-align: center; border: none; border-radius: 5px; text-decoration: none; }
                .btn:hover { background-color: #0056b3; }
                @media print {
                    .btn {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h4>Ticket Information - Parkir XYZ</h4>
                <table>
                    <tr>
                        <th>Ticket No</th>
                        <td>{$transaction['ticket_number']}</td>
                    </tr>
                    <tr>
                        <th>Member ID</th>
                        <td>{$transaction['member_id']}</td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td>{$transaction['user_id']}</td>
                    </tr>
                    <tr>
                        <th>Vehicle Type</th>
                        <td>{$transaction['vehicle_type']}</td>
                    </tr>
                    <tr>
                        <th>Vehicle Number</th>
                        <td>{$veh_num}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{$transaction['date']}</td>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <td>{$transaction['time']}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{$transaction['status']}</td>
                    </tr>
                    <tr>
                        <th>Duration</th>
                        <td>{$duration} Jam</td>
                    </tr>
                    <tr>
                        <th>Payment</th>
                        <td>{$transaction['payment']}</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>Rp {$transaction['price']}</td>
                    </tr>
                </table>
                <a href='#' class='btn' onclick='window.print()'>Print Nota</a>
            </div>
        </body>
        </html>";
    } else {
        echo "No transaction found for ticket number $ticket_number.";
    }

    $stmt->close();
} else {
    echo "Ticket number is required.";
}

$konek->close();
?>
