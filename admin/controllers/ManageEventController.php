<?php
$msg = ""; $err = "";
$current_officer = $_SESSION['full_name'];

// 1. ADD EVENT (Backend - simply saves what frontend shrunk and sent)
if (isset($_POST['add_event'])) {
    $title = $conn->real_escape_string(trim($_POST['e_title']));
    $cat = $conn->real_escape_string($_POST['e_cat']);
    $msg_txt = $conn->real_escape_string(trim($_POST['e_msg']));

    $target_dir = "../../uploads/"; // Make sure "uploads" folder exists in the main directory
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $ext = strtolower(pathinfo($_FILES["e_image"]["name"], PATHINFO_EXTENSION));
    if (empty($ext)) $ext = 'jpg';
    $img_name = 'event_' . time() . '.' . $ext; 
    $target_file = $target_dir . $img_name;
    
    // As the image is ALREADY tiny thanks to JS, just directly save it.
    if(move_uploaded_file($_FILES["e_image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO company_events (category, title, message, image_path, posted_by) VALUES ('$cat', '$title', '$msg_txt', '$target_file', '$current_officer')";
        if($conn->query($sql)) { $msg = "New event published!"; }
    } else {
        $err = "Image failed to upload securely. Please try again.";
    }
}

// 2. DELETE EVENT
if (isset($_GET['del']) && $_SESSION['role'] == 'Super Admin') {
    $d = intval($_GET['del']);
    
    // Get the image path to delete the physical file
    $res = $conn->query("SELECT image_path FROM company_events WHERE id=$d");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $imgPath = $row['image_path'];
        if (!empty($imgPath) && file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    
    $conn->query("DELETE FROM company_events WHERE id=$d");
    header("Location: manage_events"); exit();
}
?>