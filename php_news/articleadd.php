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
if (isset($_POST['author_id'], $_POST['title'], $_POST['perex'], $_POST['text'], $_POST['visible'])) {

    
        $sql = 'INSERT INTO article
        SET author_id = :author_id, title = :title, perex = :perex, text = :text, visible = :visible , created_at = now()';

        $stmt = $conn->prepare($sql);
        $stmt->execute([
        ':author_id' => $_POST['author_id'],
        ':title' => $_POST['title'],
        ':perex' => $_POST['perex'],
        ':text' => $_POST['text'],
        ':visible' => $_POST['visible'],
        ]);
        $article_id = $conn->lastInsertId();
    
    
    

    foreach ($_POST['category'] as $category_id) {

        $sql = 'INSERT INTO article_category SET article_id = :article_id, category_id = :category_id';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':article_id'=> $article_id,
            ':category_id' => $category_id,
        ]); 
    }
   header('Location: administration.php');
   die();

}
//-------------------------------------------------------------------------------------------------------------------------

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

    <form class="addformarticle" method="post">
        <label  for="">
            Název článku: <br>
            <textarea name="title"   cols="93" rows="10" ></textarea>
        </label>
        <label  for="">
            <br> Perex článku: <br>
            <textarea name="perex"  cols="93" rows="10" ></textarea>
        </label> 
        <label for="">
            <br> Obsah článku: <br>
            <textarea id="mytextarea" name="text"></textarea>
        </label>
        <label   for="">
            <br> Autor:
            <select name="author_id" id="" required>
                <option value="" hidden>-- vyberte autora --</option>
                <?php foreach ($author as $aut): ?>
                <option value="<?= $aut['id'] ?>"
                ><?= $aut['name'] ?> <?= $aut['surname'] ?></option>
                <?php endforeach; ?>
            </select> <br>
            <br>
            <br>
        </label>
        <label for="">Kategorie:<br>
        <?php foreach ($category as $c): ?>
                <input type="checkbox"
                       name="category[]" value="<?= $c['id'] ?>">
                    <?= $c['name'] ?>
        <?php endforeach; ?>
        <br>
        </label>
        <br>
        <br>
       
        <label for="">Stav:</label>
        <br>
        <label>
                <input type="radio"
                       name="visible" value="1">
                Veřejné
        </label>
        <label>
                <input type="radio"
                       name="visible" value="0" checked >
                Neveřejné
        </label>
        <input class="formbutton" type="submit" value="Potvrdit">
    </form>
  </body>
</html>