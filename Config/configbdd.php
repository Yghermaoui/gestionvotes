<?php
$host = '127.0.0.1';
$port = '3308';  
$dbname = 'outilelection';
$username = 'root'; 
$password = '';  

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
   
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
