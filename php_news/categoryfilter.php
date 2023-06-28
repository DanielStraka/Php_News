<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<?php 
session_start();
require_once 'database.php';

if(isset($_GET['id'])){
//-------------------------------------------------------------------------------------------------------------------------
    $categoryfilter = 'SELECT article.id,article.title, article.perex, article.created_at, article.text, author.name, author.surname,author.id as authorid FROM article
    INNER JOIN author on article.author_id = author.id
    INNER JOIN article_category on article_category.article_id = article.id
    WHERE article_category.category_id = :id
    order by article.created_at desc
    ';
    $categorystmt = $conn->prepare($categoryfilter);
    $categorystmt->execute(['id' => $_GET['id']]);

    $categoryfilter = $categorystmt->fetchAll();


//-------------------------------------------------------------------------------------------------------------------------
    $categorynamesql = 'SELECT * FROM category
    WHERE category.id = :id 
    ';

    $categorynamestmt = $conn->prepare($categorynamesql);
    $categorynamestmt->execute(['id' => $_GET['id']]);

    $categoryname = $categorynamestmt->fetch();

//-------------------------------------------------------------------------------------------------------------------------

}




?>

<header class="header">
        <div class="header1">
        <a class="odkazy" href="index.php">Zprávy</a>
        <a class="odkazy" href="category.php">Kategorie</a>
        <a class="odkazy" href="author.php">Autoři</a>
        <?php if(isset($_SESSION['author'])): ?>
        <a class="odkazy" href="administration.php">Administrace článků</a>
        <a class="odkazy" href="articleadd.php">Přidat článek</a>
        <?php endif; ?>
        </div>

        <div class="header2">
        <?php if(isset($_SESSION['author'])): ?>
            <?= $_SESSION['author']['name'] ?>
            <?= $_SESSION['author']['surname'] ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['author'])): ?>
        <a class="logout" href="logout.php"> Odhlásit</a>
        <?php else: ?>
        <a class="login" href="login.php">Přihlásit se</a>
        |
        <a class="login" href="authoradd.php">Registrace</a>
        <?php endif; ?>
        </div>
</header>
<div class="head">
<h1 class="Title">Články z kategorie <?= $categoryname['name'] ?></h1>
</div>
<div class="main">
    <?php foreach ($categoryfilter as $c): ?>
    <div class="article">
        <h2 class="articleTitle"><?= $c['title'] ?></h2>

        <p><?= date_format(date_create($c['created_at']),'d.m.Y H:i') ?></p>
        <p>Author: <a class="articleHREF" href="authorfilter.php?id=<?= $c['authorid'] ?>"><?= $c['name'] ?> <?= $c['surname'] ?></a></p>

        <p><?= $c['perex'] ?></p>

        <a class="detailHREF" href="articledetail.php?id=<?= $c['id'] ?>">číst dál</a>
    </div>
    <?php endforeach; ?>
</div>


</body>
</html>