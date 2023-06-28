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
if (isset($_POST['email'], $_POST['password'])) {
    require_once 'database.php';

    $sql = 'SELECT * FROM author WHERE email = :email';
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'email' => $_POST['email'],
    ]);

    $author = $stmt->fetch();
    
    if ($author === false) {
        header('Location: login.php?msg=badEmail');
        die();
    }
    if(!password_verify($_POST['password'], $author['password'])){
        header('Location: login.php?msg=badPassword');
        die();
    }


    session_start();

    unset($author['password'], $author[2]);

    $_SESSION['author'] = $author;

    var_dump($_SESSION);
   header('Location: index.php?msg=registered');

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
        Přihlašovací email
        <input type="email" name="email" placeholder="Zadejte email">
    </label>
    <br>
    <br>
    <label>
        Heslo
        <input type="password" name="password" placeholder="Zadejte heslo">
    </label>
    <br>
    <br>
    <div>
        <button class="formbutton">Přihlásit se</button>
    </div>

</form>

    
    <?php

             

    ?>
</body>
</html>