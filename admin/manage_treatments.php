<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');
?>

<div class="main-content animated">
    <h1 class="mb-4">Manage Treatments</h1>
    <a href="add_treatment.php" class="btn btn-primary mb-4">Add New Treatment</a>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Treatment Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM treatments";
                $result = $con->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['treatment_name']}</td>
                            <td>
                                <a href='edit_treatment.php?id={$row['treatment_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_treatment.php?id={$row['treatment_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>