<?php

/**
 * Récupération de tous les films
 * Méthode : GET
 */

// Retour d'en-tête
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');

// Récupération de la méthode
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Si la méthode est différente de "GET", 
 * on retourne une erreur
 */
if ($method !== 'GET') {
    // Récupère ou définit le code de réponse HTTP
    http_response_code(405);

    echo json_encode([
        'status' => 405,
        'message' => 'Method Not Allowed'
    ]);

    exit;
}

/**
 * Si le paramètre "id" n'existe pas dans l'URL,
 * on retourne une erreur 400
 */
if (empty($_GET['id'])) {
    http_response_code(400);

    echo json_encode([
        'status' => 400,
        'message' => 'Bad Request'
    ]);

    exit;
}

// Récupération de la valeur du paramètre "id"
$id = htmlspecialchars(strip_tags($_GET['id']));

// Connexion à la BDD
require_once 'connexion.php';

// Sélection en BDD
$query = $db->prepare('SELECT * FROM movies WHERE id = :id');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();

// Récupère l'enregistrement trouvé
$movie = $query->fetch();

/**
 * Si aucun résultat,
 * on retourne une erreur 404
 */
if (!$movie) {
    http_response_code(404);

    echo json_encode([
        'status' => 404,
        'message' => 'Not Found'
    ]);

    exit;
}

// 200 - OK
http_response_code(200);

// Retourne les données au format JSON
echo json_encode($movie);