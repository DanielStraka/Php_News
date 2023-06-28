<?php 
session_start();
if (!isset($_SESSION['author'])) {
    header('Location: login.php');
    die();
}


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>


</head>
<body>

<?php 
require_once 'database.php';
//-------------------------------------------------------------------------------------------------------------------------
$sql = 'SELECT article.id, article.title, article.perex, article.created_at, author.name, author.surname,article.author_id,article.visible FROM article
    INNER JOIN author on article.author_id = author.id
    order by article.title
    ';

$stmt = $conn->query($sql);

$articles = $stmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
$acsql = 'SELECT article_category.article_id, article_category.category_id, category.name FROM article_category
INNER JOIN category on category.id = article_category.category_id
';
$acstmt = $conn->query($acsql);

$ac = $acstmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
$authorsql = 'SELECT * FROM author';
$authorstmt = $conn->query($authorsql);

$author = $authorstmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
$categorysql = 'SELECT * FROM category';
$categorystmt = $conn->query($categorysql);

$category = $categorystmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
// $articlecountsql = 'SELECT author.id,count(article.author_id) FROM article inner join author on author.id = article.author_id
// order by author.id';
// $articlecountstmt = $conn->query($articlecountsql);

// $articlecount = $articlecountstmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
// var_dump($articlecount);
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
<h1 class="Title">Administrace</h1>
</div>
<div class="AdministrationMain">

<div  class="TableAuthor">

<h1 class="Title">Autoři</h1>
<a class="ahrefAdministration" href="authoradd.php">přidat</a>
<table>
    <tr>
        <th>ID Autora</th>
        <th>Jméno</th>
        <th>Příjmení</th>
        <th>Akce</th>
    </tr>
    <?php foreach ($author as $auth): ?>
    <tr>
        <td><?= $auth['id'] ?></td>
        <td><?= $auth['name'] ?></td>
        <td><?= $auth['surname'] ?></td>
        <td>
            <a class="addahref" href="authoradd.php?id=<?= $auth['id'] ?>">Upravit</a> /
            <a class="deleteahref" href="authordelete.php?id=<?= $auth['id'] ?>">Smazat</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</div>
<div class="TableCategory">

<h1 class="Title">Kategorie</h1>
<a class="ahrefAdministration" href="categoryadd.php">přidat</a>
<table>
    <tr>
        <th>ID Kategorie</th>
        <th>Název</th>
        <th>Akce</th>
    </tr>
    <?php foreach ($category as $cat): ?>
    <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= $cat['name'] ?></td>
        <td>
            <a class="addahref" href="categoryadd.php?id=<?= $cat['id'] ?>">Upravit</a> /
            <a class="deleteahref" href="categorydelete.php?id=<?= $cat['id'] ?>">Smazat</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</div>

<div class="TableArticle">

<h1 class="Title">Články</h1>
<table>
    <tr>
        <th>ID článku</th>
        <th>Název</th>
        <th>Autor</th>
        <th>Kategorie</th>
        <th>stav</th>
        <th>akce</th>
    </tr>
    <?php foreach ($articles as $a): ?>
    <tr>
        <td><?= $a['id'] ?></td>
        <td><?= $a['title'] ?></td>
        <td><?= $a['name'] ?> <?= $a['surname'] ?></td>
        <td>
        <?php foreach ($ac as $autcat): ?>
            <?php if ($autcat['article_id']==$a['id']): ?>
                <?= $autcat['name'] ?>
            <?php endif; ?>  
        <?php endforeach; ?> 
        </td>
        <?php if ($a['visible']==true): ?>
         <td><p style="color: rgb(0, 128, 255);">veřejné</p></td>
         <?php endif; ?>
         <?php if ($a['visible']==false): ?>
         <td><p style="color: rgb(255, 51, 51);">neveřejné</p></td>
         <?php endif; ?>
        <td>
            <a class="addahref" href="articleedit.php?id=<?= $a['id'] ?>">Upravit</a> /
            <a class="deleteahref" href="articledelete.php?id=<?= $a['id'] ?>">Smazat</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</div>

</div>
    
</body>
</html>