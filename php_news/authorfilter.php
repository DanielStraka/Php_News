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
    $authorfiltersql = 'SELECT article.id,article.title, article.perex, article.created_at, article.text, author.name, author.surname FROM article
    INNER JOIN author on article.author_id = author.id
    WHERE article.author_id = :id 
    order by article.created_at desc
    ';
    $authorfilterstmt = $conn->prepare($authorfiltersql);
    $authorfilterstmt->execute(['id' => $_GET['id']]);

    $authorfilter = $authorfilterstmt->fetchAll();


//-------------------------------------------------------------------------------------------------------------------------
    $authornamesql = 'SELECT * FROM author
    WHERE author.id = :id 
    ';

    $authornamestmt = $conn->prepare($authornamesql);
    $authornamestmt->execute(['id' => $_GET['id']]);

    $authorname = $authornamestmt->fetch();

//-------------------------------------------------------------------------------------------------------------------------
    $acsql = 'SELECT article_category.article_id, article_category.category_id, category.name FROM article_category
    INNER JOIN category on category.id = article_category.category_id
    ';
    $acstmt = $conn->query($acsql);

    $ac = $acstmt->fetchall();
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
<div>
<div class="head">
<h1 class="Title">Články autora <?= $authorname['name'] ?> <?= $authorname['surname'] ?></h1>
</div>
<div class="main">
    <?php foreach ($authorfilter as $a): ?>
    <div class="article">
        <h2 class="articleTitle"><?= $a['title'] ?></h2>

        <p><?= date_format(date_create($a['created_at']),'d.m.Y H:i') ?></p>

        <p>Kategorie:
        <?php foreach ($ac as $autcat): ?>
            <?php if ($autcat['article_id']==$a['id']): ?>
            
            <a class="articleHREF" href="categoryfilter.php?id=<?= $autcat['category_id'] ?>"><?= $autcat['name'] ?></a>
        
            <?php endif; ?>        
        <?php endforeach; ?>    
        </p>

        <p><?= $a['perex'] ?></p>

        <a class="detailHREF" href="articledetail.php?id=<?= $a['id'] ?>">číst dál</a>
    </div>
    <?php endforeach; ?>
</div>


</body>
</html>