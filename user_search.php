<?php
include 'db_connect.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

$searchResults = []; // ตัวแปรเก็บผลการค้นหา

if (isset($_GET['q'])) {
    $search = $_GET['q'];
    $sql = "SELECT * FROM articles WHERE title LIKE :search OR content LIKE :search";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['search' => '%' . $search . '%']);
    
    while ($row = $stmt->fetch()) {
        $searchResults[] = $row; // เก็บผลการค้นหาไว้ในอาร์เรย์
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Articles</title>
        <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Itim', sans-serif; /* ใช้ฟอนต์ Itim */
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">สถานที่ท่องเที่ยว
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="user_search.php">Search</a></li>
            </ul>
        </div>
    </nav>

    <div class="container" style="padding-top: 70px;">
        <h1 class="mt-5">ค้นหาสถานที่ท่องเที่ยวที่ต้องการ</h1>
        <form action="user_search.php" method="GET" class="my-4">
            <input type="text" name="q" placeholder="Search articles..." class="form-control" required>
            <input type="submit" value="Search" class="btn btn-primary mt-2">
            <a href="user_dashboard.php" class="btn btn-secondary mt-3">ย้อนกลับ</a>
        </form>

        <?php if (isset($_GET['q'])): ?>
            <h2>Results for "<?= htmlspecialchars($search) ?>"</h2>
            <?php if (count($searchResults) > 0): ?>
                <?php foreach ($searchResults as $row): ?>
                    <h2><a href='user_article.php?id=<?= $row['id'] ?>'><?= htmlspecialchars($row['title']) ?></a></h2>
                    <p><?= htmlspecialchars(substr($row['content'], 0, 100)) ?>...</p>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="alert alert-warning">No articles found matching your search.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <footer>
    <p>ข้อมูลติดต่อ: อีเมล dee055909@gmail.com | โทร 080-1475044</p>
</footer>
<style>
                header, footer {
            background: #004d40;
            color: white;
            text-align: center;
            padding: 15px 0;
        }
</style>
</body>
</html>
