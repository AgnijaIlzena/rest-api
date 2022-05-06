<?php

/**
 * Ajoute un film
 * Méthode : POST
 */

// Retour d'en-tête
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Récupération de la méthode
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Si la méthode est différente de "POST", 
 * on retourne une erreur
 */
if ($method !== 'POST') {
    // Récupère ou définit le code de réponse HTTP
    http_response_code(405);

    echo json_encode([
        'status' => 405,
        'message' => 'Method Not Allowed'
    ]);

    exit;
}

// Récupère les données envoyées en POST
$datas = json_decode(file_get_contents('php://input'), true);

/**
 * Si tous les champs sont bien remplis, on insère en BDD
 */
if (
    !empty($datas['title']) 
    && !empty($datas['description'])
    && !empty($datas['date'])
    && !empty($datas['time'])
    && !empty($datas['director'])
    && !empty($datas['image'])
    && !empty($datas['trailer'])
) {
    // Nettoie les données
    foreach ($datas as $key => $value) {
        $datas[$key] = htmlspecialchars(strip_tags($value)); 
    }

    // Connexion à la BDD
    require_once 'connexion.php'; 

    // Insertion en BDD
    $query = $db->prepare('INSERT INTO movies (title, description, date, time, director, image, trailer) VALUES (:title, :description, :date, :time, :director, :image, :trailer)');
    $query->bindValue(':title', $datas['title']);
    $query->bindValue(':description', $datas['description']);
    $query->bindValue(':date', $datas['date']);
    $query->bindValue(':time', $datas['time'], PDO::PARAM_INT);
    $query->bindValue(':director', $datas['director']);
    $query->bindValue(':image', $datas['image']);
    $query->bindValue(':trailer', $datas['trailer']);
    $query->execute();

    // Récupération de l'ID nouvellement inséré
    // https://www.php.net/manual/fr/pdo.lastinsertid.php
    $id = $db->lastInsertId();

    // 201 - Created
    http_response_code(201);

    // Retour de la ressource
    echo json_encode([
        'id' => $id,
        ...$datas
    ]);
}
// Sinon on retourne une erreur...
else {
    http_response_code(400);

    echo json_encode([
        'status' => 400,
        'message' => 'Bad Request'
    ]);

    exit;
}