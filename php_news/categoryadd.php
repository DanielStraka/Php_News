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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
require_once 'database.php';

if(isset($_GET['id'])){

    $stmt = $conn->prepare('SELECT * FROM category WHERE id = :id');
    $stmt->execute(['id' => $_GET['id']]);

    $row = $stmt->fetch();

}

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
<form class="addform" action="" method="post">
    <label>
        Název:
        <input type="text"  name="name" value="<?php echo isset($_GET['id']) ? $row['name'] :  '' ?>" required>
    </label><br>
    <br>
    <input type="submit" class="formbutton" value="Potvrdit">
    </form>
    
    <?php
    
    if(!empty($_POST)){
        if(!isset($_GET['id'])){
            $sql = 'INSERT INTO category
            SET name = :name';

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $_POST['name'],
                ]);
            $id = $conn-> lastInsertId();
            header('Location: administration.php');
        }
        elseif(isset($_POST['name'], $_GET['id'])){
            $sql = 'UPDATE category 
                SET name = :name
                WHERE id = :id';
    
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $_POST['name'],
                ':id' => $_GET['id'],
            ]);
            header('Location: administration.php');
        }
          
            
    
    }

    ?>

</body>
</html>