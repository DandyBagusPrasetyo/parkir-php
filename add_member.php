<?php
require_once 'koneksi.php';

function generateCardCode($vehicle_type, $vehicle_number) {
    $prefix = ($vehicle_type == 'Sepeda Motor') ? 'MTR' : 'MBL';
    return $prefix . '-' . $vehicle_number;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_color = $_POST['vehicle_color'];
    $vehicle_number = $_POST['vehicle_number'];
    $card_code = generateCardCode($vehicle_type, $vehicle_number);

    $sql = "INSERT INTO members (name, phone, address, vehicle_type, vehicle_model, vehicle_color, vehicle_number, card_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $konek->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $phone, $address, $vehicle_type, $vehicle_model, $vehicle_color, $vehicle_number, $card_code);

    if ($stmt->execute()) {
        header("Location: index.php?page=members");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $konek->close();
}
?>

<div>
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Add a New Member</h6>
                </div>
                <div class="card-body">
                    <form action="add_member.php" method="POST">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="address" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_type">Vehicle type</label>
                            <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                                <option value="Sepeda Motor">Sepeda Motor</option>
                                <option value="Mobil">Mobil</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_model">Vehicle model</label>
                            <input type="vehicle_model" class="form-control" id="vehicle_model" name="vehicle_model" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_color">Vehicle color</label>
                            <input type="vehicle_color" class="form-control" id="vehicle_color" name="vehicle_color" required>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_number">Vehicle number</label>
                            <input type="vehicle_number" class="form-control" id="vehicle_number" name="vehicle_number" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
