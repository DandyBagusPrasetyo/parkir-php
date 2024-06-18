<?php
require_once 'koneksi.php';

$sql = "SELECT SUM(price) AS total_ticket_earnings
        FROM transactions
        WHERE ticket_number IS NOT NULL
        AND status = 'OUT'
        AND DATE(date) = CURDATE()";

$result = $konek->query($sql);
$row = $result->fetch_assoc();
$total_ticket_earnings = $row['total_ticket_earnings'] ? $row['total_ticket_earnings'] : 0;

$sql_monthly = "SELECT SUM(price) AS total_ticket_earnings_monthly
                FROM transactions
                WHERE ticket_number IS NOT NULL
                AND status = 'OUT'
                AND MONTH(date) = MONTH(CURDATE())
                AND YEAR(date) = YEAR(CURDATE())";

$result_monthly = $konek->query($sql_monthly);
$row_monthly = $result_monthly->fetch_assoc();
$total_ticket_earnings_monthly = $row_monthly['total_ticket_earnings_monthly'] ? $row_monthly['total_ticket_earnings_monthly'] : 0;


$sql_checkin = "SELECT COUNT(*) AS total_checkin
                FROM transactions t1
                WHERE t1.status = 'IN'
                AND t1.ticket_number IS NOT NULL
                AND NOT EXISTS (
                    SELECT 1
                    FROM transactions t2
                    WHERE t2.ticket_number = t1.ticket_number
                    AND t2.status = 'OUT'
                )";

$result_checkin = $konek->query($sql_checkin);
$row_checkin = $result_checkin->fetch_assoc();
$total_checkin = $row_checkin['total_checkin'] ? $row_checkin['total_checkin'] : 0;

$sql_total_members = "SELECT COUNT(*) AS total_members FROM members";
$result_total_members = $konek->query($sql_total_members);
$row_total_members = $result_total_members->fetch_assoc();
$total_members = $row_total_members['total_members'] ? $row_total_members['total_members'] : 0;

?>

<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Ticket Earnings (Today)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo 'Rp ' . number_format($total_ticket_earnings, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Ticket Earnings (Monthly)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo 'Rp ' . number_format($total_ticket_earnings_monthly, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ticket Check IN
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <?php echo $total_checkin; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Member</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php echo $total_members; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
