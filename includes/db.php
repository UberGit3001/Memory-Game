<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=memory_game;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo " Connexion réussie à la base memory_game !";
} catch (PDOException $e) {
    echo " Erreur : " . $e->getMessage();
}
