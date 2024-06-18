<?php
 require_once 'koneksi.php';

 if (isset($_GET['delete'])) {
     $id = $_GET['delete'];
     $sql = "DELETE FROM members WHERE id = ?";
     $stmt = $konek->prepare($sql);
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $stmt->close();
     header("Location: index.php?page=members");
     exit();
 }

 $sql = "SELECT id, name, address, vehicle_type, vehicle_model, vehicle_color, vehicle_number, card_code FROM members";
 $result = $konek->query($sql);
 ?>

<div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <a href="index.php?page=add_member" class="btn btn-primary">Add Member</a>
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
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><?php echo $row['vehicle_type']; ?></td>
                            <td><?php echo $row['vehicle_model']; ?></td>
                            <td><?php echo $row['vehicle_color']; ?></td>
                            <td><?php echo $row['vehicle_number']; ?></td>
                            <td><?php echo $row['card_code']; ?></td>
                            <td>
                                <a href="index.php?page=edit_member&id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="members.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
