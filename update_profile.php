<?php
session_start();
include('connect.php'); // Include your database connection file

$u_id = $_SESSION['uid']; // Get the logged-in user's ID
$response = ['status' => 'error', 'message' => 'An error occurred.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u_name = $_POST['u_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $landmark = $_POST['landmark'];
    $flat_house_no = $_POST['flat_house_no'];
    $pin_no = $_POST['pin_no'];

    // Handle profile picture upload
    $profile_pic = '';
    if (isset($_FILES['u_img']) && $_FILES['u_img']['error'] == 0) {
        $target_dir = "images/profilepic/";
        $target_file = $target_dir . basename($_FILES['u_img']['name']);
        if (move_uploaded_file($_FILES['u_img']['tmp_name'], $target_file)) {
            $profile_pic = $target_file;
        }
    }

    // Update user data in the database
    $sql = "UPDATE users SET u_name = ?, email = ?, phone = ?, address = ?, state = ?, landmark = ?, flat_house_no = ?, pin_no = ?, u_img = ? WHERE u_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssssssi", $u_name, $email, $phone, $address, $state, $landmark, $flat_house_no, $pin_no, $profile_pic, $u_id);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Profile updated successfully!';
        $response['profile_pic'] = $profile_pic; // Return the new profile picture path
    } else {
        $response['message'] = "Error updating profile: " . $stmt->error;
    }
}

echo json_encode($response);
?>