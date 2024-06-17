<?php
require_once 'koneksi.php';

function generateCardCode($vehicle_type, $vehicle_number) {
    $prefix = ($vehicle_type == 'Sepeda Motor') ? 'MTR' : 'MBL';
    return $prefix . '-' . $vehicle_number;
}

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch member data
    $sql = "SELECT * FROM members WHERE id = ?";
    $stmt = $konek->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if (!$member) {
        die("Member not found.");
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

        // Update query
        $sql = "UPDATE members SET name = ?, phone = ?, address = ?, vehicle_type = ?, vehicle_model = ?, vehicle_color = ?, vehicle_number = ?, card_code = ? WHERE id = ?";
        $stmt = $konek->prepare($sql);
        $stmt->bind_param("ssssssssi", $name, $phone, $address, $vehicle_type, $vehicle_model, $vehicle_color, $vehicle_number, $card_code, $id);

        if ($stmt->execute()) {
            header("Location: index.php?page=members");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $konek->close();
    }
} else {
    die("ID not provided or invalid.");
}
?>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Member</h6>
            </div>
            <div class="card-body">
                <form action="edit_member.php?id=<?php echo $member['id']; ?>" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $member['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $member['phone']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo $member['address']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_type">Vehicle Type</label>
                        <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                            <option value="Sepeda Motor" <?php if ($member['vehicle_type'] == 'Sepeda Motor') echo 'selected'; ?>>Sepeda Motor</option>
                            <option value="Mobil" <?php if ($member['vehicle_type'] == 'Mobil') echo 'selected'; ?>>Mobil</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_model">Vehicle Model</label>
                        <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" value="<?php echo $member['vehicle_model']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_color">Vehicle Color</label>
                        <input type="text" class="form-control" id="vehicle_color" name="vehicle_color" value="<?php echo $member['vehicle_color']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_number">Vehicle Number</label>
                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" value="<?php echo $member['vehicle_number']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Member</button>
                </form>
            </div>
        </div>
    </div>
</div>
