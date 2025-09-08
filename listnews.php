<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>list news</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
        include 'header.php';
    ?>
    <div class="container">
        <div class="sidebar">
            <h1><a href="listnews.php">List News</a></h1>
            <h1><a href="insertnews.php">Thêm Tin Tức</a></h1>
        </div>
        <main>
            <?php
                include 'db_connect.php';
                $sql = "SELECT * FROM news order by id desc limit 10";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){?>
                        <a href="detailnews.php?id=<?php echo $row['id']; ?>">
                        <h2><?php  echo $row['title'];?></h2>
                        <p><?php  echo $row['describe'];?></p>
                        </a>
                        <a href="deletenews.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa tin này không?');">Xóa</a>
                        <a href="editnews.php?id=<?php echo $row['id']; ?>">Sửa</a>
                     <?php  }
                     mysqli_free_result($result);
                     ?>
                    <?php }
                else{
                    echo "khong co du lieu";
                }
                mysqli_close($conn);
            ?>
        <ul>
            <li><a href="https://vnexpress.net/thoi-su">Thời Sự</a></li>
            <li><a href="https://vnexpress.net/the-gioi">Thế Giới</a></li>
            <li><a href="https://vnexpress.net/kinh-doanh">Kinh Doanh</a></li>
        </ul>
        </main>
    </div>
    <?php
        include 'footer.php';
    ?>
    
</body>
</html>