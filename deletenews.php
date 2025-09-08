<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        
        header("Location: listnews.php");
        exit();
    } else {
        echo "Lỗi khi xóa: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Yêu cầu không hợp lệ.";
}

mysqli_close($conn);
?>