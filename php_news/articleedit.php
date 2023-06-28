<?php 
session_start();
if (!isset($_SESSION['author'])) {
    header('Location: login.php');
    die();
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

    <script src="js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
     tinymce.init({
       selector: '#mytextarea'
      });
    </script>
</head>

<body>

<?php 
    require_once 'database.php';
//-------------------------------------------------------------------------------------------------------------------------
$authorsql = 'SELECT * FROM author';
$authorstmt = $conn->query($authorsql);
$author = $authorstmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
$categorysql = 'SELECT * FROM category';
$categorystmt = $conn->query($categorysql);
$category = $categorystmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
$ArticleCategorytSql = 'SELECT category_id FROM article_category WHERE article_category.article_id = :article_id';
$ArticleCategorystmt = $conn->prepare($ArticleCategorytSql);
$ArticleCategorystmt->execute([
    ':article_id' => $_GET['id'],
]);
$ArticleCategory = $ArticleCategorystmt->fetchAll();
//-------------------------------------------------------------------------------------------------------------------------
$ArticleCategoryIds = array_map(function ($ac) {
    return $ac['category_id'];
}, $ArticleCategory);
//-------------------------------------------------------------------------------------------------------------------------
$articlesql = 'SELECT * FROM article WHERE id = :id';
$articlestmt = $conn->prepare($articlesql);
$articlestmt->execute([
    ':id' => $_GET['id'],
]);

$article = $articlestmt->fetch();
//-------------------------------------------------------------------------------------------------------------------------
if (isset($_POST['author_id'], $_POST['title'], $_POST['perex'], $_POST['text'], $_POST['visible'])) {

 
    $sql = 'UPDATE article 
            SET author_id = :author_id, title = :title, perex = :perex, text = :text, visible = :visible
            WHERE id = :id';

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':author_id' => $_POST['author_id'],
        ':title' => $_POST['title'],
        ':perex' => $_POST['perex'],
        ':text' => $_POST['text'],
        ':visible' => $_POST['visible'],
        ':id' => $_GET['id'],
    ]);

 
    $sql = 'DELETE FROM article_category WHERE article_id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id' => $_GET['id'],
    ]);

   
    foreach ($_POST['category'] as $category_id) {

        $sql = 'INSERT INTO article_category SET article_id = :article_id, category_id = :category_id';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':article_id'=> $_GET['id'],
            ':category_id' => $category_id,
        ]); 
    }
 header('Location: administration.php');
 die();
}
//-------------------------------------------------------------------------------------------------------------------------

?>
<header>
    <div>
        <a class="odkazy" href="index.php">Zprávy</a>
        <a class="odkazy" href="category.php">Kategorie</a>
        <a class="odkazy" href="author.php">Autoři</a>
        <a class="odkazy" href="administration.php">Administrace článků</a>
        <a class="odkazy" href="articleadd.php">Přidat článek</a>
    </div>
</header>

    <form class="addformarticle" method="post">
        <label  for="">
            Název článku: <br>
            <textarea name="title"  cols="30" rows="10" ><?= isset($article) ? $article['title'] : '' ?></textarea>
        </label>
        <label  for="">
            <br> Perex článku: <br>
            <textarea name="perex"  cols="30" rows="10" ><?= isset($article) ? $article['perex'] : '' ?></textarea>
        </label> 
        <label for="">
            <br> Obsah článku: <br>
            <textarea id="mytextarea" name="text"><?= isset($article) ? $article['text'] : '' ?></textarea>
        </label>
        <br>
        <label for="">
            Autor:
            <select name="author_id" id="" required>
                <option value="" hidden>-- vyberte místnost --</option>
                <?php foreach ($author as $a): ?>
                    <option value="<?= $a['id'] ?>"
                        <?php if ($a['id'] === $article['author_id']): ?>
                            selected
                        <?php endif; ?>
                    ><?= $a['name'] ?> <?= $a['surname'] ?></option>
                <?php endforeach; ?>
            </select>
            
        </label>
        <br>
        <br>
        <label for="">Kategorie:
        <br>
        <?php foreach ($category as $c): ?>
                <input type="checkbox"
                       name="category[]" value="<?= $c['id'] ?>"
                    <?php if (in_array($c['id'], $ArticleCategoryIds)): ?>
                        checked
                    <?php endif; ?>
                >
                <?= $c['name'] ?>
        <?php endforeach; ?>
        <br>
        </label>
        <br>
        <br>
        <label for="">Stav:</label>
        <br>
        <label>
                <input
                    <?php if ($article['visible']==true): ?>
                        checked
                    <?php endif; ?>    
                type="radio"
                       name="visible" value="1">
                Veřejné
        </label>
        <label>
                <input
                    <?php if ($article['visible']==false): ?>
                        checked
                    <?php endif; ?> 
                type="radio"
                       name="visible" value="0">
                Neveřejné
        </label>
        <br><input class="formbutton" type="submit" value="Potvrdit">
    </form>
  </body>
</html>