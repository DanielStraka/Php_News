<?php 
session_start();
if (!isset($_SESSION['author'])) {
    header('Location: login.php');
    die();
}


?>
<?php 


require 'database.php';


if(isset($_GET['id']))
{
   
    $stmt = $conn->prepare("DELETE FROM article WHERE id = :id");

    $stmt->execute(['id'=>$_GET['id']]);

    header('Location: administration.php');


}