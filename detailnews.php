1 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        include 'db_connect.php';
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $sql = "SELECT * FROM news WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                echo "<h1>" . $row['title'] . "</h1>";
                echo "<p>" . $row['describe'] . "</p>";
                echo "<div>" . $row['content'] . "</div>";
            } else {
                echo "No news found.";
            }
        } else {
            echo "Invalid request.";
        }
        mysqli_close($conn);
    ?>
</body>
</html>