<?php
include('includes/auth.php');
include('includes/header.php');
include('includes/sidebar.php');
include('../connect.php');
?>

<div class="main-content animated">
    <h1 class="mb-4">Manage Lab Tests</h1>
    <a href="add_lab_test.php" class="btn btn-primary mb-4">Add New Lab Test</a>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM lab_tests";
                $result = $con->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['test_name']}</td>
                            <td>{$row['price']}</td>
                            <td>
                                <a href='edit_lab_test.php?id={$row['lt_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_lab_test.php?id={$row['lt_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>