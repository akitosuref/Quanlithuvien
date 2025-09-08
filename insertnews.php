<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>themtintuc</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        include 'header.php';
    ?>
    <?php
    ?>
    <div class="container">
        <div class="sidebar">
            <h1><a href="insertnews.php">Thêm Tin Tức</a></h1>
            <h1><a href="listnews.php">Danh Sách Tin Tức</a></h1>
        </div>
        <main>
            <form action="access.php" method="POST">
                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="describe">Mô Tả</label>
                <input type="text" id="describe" name="describe" required>
                
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" required></textarea>
                
                <input type="submit" value="Thêm Tin">
            </form>
        </main>
    </div>
<?php
        include 'footer.php';
    ?>
</body>
</html>