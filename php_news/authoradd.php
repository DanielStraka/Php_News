<?php 
session_start();


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

    $stmt = $conn->prepare('SELECT * FROM author WHERE id = :id');
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
        E-mail:<br>
        <input type="email"  name="email" value="<?php echo isset($_GET['id']) ? $row['email'] :  '' ?>" required>
    </label><br>
    <br>
    <label>
        Heslo:<br>
        <input type="password"  name="password" required>
    </label><br>
    <br>
    <label>
        Jméno:<br>
        <input type="text"  name="name" value="<?php echo isset($_GET['id']) ? $row['name'] :  '' ?>" required>
    </label><br>
    <br>
    <label>
        Příjmení:<br>
        <input type="text"  name="surname" value="<?php echo isset($_GET['id']) ? $row['surname'] :  '' ?>" required>
    </label><br>
    <br>
    <input type="submit" class="formbutton"  value="Potvrdit">
    </form>
    
    <?php
    
    if(!empty($_POST)){
        if(!isset($_GET['id'])){
            $sql = 'INSERT INTO author
            SET name = :name, surname = :surname, email = :email, password = :password';

            $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $_POST['name'],
                ':surname' => $_POST['surname'],
                ':email' => $_POST['email'],
                ':password' => $hash,
                ]);
            header('Location: administration.php');
        }
        elseif(isset($_POST['name'], $_POST['surname'], $_GET['id'])){
            $sql = 'UPDATE author 
                SET name = :name, surname = :surname, email = :email, password = :password
                WHERE id = :id';
    
            $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name' => $_POST['name'],
                ':surname' => $_POST['surname'],
                ':email' => $_POST['email'],
                ':password' => $hash,
                ':id' => $_GET['id'],
            ]);
            header('Location: administration.php');
        }
             
    }

    ?>
</body>
</html>