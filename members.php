<div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Member Data</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="membersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle Model</th>
                            <th>Vehicle Color</th>
                            <th>Vehicle Number</th>
                            <th>Card Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'koneksi.php';

                        $query = "SELECT id, name, address, vehicle_type, vehicle_model, vehicle_color, vehicle_number, card_code FROM members";
                        $result = $konek->query($query);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"]. "</td>";
                                echo "<td>" . $row["name"]. "</td>";
                                echo "<td>" . $row["address"]. "</td>";
                                echo "<td>" . $row["vehicle_type"]. "</td>";
                                echo "<td>" . $row["vehicle_model"]. "</td>";
                                echo "<td>" . $row["vehicle_color"]. "</td>";
                                echo "<td>" . $row["vehicle_number"]. "</td>";
                                echo "<td>" . $row["card_code"]. "</td>";
                                echo "<td> action </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No data found</td></tr>";
                        }
                        $konek->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
