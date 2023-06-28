<?php 


require 'database.php';


if(isset($_GET['id']))
{
   
    $stmt = $conn->prepare("DELETE FROM author WHERE id = :id");

    $stmt->execute(['id'=>$_GET['id']]);

    header('Location: administration.php');


}