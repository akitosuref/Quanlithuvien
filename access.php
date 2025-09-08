<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title']; 
    $describe = $_POST['describe'];
    $content = $_POST['content'];

    include 'db_connect.php';
    $sql = "INSERT INTO news (title, `describe`, content) VALUES ('$title', '$describe', '$content')";
    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);
        header("Location: listnews.php");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
        mysqli_close($conn);
    }
} else {
    echo "Không có dữ liệu POST.";
}
?>
